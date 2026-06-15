<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Anesidora{{ View::hasSection('title') ? ' ┃ ' . $__env->yieldContent('title') : '' }}</title>
    <link rel="icon" href="assets/icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body class="flex flex-col bg-[#1e293b] text-[#f8fafc] h-dvh w-dvw overflow-none">
    <x-header />
    <main class="h-full w-full overflow-hidden overflow-y-auto scrollbar-none">
      <div class="min-h-screen w-full p-5">
        @yield('content')
      </div>
      <x-footer />
    </main>
    @include('layout.register')
    @include('layout.login')
  </body>
</html>
