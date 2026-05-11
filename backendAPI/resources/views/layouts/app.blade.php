<!DOCTYPE html>
<html lang="en">
<head>
    @include('includes.header')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">

<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center">
        @include('includes.preloader')
    </div>

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
        @include('includes.header_navbar')
    </nav>

    <!-- Sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        @include('includes.sidebar_container')
    </aside>

    <!-- Content -->
    <div class="content-wrapper">

        <div class="content-header">
            @include('includes.content_header')
        </div>

        <section class="content">
           {{$slot ?? ''}}
           @yield('content')
        </section>

    </div>

    <!-- Footer -->
    <footer class="main-footer">
        @include('includes.footer')
    </footer>

</div>
<x-toast />

@include('includes.script')
</body>
</html>
