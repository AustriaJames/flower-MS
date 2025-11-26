@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-5">
                <h2 class="fw-bold mb-3" style="color: #5D2B4C;">
                    <i class="fas fa-calendar-plus me-2"></i>Book Your Event
                </h2>
                <p class="lead text-muted">Let us help you create the perfect floral arrangement for your special occasion</p>
            </div>

            <div class="card border-0 shadow-lg" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h4 class="fw-bold mb-0" style="color: #5D2B4C;">Event Booking Form</h4>
                </div>
                <div class="card-body">
                    <form id="booking-form" action="{{ route('bookings.store') }}" method="POST">
                        @csrf

                        <!-- Event Details -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Event Details</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="event_type" class="form-label fw-bold">Event Type *</label>
                                    <select class="form-select" id="event_type" name="event_type" required>
                                        <option value="">Select event type</option>
                                        <option value="wedding">Wedding</option>
                                        <option value="birthday">Birthday</option>
                                        <option value="anniversary">Anniversary</option>
                                        <option value="funeral">Funeral</option>
                                        <option value="graduation">Graduation</option>
                                        <option value="corporate">Corporate Event</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="event_date" class="form-label fw-bold">Event Date *</label>
                                    <input type="date" class="form-control" id="event_date" name="event_date" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="event_time" class="form-label fw-bold">Event Time *</label>
                                    <select class="form-select" id="event_time" name="event_time" required>
                                        <option value="">Select time</option>
                                        <option value="09:00">9:00 AM</option>
                                        <option value="10:00">10:00 AM</option>
                                        <option value="11:00">11:00 AM</option>
                                        <option value="12:00">12:00 PM</option>
                                        <option value="13:00">1:00 PM</option>
                                        <option value="14:00">2:00 PM</option>
                                        <option value="15:00">3:00 PM</option>
                                        <option value="16:00">4:00 PM</option>
                                        <option value="17:00">5:00 PM</option>
                                        <option value="18:00">6:00 PM</option>
                                        <option value="19:00">7:00 PM</option>
                                        <option value="20:00">8:00 PM</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guest_count" class="form-label fw-bold">Expected Guest Count *</label>
                                    <input type="number" class="form-control" id="guest_count" name="guest_count" min="1" placeholder="e.g., 50" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="event_description" class="form-label fw-bold">Event Description</label>
                                <textarea class="form-control" id="event_description" name="event_description" rows="3" placeholder="Tell us more about your event..."></textarea>
                            </div>
                        </div>

                        <!-- Floral Requirements -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Floral Requirements</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="flower_preferences" class="form-label fw-bold">Flower Preferences</label>
                                    <select class="form-select" id="flower_preferences" name="flower_preferences">
                                        <option value="">Select preferences</option>
                                        <option value="roses">Roses</option>
                                        <option value="tulips">Tulips</option>
                                        <option value="lilies">Lilies</option>
                                        <option value="sunflowers">Sunflowers</option>
                                        <option value="orchids">Orchids</option>
                                        <option value="mixed">Mixed Flowers</option>
                                        <option value="seasonal">Seasonal Flowers</option>
                                        <option value="no_preference">No Preference</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="color_scheme" class="form-label fw-bold">Color Scheme</label>
                                    <select class="form-select" id="color_scheme" name="color_scheme">
                                        <option value="">Select colors</option>
                                        <option value="red">Red</option>
                                        <option value="pink">Pink</option>
                                        <option value="white">White</option>
                                        <option value="yellow">Yellow</option>
                                        <option value="purple">Purple</option>
                                        <option value="orange">Orange</option>
                                        <option value="mixed">Mixed Colors</option>
                                        <option value="no_preference">No Preference</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="arrangement_type" class="form-label fw-bold">Arrangement Type</label>
                                    <select class="form-select" id="arrangement_type" name="arrangement_type">
                                        <option value="">Select type</option>
                                        <option value="bouquet">Bouquet</option>
                                        <option value="centerpiece">Centerpiece</option>
                                        <option value="arch">Arch/Backdrop</option>
                                        <option value="aisle">Aisle Decorations</option>
                                        <option value="table">Table Arrangements</option>
                                        <option value="wreath">Wreath</option>
                                        <option value="mixed">Mixed Arrangements</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="budget_range" class="form-label fw-bold">Budget Range</label>
                                    <select class="form-select" id="budget_range" name="budget_range">
                                        <option value="">Select budget</option>
                                        <option value="1000-3000">₱1,000 - ₱3,000</option>
                                        <option value="3000-5000">₱3,000 - ₱5,000</option>
                                        <option value="5000-10000">₱5,000 - ₱10,000</option>
                                        <option value="10000-20000">₱10,000 - ₱20,000</option>
                                        <option value="20000+">₱20,000+</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Occasion Category -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Occasion Category *</h5>
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-bold">Select Occasion</label>
                                <select class="form-select" id="category_id" name="category_id" required>
                                    <option value="">Choose an occasion</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Additional Services -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Additional Services</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="setup_delivery" name="additional_services[]" value="setup_delivery">
                                        <label class="form-check-label" for="setup_delivery">
                                            <i class="fas fa-truck me-2"></i>Setup & Delivery
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="maintenance" name="additional_services[]" value="maintenance">
                                        <label class="form-check-label" for="maintenance">
                                            <i class="fas fa-tint me-2"></i>Flower Maintenance
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="consultation" name="additional_services[]" value="consultation">
                                        <label class="form-check-label" for="consultation">
                                            <i class="fas fa-comments me-2"></i>Design Consultation
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="custom_design" name="additional_services[]" value="custom_design">
                                        <label class="form-check-label" for="custom_design">
                                            <i class="fas fa-palette me-2"></i>Custom Design
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Venue & Contact Information -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Contact Information</h5>
                            <div class="mb-3">
                                <label for="venue_address" class="form-label fw-bold">Venue Address *</label>
                                <select class="form-select" id="venue_address" name="venue_address" required>
                                    <option value="">Select city/municipality</option>
                                    <option value="Bacoor City">Bacoor City</option>
                                    <option value="Cavite City">Cavite City</option>
                                    <option value="Dasmariñas City">Dasmariñas City</option>
                                    <option value="General Trias City">General Trias City</option>
                                    <option value="Imus City">Imus City</option>
                                    <option value="Tagaytay City">Tagaytay City</option>
                                    <option value="Trece Martires City">Trece Martires City</option>
                                    <option value="Alfonso">Alfonso</option>
                                    <option value="Amadeo">Amadeo</option>
                                    <option value="Carmona">Carmona</option>
                                    <option value="Gen. Emilio Aguinaldo">Gen. Emilio Aguinaldo</option>
                                    <option value="Gen. Mariano Alvarez">Gen. Mariano Alvarez</option>
                                    <option value="Indang">Indang</option>
                                    <option value="Kawit">Kawit</option>
                                    <option value="Magallanes">Magallanes</option>
                                    <option value="Maragondon">Maragondon</option>
                                    <option value="Mendez-Nuñez">Mendez-Nuñez</option>
                                    <option value="Naic">Naic</option>
                                    <option value="Noveleta">Noveleta</option>
                                    <option value="Rosario">Rosario</option>
                                    <option value="Silang">Silang</option>
                                    <option value="Tanza">Tanza</option>
                                    <option value="Ternate">Ternate</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_person" class="form-label fw-bold">Contact Person *</label>
                                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ auth()->user()->name ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="contact_phone" class="form-label fw-bold">Contact Phone *</label>
                                    <input type="tel" class="form-control" id="contact_phone" name="contact_phone" value="{{ auth()->user()->phone ?? '' }}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="contact_email" class="form-label fw-bold">Contact Email *</label>
                                    <input type="email" class="form-control" id="contact_email" name="contact_email" value="{{ auth()->user()->email ?? '' }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="postal_id" class="form-label fw-bold">Postal ID *</label>
                                    <input type="text" class="form-control" id="postal_id" name="postal_id" maxlength="20" required placeholder="Enter Postal ID">
                                </div>
                            </div>
                        </div>

                        <!-- Special Requests -->
                        <div class="mb-4">
                            <h5 class="fw-bold mb-3" style="color: #5D2B4C;">Special Requests</h5>
                            <div class="mb-3">
                                <label for="special_requests" class="form-label">Additional Requirements</label>
                                <textarea class="form-control" id="special_requirements" name="special_requirements" rows="3" placeholder="Any special requests or specific requirements for your event?"></textarea>
                            </div>
                        </div>

                        <!-- Terms and Conditions -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="terms" name="terms" required>
                                <label class="form-check-label" for="terms">
                                    I agree to the <a href="#" style="color: #5D2B4C;">Terms and Conditions</a> and <a href="#" style="color: #5D2B4C;">Privacy Policy</a> *
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100" style="background-color: #5D2B4C; border-color: #5D2B4C;">
                            <i class="fas fa-calendar-check me-2"></i>Submit Booking Request
                        </button>
                    </form>
                </div>
            </div>

            <!-- Booking Information -->
            <div class="card border-0 shadow-lg mt-4" style="border-radius: 20px;">
                <div class="card-header bg-transparent border-0">
                    <h6 class="fw-bold mb-0" style="color: #5D2B4C;">What Happens Next?</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                1
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">Submit Request</h6>
                            <p class="text-muted small">Fill out the form above with your event details</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                2
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">Consultation</h6>
                            <p class="text-muted small">We'll contact you within 24 hours to discuss your needs</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                3
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">Design & Quote</h6>
                            <p class="text-muted small">Receive a custom design proposal and detailed quote</p>
                        </div>
                        <div class="col-md-3 text-center">
                            <div class="step-number bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" style="width: 40px; height: 40px;">
                                4
                            </div>
                            <h6 class="fw-bold" style="color: #5D2B4C;">Confirmation</h6>
                            <p class="text-muted small">Confirm your booking and we'll start creating your arrangements</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventDateInput = document.getElementById('event_date');
    const eventTypeSelect = document.getElementById('event_type');
    const flowerPreferencesSelect = document.getElementById('flower_preferences');
    const colorSchemeSelect = document.getElementById('color_scheme');

    // Set minimum date to tomorrow
    const tomorrow = new Date();
    tomorrow.setDate(tomorrow.getDate() + 1);
    eventDateInput.min = tomorrow.toISOString().split('T')[0];

    // Block Sundays
    eventDateInput.addEventListener('change', function() {
        const selectedDate = new Date(this.value);
        const dayOfWeek = selectedDate.getDay();

        if (dayOfWeek === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'Unavailable Day',
                text: 'Sorry, we do not work on Sundays. Please select another date.',
            }).then(() => {
                eventDateInput.value = '';
            });
        }
    });

    // Auto-fill preferences based on event type
    eventTypeSelect.addEventListener('change', function() {
        const eventType = this.value;

        // Reset selections
        flowerPreferencesSelect.value = '';
        colorSchemeSelect.value = '';

        // Suggest preferences based on event type
        switch(eventType) {
            case 'wedding':
                flowerPreferencesSelect.value = 'roses';
                colorSchemeSelect.value = 'white';
                break;
            case 'birthday':
                flowerPreferencesSelect.value = 'mixed';
                colorSchemeSelect.value = 'mixed';
                break;
            case 'funeral':
                flowerPreferencesSelect.value = 'lilies';
                colorSchemeSelect.value = 'white';
                break;
            case 'anniversary':
                flowerPreferencesSelect.value = 'roses';
                colorSchemeSelect.value = 'red';
                break;
        }
    });

    // Form validation
    const bookingForm = document.getElementById('booking-form');
    bookingForm.addEventListener('submit', function(e) {
        if (!document.getElementById('terms').checked) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Terms Required',
                text: 'Please agree to the Terms and Conditions before submitting.',
            });
            return;
        }

        const eventDate = new Date(eventDateInput.value);
        const today = new Date();
        // Set both dates to midnight for accurate day difference
        eventDate.setHours(0,0,0,0);
        today.setHours(0,0,0,0);
        const daysDifference = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));

        if (daysDifference < 7) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Short Notice Event',
                text: 'Your event is less than 7 days away. We may not be able to accommodate all requests. Do you want to continue?',
                showCancelButton: true,
                confirmButtonText: 'Yes, continue',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    bookingForm.submit();
                }
            });
        }
    });
});
</script>
@endsection
