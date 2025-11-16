<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Services\PhpMailerService;

class OrderController extends Controller
{
    protected PhpMailerService $mailer;

    public function __construct(PhpMailerService $mailer)
    {
        $this->middleware('auth');
        $this->mailer = $mailer;
    }
    /**
     * Display customer orders.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderItems.product', 'shippingAddress'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        // Calculate subtotal (sum of all cart items)
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->current_price;
        });

        // Calculate delivery fee (free for orders above ₱1,000, otherwise ₱150)
        $deliveryFee = $subtotal >= 1000 ? 0 : 150;

        // Calculate total (subtotal + delivery fee)
        $total = $subtotal + $deliveryFee;

        return view('customer.orders.create', compact('cartItems', 'subtotal', 'deliveryFee', 'total'));
    }

    /**
     * Display the specified order.
     */
    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['orderItems.product', 'shippingAddress', 'tracking']);

        return view('customer.orders.show', compact('order'));
    }

    /**
     * Store a new order from cart.
     */
    public function store(Request $request)
    {
        // Debug: Log the incoming request
        Log::info('Order creation request received', [
            'all_data' => $request->all(),
            'user_id' => Auth::id(),
            'delivery_type' => $request->delivery_type,
            'has_cart_items' => CartItem::where('user_id', Auth::id())->exists()
        ]);

        // Prepare validation rules based on delivery type
        $rules = [
            'name' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'delivery_type' => 'required|in:delivery,pickup',
            'delivery_date' => 'required|date|after:today',
            'notes' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:cod',
            'terms' => 'required|accepted'
        ];

        // Add conditional validation rules
        if ($request->delivery_type === 'delivery') {
            $rules['delivery_time'] = 'required|date_format:H:i';
            $rules['address'] = 'required|string|max:255';
            $rules['city'] = 'required|string|max:100';
            $rules['postal_code'] = 'required|string|max:20';
        } else {
            $rules['pickup_time'] = 'required|date_format:H:i';
        }

        $request->validate($rules, [
            'delivery_time.required' => 'Please select a delivery time.',
            'pickup_time.required' => 'Please select a pickup time.',
            'address.required' => 'Please provide your delivery address.',
            'city.required' => 'Please provide your city.',
            'postal_code.required' => 'Please provide your postal code.',
            'terms.required' => 'You must agree to the Terms and Conditions.',
            'delivery_date.after' => 'Delivery date must be after today.',
        ]);

        Log::info('Validation passed successfully');

        // Check if delivery is on Sunday
        $deliveryDate = \Carbon\Carbon::parse($request->delivery_date);
        if ($deliveryDate->isSunday()) {
            return redirect()->back()->withErrors(['delivery_date' => 'Sorry, we do not deliver on Sundays. Please choose another date.'])->withInput();
        }

        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->withErrors(['cart' => 'Your cart is empty.'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Calculate totals
            $subtotal = $cartItems->sum(function ($item) {
                return $item->quantity * $item->product->current_price;
            });

            // No delivery fee for pickup orders
            $deliveryFee = $request->delivery_type === 'pickup' ? 0 : ($subtotal >= 1000 ? 0 : 150);
            $total = $subtotal + $deliveryFee;

            Log::info('Order calculation completed', [
                'subtotal' => $subtotal,
                'delivery_fee' => $deliveryFee,
                'total' => $total,
                'cart_items_count' => $cartItems->count()
            ]);

            // Additional validation: ensure cart items still exist and are valid
            if ($cartItems->isEmpty()) {
                DB::rollback();
                return redirect()->back()->withErrors(['cart' => 'Your cart is empty or items have been removed.'])->withInput();
            }

            // Create order
            Log::info('Creating order with data:', [
                'user_id' => Auth::id(),
                'delivery_type' => $request->delivery_type,
                'subtotal' => $subtotal,
                'total' => $total
            ]);

            $orderData = [
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'tracking_number' => 'TRK-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'shipping_amount' => $deliveryFee,
                'total_amount' => $total,
                'delivery_type' => $request->delivery_type,
                'delivery_date' => $request->delivery_date,
                'delivery_time' => $request->delivery_type === 'delivery' ? $request->delivery_time : null,
                'pickup_time' => $request->delivery_type === 'pickup' ? $request->pickup_time : null,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'status' => 'pending',
                'notes' => $request->notes
            ];

            Log::info('Order data prepared:', $orderData);

            $order = Order::create($orderData);

            Log::info('Order created successfully with ID: ' . $order->id);

            // Create delivery address only for delivery orders
            if ($request->delivery_type === 'delivery' && !empty($request->address)) {
                $nameParts = explode(' ', $request->name, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';

                $shippingAddress = OrderAddress::create([
                    'type' => 'shipping',
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'address_line_1' => $request->address,
                    'city' => $request->city,
                    'state' => 'Philippines', // Default state
                    'postal_code' => $request->postal_code,
                    'country' => 'Philippines',
                    'phone' => $request->phone,
                    'email' => $request->email
                ]);

                // Link the address to the order
                $order->update(['shipping_address_id' => $shippingAddress->id]);
            }

            // Create order items
            Log::info('Creating order items for cart items:', ['count' => $cartItems->count()]);

            foreach ($cartItems as $cartItem) {
                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->current_price,
                    'total_price' => $cartItem->quantity * $cartItem->product->current_price,
                    'options' => [
                        'add_ons' => $cartItem->add_ons,
                        'personal_message' => $cartItem->personal_message
                    ],
                    'notes' => $cartItem->personal_message
                ];

                Log::info('Creating order item:', $orderItemData);

                OrderItem::create($orderItemData);
            }

            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            // Send order placed email
            $this->sendOrderPlacedEmail($order, $request->email ?? Auth::user()->email);

            return redirect()->route('orders.show', $order)
                ->with('success', 'Order placed successfully! Your order number is ' . $order->order_number . '. We will contact you soon to confirm your order.');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollback();
            Log::error('Database error during order creation: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'sql' => $e->getSql(),
                'bindings' => $e->getBindings()
            ]);
            return redirect()->back()->withErrors(['general' => 'Database error occurred. Please try again.'])->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order creation failed: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->withErrors(['general' => 'An error occurred while placing your order. Please try again.'])->withInput();
        }
    }

    /**
     * Request order cancellation.
     */
    public function requestCancellation(Order $order)
    {
        Log::info('Cancellation request received', [
            'order_id' => $order->id,
            'user_id' => Auth::id(),
            'order_user_id' => $order->user_id,
            'order_status' => $order->status,
            'request_data' => request()->all()
        ]);

        if ($order->user_id !== Auth::id()) {
            Log::warning('Unauthorized cancellation attempt', [
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'order_user_id' => $order->user_id
            ]);
            abort(403);
        }

        if (!in_array($order->status, ['pending', 'confirmed'])) {
            Log::warning('Cancellation attempt for invalid status', [
                'order_id' => $order->id,
                'order_status' => $order->status
            ]);
            return redirect()->back()->with('warning', '⚠️ Cannot cancel order in current status. Only pending or confirmed orders can be cancelled.');
        }

        try {
            // For pending orders, cancel immediately without admin confirmation
            if ($order->status === 'pending') {
                $order->update([
                    'status' => 'cancelled',
                    'cancellation_requested' => true
                ]);

                Log::info('Order cancelled immediately (pending status)', [
                    'order_id' => $order->id,
                    'status' => $order->status
                ]);

                return redirect()->back()->with('success', '✅ Order cancelled successfully! Your order has been cancelled immediately.');
            }

            // For confirmed orders, request cancellation (requires admin approval)
            if ($order->status === 'confirmed') {
                $order->update(['cancellation_requested' => true]);

                Log::info('Cancellation request submitted (confirmed status)', [
                    'order_id' => $order->id,
                    'cancellation_requested' => $order->cancellation_requested
                ]);

                return redirect()->back()->with('success', '✅ Cancellation request submitted successfully! We will review your request and contact you within 24 hours.');
            }

        } catch (\Exception $e) {
            Log::error('Cancellation request failed', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', '❌ Failed to submit cancellation request. Please try again or contact customer support.');
        }
    }

    private function sendOrderPlacedEmail(Order $order, string $email): void
    {
        if (!$email) {
            return;
        }

        $user = Auth::user();
        $toName = $user ? ($user->name ?? trim($user->first_name . ' ' . $user->last_name)) : $email;

        $subject = 'Order Placed - ' . config('app.name') . ' (Order ' . $order->order_number . ')';
        $htmlBody = view('emails.orders.placed', [
            'order' => $order,
            'user' => $user,
        ])->render();

        $this->mailer->send($email, $toName, $subject, $htmlBody);
    }

}
