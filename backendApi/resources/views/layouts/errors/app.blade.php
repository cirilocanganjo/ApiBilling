<!DOCTYPE html>
<html lang="pt" class="h-full">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'PV-S01 | Dashboard')</title>
{{-- @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
@vite(['resources/css/app.css', 'resources/js/app.js'])
@endif --}}
<script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="h-full bg-gray-50 antialiased">
    @yield('content')
</body>
</html>
