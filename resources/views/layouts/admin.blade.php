<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Google Fonts - Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- Custom Admin Styles -->
    <style>
        :root {
            --primary-color: #3b82f6;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --sidebar-width: 280px;
            --header-height: 70px;
        }

        /* Apply Poppins font to body and general elements */
        body, p, h1, h2, h3, h4, h5, h6, span, div, a, button, input, textarea, select, label {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Bootstrap Icons styling */
        .bi {
            display: inline-block;
            font-size: inherit;
            line-height: 1;
            vertical-align: middle;
        }

        /* Icon spacing in navigation */
        .sidebar .nav-link .bi {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Icon spacing in buttons and other elements */
        .btn .bi, .nav-link .bi, .dropdown-item .bi {
            margin-right: 6px;
        }

        /* Ensure form elements use Poppins */
        input, textarea, select, button, .btn, .form-control, .form-select {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure DataTables use Poppins */
        .dataTables_wrapper, .dataTables_filter, .dataTables_length, .dataTables_info, .dataTables_paginate {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure modals and dropdowns use Poppins */
        .modal, .dropdown-menu, .alert, .badge, .breadcrumb {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure all Bootstrap components use Poppins */
        .navbar, .nav, .nav-link, .nav-item, .card, .card-header, .card-body, .card-footer,
        .table, .table th, .table td, .list-group, .list-group-item,
        .pagination, .page-link, .page-item, .btn-group, .input-group,
        .form-label, .form-text, .invalid-feedback, .valid-feedback {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure any JavaScript-generated content uses Poppins */
        [data-bs-toggle], [data-bs-target], [data-bs-dismiss] {
            font-family: 'Poppins', sans-serif !important;
        }

        /* Ensure pseudo-elements use appropriate fonts */
        .btn::before, .btn::after, .nav-link::before, .nav-link::after {
            font-family: inherit;
        }

        /* Fixed top navigation */
        .top-navbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            z-index: 1030;
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 15px 0;
            transition: left 0.3s ease;
        }

        .sidebar.collapsed ~ .main-content .top-navbar {
            left: 0;
        }

        /* Responsive adjustments for fixed navbar */
        @media (max-width: 768px) {
            .top-navbar {
                left: 0 !important;
            }

            .main-content {
                padding-top: 70px;
            }
        }

        body {
            background: #f8fafc;
            font-size: 14px;
            color: var(--dark-color);
        }

        .sidebar {
            background: var(--primary-color);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border-radius: 0 20px 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar.collapsed {
            transform: translateX(-100%);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1050;
            border-radius: 10px;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: left 0.3s ease;
        }

        .sidebar-toggle i {
            font-size: 24px;
        }

        .sidebar.collapsed ~ .sidebar-toggle {
            left: 20px;
        }

        .sidebar:not(.collapsed) ~ .sidebar-toggle {
            left: calc(var(--sidebar-width) + 20px);
        }

        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8) !important;
            padding: 12px 20px;
            margin: 5px 15px;
            border-radius: 10px;
            font-weight: 500;
            border: none;
        }

        .sidebar .nav-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white !important;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Enhanced Font Awesome icon styling */
        .fas, .far, .fab, .fa, i[class*="fa-"] {
            font-family: "Font Awesome 6 Free", "Font Awesome 6 Pro", "FontAwesome" !important;
            font-weight: 900;
            display: inline-block;
            font-style: normal;
            font-variant: normal;
            text-rendering: auto;
            line-height: 1;
            margin-right: 0;
        }

        /* Ensure icons in buttons and navigation work properly */
        .btn i, .nav-link i, .dropdown-item i,
        .btn .bi, .nav-link .bi, .dropdown-item .bi {
            margin-right: 6px;
            font-size: 14px;
        }

        /* Icon spacing for different contexts */
        .sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        /* Consistent button styling for actions */
        .btn-action {
            border-radius: 8px;
            font-size: 13px;
            padding: 8px 12px;
            font-weight: 600;
            border: none;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            min-width: 36px;
            height: 36px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.3px;
        }

        .btn-action:hover {
            /* No animation */
        }

        .btn-action:active {
            /* No animation */
        }

        .btn-action-sm {
            padding: 6px 10px;
            font-size: 12px;
            min-width: 32px;
            height: 32px;
        }

        .btn-action-lg {
            padding: 12px 20px;
            font-size: 14px;
            min-width: 48px;
            height: 48px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        /* Enhanced button colors with better contrast */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        }

        /* No hover effects */

        .btn-success {
            background: var(--success-color);
            border-color: var(--success-color);
            color: white;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.2);
        }

        /* No hover effects */

        .btn-warning {
            background: var(--warning-color);
            border-color: var(--warning-color);
            color: white;
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.2);
        }

        /* No hover effects */

        .btn-danger {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.2);
        }

        /* No hover effects */

        .btn-info {
            background: var(--info-color);
            border-color: var(--info-color);
            color: white;
            box-shadow: 0 2px 4px rgba(6, 182, 212, 0.2);
        }

        /* No hover effects */

        .btn-secondary {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
            box-shadow: 0 2px 4px rgba(100, 116, 139, 0.2);
        }

        /* No hover effects */

        /* Outline button variants for better contrast */
        .btn-outline-primary {
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            background: transparent;
            font-weight: 600;
        }

        /* No hover effects */

        .btn-outline-warning {
            color: var(--warning-color);
            border: 2px solid var(--warning-color);
            background: transparent;
            font-weight: 600;
        }

        /* No hover effects */

        /* Enhanced form buttons */
        .btn {
            border-radius: 10px;
            font-weight: 600;
            padding: 12px 24px;
            border: none;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .btn:hover {
        }

        .btn:active {

        }

        .btn-sm {
            padding: 8px 16px;
            font-size: 13px;
            border-radius: 8px;
        }

        .btn-lg {
            padding: 16px 32px;
            font-size: 16px;
            border-radius: 12px;
            font-weight: 700;
        }

        /* Status toggle buttons with better visibility */
        .btn-status-toggle {
            min-width: 80px;
            font-size: 12px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Quick action buttons in dashboard */
        .quick-action-btn {
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            color: var(--dark-color);
            font-weight: 600;
            padding: 16px 20px;
            border-radius: 12px;
            text-decoration: none;
            display: block;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        /* No hover effects */

        /* Enhanced DataTables buttons */
        .dt-button {
            background: var(--primary-color) !important;
            border: none !important;
            border-radius: 6px !important;
            color: white !important;
            padding: 8px 15px !important;
            margin-right: 5px !important;
            margin-bottom: 5px !important;
            font-weight: 500 !important;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
        }

        /* No hover effects */

        /* Enhanced search and filter buttons */
        .search-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 16px;
            font-weight: 600;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.2);
        }

        /* No hover effects */

        /* Enhanced dropdown buttons */
        .dropdown-toggle::after {
            margin-left: 8px;
            font-weight: 600;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 12px 18px;
            font-weight: 500;
            margin: 2px 8px;
        }

        /* No hover effects */

        /* Enhanced modal buttons */
        .modal-footer .btn {
            margin-left: 8px;
            min-width: 100px;
        }

        /* Enhanced badge styling for better readability */
        .badge {
            font-weight: 600;
            padding: 8px 12px;
            border-radius: 20px;
            font-size: 11px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        /* Enhanced table action buttons spacing */
        .table-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .table-actions .btn-action {
            margin: 2px;
        }

        /* Ensure button action icons display properly */
        .btn-action .bi {
            font-size: 14px;
            margin-right: 0;
        }

        .btn-action:not(:has(.bi)) .bi {
            margin-right: 0;
        }

        /* Enhanced form styling */
        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            /* No animation */
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 12px 15px;
        }

        .form-label {
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 8px;
        }

        .input-group-text {
            background: #f8fafc;
            border-color: #e9ecef;
            color: var(--dark-color);
            font-weight: 600;
        }

        /* Enhanced card styling */
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        /* Card hover animation removed */

        .card-header {
            background: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            padding: 20px 25px;
            font-weight: 600;
            color: var(--dark-color);
            font-size: 16px;
        }

        /* Enhanced table styling */
        .table {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 0;
        }

        .table thead th {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
            font-weight: 600;
            color: var(--dark-color);
            padding: 15px;
            font-size: 13px;
            text-transform: none;
            letter-spacing: normal;
        }

        .table tbody td {
            padding: 15px;
            border-color: #f1f3f4;
            vertical-align: middle;
            font-size: 14px;
        }

        /* No hover effects */

        /* Enhanced search and filter section */
        .search-filters {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }

        .search-filters .form-control,
        .search-filters .form-select {
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-weight: 500;
        }

        .search-filters .form-control:focus,
        .search-filters .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.25);
            /* No animation */
        }

        /* Enhanced page header */
        .page-header {
            background: #ffffff;
            border-radius: 15px;
            padding: 25px 30px;
            margin-bottom: 25px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        /* Full width content sections */
        .content-section {
            margin-bottom: 30px;
        }

        .content-section:last-child {
            margin-bottom: 0;
        }

        /* Responsive padding adjustments */
        @media (min-width: 1200px) {
            .px-4 {
                padding-left: 2rem !important;
                padding-right: 2rem !important;
            }
        }

        @media (min-width: 1400px) {
            .px-4 {
                padding-left: 3rem !important;
                padding-right: 3rem !important;
            }
        }

        .page-header h1 {
            color: var(--dark-color);
            font-weight: 800;
            margin: 0;
            font-size: 28px;
        }

        /* Enhanced empty state */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        .empty-state h5 {
            font-weight: 600;
            margin-bottom: 10px;
            color: #475569;
        }

        .empty-state p {
            margin-bottom: 25px;
            font-size: 16px;
        }

        /* Enhanced responsive design */
        @media (max-width: 768px) {
            .btn-action-lg {
                padding: 10px 16px;
                font-size: 13px;
                min-width: 40px;
                height: 40px;
            }

            .table-actions {
                flex-direction: column;
                gap: 4px;
            }

            .table-actions .btn-action {
                width: 100%;
                justify-content: center;
            }

            .page-header {
                padding: 20px;
                text-align: center;
            }

            .page-header .d-flex {
                flex-direction: column;
                gap: 15px;
            }
        }

        /* Enhanced loading states */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;

        }



        /* Enhanced button focus states */
        .btn-action:focus,
        .btn:focus,
        .search-btn:focus {
            outline: none;
            box-shadow: 0 0 0 0.3rem rgba(59, 130, 246, 0.25);
        }

        /* Enhanced button active states */
        .btn-action:active,
        .btn:active,
        .search-btn:active {
            /* No animation */
        }

        /* Button action specific styling for Bootstrap Icons */
        .btn-action.btn-primary .bi,
        .btn-action.btn-success .bi,
        .btn-action.btn-warning .bi,
        .btn-action.btn-danger .bi,
        .btn-action.btn-info .bi {
            color: inherit;
        }

        /* Ensure button text and icons are properly aligned */
        .btn-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* Enhanced button disabled states */
        .btn-action:disabled,
        .btn:disabled,
        .search-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            box-shadow: none !important;
        }

        /* Enhanced button group styling */
        .btn-group .btn-action {
            border-radius: 0;
        }

        .btn-group .btn-action:first-child {
            border-top-left-radius: 8px;
            border-bottom-left-radius: 8px;
        }

        .btn-group .btn-action:last-child {
            border-top-right-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        /* Enhanced icon spacing in buttons */
        .btn-action i,
        .btn i,
        .search-btn i {
            margin-right: 6px;
            font-size: 14px;
        }

        .btn-action i:only-child,
        .btn i:only-child,
        .search-btn i:only-child {
            margin-right: 0;
        }

        /* Enhanced button text */
        .btn-action span,
        .btn span,
        .search-btn span {
            font-weight: inherit;
            letter-spacing: inherit;
        }

        /* Enhanced button shadows for depth */
        .btn-action,
        .btn,
        .search-btn {
            position: relative;
            overflow: hidden;
        }

        /* Ensure all buttons display icons properly */
        .btn .bi, .btn-action .bi, .search-btn .bi {
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }

        /* Button icon spacing */
        .btn:not(.btn-icon-only) .bi:first-child {
            margin-right: 6px;
        }

        .btn:not(.btn-icon-only) .bi:last-child {
            margin-left: 6px;
        }

        /* Icon-only buttons */
        .btn-icon-only .bi {
            margin: 0;
            font-size: 16px;
        }

        /* Enhanced status toggle buttons */
        .btn-status-toggle {
            position: relative;
            overflow: hidden;
        }

        /* Enhanced quick action buttons */
        .quick-action-btn {
            position: relative;
            overflow: hidden;
        }

        /* Essential layout styles */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 0;
            width: calc(100vw - var(--sidebar-width));
            padding-top: 80px; /* Add top padding for fixed navbar */
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        .sidebar.collapsed ~ .main-content {
            margin-left: 0;
            width: 100vw;
        }

        /*
        .top-navbar {
            background: white;
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            border-radius: 15px;
            margin-bottom: 20px;
            padding: 15px 25px;
            height: var(--header-height);
            display: flex;
            align-items: center;
            justify-content: space-between;
        } */

        .sidebar-brand {
            padding: 20px;
            text-align: center;
            color: white;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 20px;
        }

        .sidebar-brand h4 {
            font-weight: 700;
            margin: 0;
        }

        .page-title {
            font-weight: 700;
            color: var(--dark-color);
            margin-bottom: 5px;
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
            font-size: 14px;
        }

        .breadcrumb-item {
            color: #64748b;
        }

        .breadcrumb-item a {
            color: var(--primary-color);
            text-decoration: none;
        }

        /* No hover effects */

        .breadcrumb-item.active {
            color: #64748b;
            font-weight: 500;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: "›";
            color: #cbd5e1;
            margin: 0 8px;
        }

        /* Stats cards */
        .stats-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            border: 1px solid #e2e8f0;
        }

        /* Stats card hover animation removed */

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            margin-right: 15px;
        }

        /* Dropdown and modal styles */
        .dropdown-menu {
            border: none;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            padding: 10px;
            display: none;
            position: absolute;
            top: 100%;
            z-index: 1000;
            min-width: 200px;
            background: white;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        /* Ensure dropdown positioning */
        .dropdown {
            position: relative;
        }

        .dropdown-menu-end {
            right: 0;
            left: auto;
        }

        /* Dropdown items styling */
        .dropdown-item {
            display: block;
            width: 100%;
            padding: 8px 16px;
            clear: both;
            font-weight: 400;
            color: #212529;
            text-align: inherit;
            text-decoration: none;
            white-space: nowrap;
            background-color: transparent;
            border: 0;
            border-radius: 6px;
            margin: 2px 0;
            cursor: pointer;
        }

        /* No hover effects */

        /* Special styling for logout button */
        .dropdown-item.text-danger {
            color: #dc3545 !important;
        }

        /* No hover effects */

        /* Ensure button text is visible in dropdown */
        .dropdown-item button {
            background: none;
            border: none;
            width: 100%;
            text-align: left;
            color: inherit;
            font-weight: inherit;
            padding: 0;
            margin: 0;
        }

        .dropdown-divider {
            height: 0;
            margin: 0.5rem 0;
            overflow: hidden;
            border-top: 1px solid #dee2e6;
        }

        .alert {
            border: none;
            border-radius: 12px;
            padding: 15px 20px;
            font-weight: 500;
        }

        .modal-content {
            border: none;
            border-radius: 15px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        }

        .modal-header {
            border-bottom: 1px solid #f1f3f4;
            padding: 20px 25px;
        }

        .modal-body {
            padding: 25px;
        }

        .modal-footer {
            border-top: 1px solid #f1f3f4;
            padding: 20px 25px;
        }

        /* DataTables Styling */
        .dataTables_wrapper .dataTables_filter {
            margin-bottom: 20px;
        }

        .dataTables_wrapper .dataTables_filter input {
            border-radius: 10px;
            border: 2px solid #e9ecef;
            padding: 10px 15px;
            margin-left: 10px;
        }

        .dataTables_wrapper .dataTables_length select {
            border-radius: 8px;
            border: 2px solid #e9ecef;
            padding: 5px 10px;
        }

        .dt-buttons {
            margin-bottom: 20px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .sidebar-toggle {
                left: 20px !important;
            }

            .main-content {
                margin-left: 0;
                width: 100vw;
            }

            .top-navbar {
                left: 0 !important;
            }

            .btn-action-lg {
                padding: 10px 16px;
                font-size: 13px;
                min-width: 40px;
                height: 40px;
            }

            .table-actions {
                flex-direction: column;
                gap: 4px;
            }

            .table-actions .btn-action {
                width: 100%;
                justify-content: center;
            }

            .page-header {
                padding: 20px;
                text-align: center;
            }

            .page-header .d-flex {
                flex-direction: column;
                gap: 15px;
            }

            .px-4 {
                padding-left: 1rem !important;
                padding-right: 1rem !important;
            }
        }

        /* Remove Scrollbar Completely */
        ::-webkit-scrollbar {
            display: none;
        }

        /* Disable all animations and transitions globally except modals and sidebar */
        *:not(.modal):not(.modal *):not(.modal-backdrop):not(.sidebar):not(.sidebar-toggle):not(.main-content):not(.top-navbar), 
        *:not(.modal):not(.modal *):not(.modal-backdrop):not(.sidebar):not(.sidebar-toggle):not(.main-content):not(.top-navbar)::before, 
        *:not(.modal):not(.modal *):not(.modal-backdrop):not(.sidebar):not(.sidebar-toggle):not(.main-content):not(.top-navbar)::after {
            animation-duration: 0s !important;
            animation-delay: 0s !important;
            transition-duration: 0s !important;
            transition-delay: 0s !important;
        }

        /* Completely disable all hover effects except modals */
        *:not(.modal):not(.modal *):hover {
            transition: none !important;
            animation: none !important;
        }

        /* Disable Bootstrap animations except modals */
        .fade:not(.modal):not(.modal-backdrop) {
            opacity: 1 !important;
        }
        
        .fade:not(.show):not(.modal):not(.modal-backdrop) {
            opacity: 0 !important;
        }
        
        .collapsing {
            height: auto !important;
        }
        
        /* Allow normal modal animations */
        .modal.fade {
            transition: opacity 0.15s linear;
        }
        
        .modal.fade .modal-dialog {
            transition: transform 0.3s ease-out;
            transform: translate(0, -50px);
        }
        
        .modal.fade.show .modal-dialog {
            transform: none;
        }
        
        /* Fix modal backdrop */
        .modal-backdrop {
            background-color: rgba(0, 0, 0, 0.5) !important;
            transition: opacity 0.15s linear !important;
        }
        
        .modal-backdrop.fade {
            opacity: 0 !important;
        }
        
        .modal-backdrop.fade.show {
            opacity: 1 !important;
        }
        
        .carousel-item {
            transform: none !important;
        }
        
        /* Disable loading spinner animation */
        .btn-loading::after {
            animation: none !important;
        }
        
        /* All hover effects disabled */

        /* Hide scrollbar for IE, Edge and Firefox */
        html, body {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Ensure no scrollbar on main content */
        .main-content {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <!-- Sidebar Toggle Button -->
    <button class="sidebar-toggle btn btn-primary" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <nav class="sidebar bg-dark text-white vh-100 p-3" id="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand mb-4">
            <h4 class="d-flex align-items-center">
                Flower Shop Admin
            </h4>
        </div>

        <!-- Navigation -->
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>

            <!-- Sales Management -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-cart"></i> Orders
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.bookings.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.bookings.index') }}">
                    <i class="bi bi-calendar-event"></i> Bookings
                </a>
            </li>

            <!-- Product Management -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box"></i> Products
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags"></i> Categories
                </a>
            </li>

            <!-- Customer Management -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.users.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i> Customers
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.reviews.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.reviews.index') }}">
                    <i class="bi bi-star"></i> Reviews
                </a>
            </li>

            <!-- Support Management -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.chats.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.chats.index') }}">
                    <i class="bi bi-chat-dots"></i> Chat Support
                </a>
            </li>

            <!-- Reports & Analytics -->
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->routeIs('admin.reports.*') ? 'active bg-primary' : '' }}"
                    href="{{ route('admin.reports.sales') }}">
                    <i class="bi bi-graph-up"></i> Reports
                </a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation (User Dropdown only) -->
        <div class="top-navbar d-flex justify-content-end align-items-center py-3 border-bottom px-4">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>{{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                    <li>
                        <a class="dropdown-item" href="{{ route('home') }}">
                            <i class="bi bi-house"></i>View Site
                        </a>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger w-100 text-start">
                                <i class="bi bi-box-arrow-right"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page Header (Title + Breadcrumb) -->
        <div class="py-3 px-4">
            <h1 class="page-title">@yield('page-title', 'Admin Dashboard')</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    @if (!request()->routeIs('admin.dashboard'))
                        <li class="breadcrumb-item active">@yield('page-title')</li>
                    @endif
                </ol>
            </nav>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
        <div class="px-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="px-4">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
        @endif

        <!-- Page Content -->
        <div class="px-4">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery (required for DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Custom Admin JS -->
    <script>
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);

        // SweetAlert2 Configuration
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            showClass: {
                popup: '',
                backdrop: ''
            },
            hideClass: {
                popup: '',
                backdrop: ''
            },
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Global SweetAlert functions
        function showSuccess(message) {
            Toast.fire({
                icon: 'success',
                title: message
            });
        }

        function showError(message) {
            Toast.fire({
                icon: 'error',
                title: message
            });
        }

        function showWarning(message) {
            Toast.fire({
                icon: 'warning',
                title: message
            });
        }

        function showInfo(message) {
            Toast.fire({
                icon: 'info',
                title: message
            });
        }

        function confirmDelete(title = 'Are you sure?', text = 'This action cannot be undone!') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            });
        }

        function confirmAction(title = 'Are you sure?', text = 'Do you want to proceed?') {
            return Swal.fire({
                title: title,
                text: text,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, proceed!',
                cancelButtonText: 'Cancel'
            });
        }

        // Ensure dropdown functionality
        document.addEventListener('DOMContentLoaded', function() {
            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            if (sidebarToggle && sidebar) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    
                    // Save state to localStorage
                    if (sidebar.classList.contains('collapsed')) {
                        localStorage.setItem('sidebarCollapsed', 'true');
                    } else {
                        localStorage.setItem('sidebarCollapsed', 'false');
                    }
                });

                // Restore sidebar state on page load
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed');
                if (sidebarCollapsed === 'true') {
                    sidebar.classList.add('collapsed');
                }
            }

            // Simple dropdown toggle functionality
            const userDropdown = document.getElementById('userDropdown');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            if (userDropdown && dropdownMenu) {
                console.log('User dropdown found:', userDropdown);
                console.log('Dropdown menu found:', dropdownMenu);

                userDropdown.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    console.log('Dropdown clicked');

                    // Toggle dropdown manually if Bootstrap fails
                    if (dropdownMenu.classList.contains('show')) {
                        dropdownMenu.classList.remove('show');
                    } else {
                        dropdownMenu.classList.add('show');
                    }
                });

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!userDropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        dropdownMenu.classList.remove('show');
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>

</html>