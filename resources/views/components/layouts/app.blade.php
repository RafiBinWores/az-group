<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AZ Group</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/x-icon" />

    <!-- Custom Css -->
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('assets/css/font-awesome.css') }}" />

    <!-- Google Font  -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100..700;1,100..700&family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="text-gray-700 bg-gray-50 font-montserrat">

    <div class="flex">
        <!-- Sidebar (Desktop) -->
        <x-sidebar.sidebar />

        <!-- Main Content -->
        <main class="flex-1 px-6 pt-4 bg-slate-50">
            {{-- Top-bar --}}
            <x-topbar.topbar />

            
            {{ $slot }}
        </main>
    </div>



    {{-- Custom js --}}
    @stack('scripts')

    <script src="{{ asset('assets/js/components/sidebar.js') }}"></script>
</body>

</html>
