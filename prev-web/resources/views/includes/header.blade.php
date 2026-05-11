<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'PV-S01 | Dashboard')</title>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

<!-- overlayScrollbars -->
<link rel="stylesheet" href="{{ asset('plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">

<!-- DataTables CSS -->
<link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

<!-- AdminLTE CSS -->
<link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('dist/css/generic.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/custom.css') }}">
<link rel="stylesheet" href="{{ asset('dist/css/styles.css') }}">
<!-- CSS Datapicker -->
<link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endif
<livewire:styles />
<!-- CSS específico de páginas -->
@yield('styles')
