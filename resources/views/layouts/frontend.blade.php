<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title', 'IntelliCareer — AI-Based Adaptive Learning & Career Planning Platform') · {{ config('app.name', 'IntelliCareer') }}</title>
  <meta name="description" content="IntelliCareer — AI-Based Adaptive Learning & Career Planning Platform. Upload your CV, find matching jobs in real time, identify skill gaps, and get curated YouTube courses to close them.">
  <meta name="keywords" content="career, AI, recommendation, jobs, courses, skill gap, IntelliCareer, IUB, FYP">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- Theme bootstrap (must run before paint to prevent flash) -->
  <script>
    (function () {
      try {
        var stored = localStorage.getItem('caria-theme') || 'auto';
        var resolved = stored === 'auto'
          ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
          : stored;
        document.documentElement.setAttribute('data-bs-theme', resolved);
      } catch (e) {
        document.documentElement.setAttribute('data-bs-theme', 'dark');
      }
    })();
  </script>

  <!-- Favicons -->
  <link href="{{ asset('frontend/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('frontend/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts: Space Grotesk (headings) + Inter (body) + JetBrains Mono (accents) -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
  <link href="{{ asset('frontend/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

  <!-- IntelliCareer Brand CSS -->
  <link href="{{ asset('frontend/css/caria.css') }}" rel="stylesheet">

  @stack('page-css')
</head>

<body class="caria-page {{ $bodyClass ?? 'home-page' }}">

  <!-- Scroll progress bar -->
  <div class="ca-scroll-progress" aria-hidden="true"></div>

  @include('components.frontend-navbar')

  <main>
    @yield('content')
  </main>

  @include('components.frontend-footer')

  <!-- Vendor JS Files -->
  <script src="{{ asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('frontend/vendor/aos/aos.js') }}"></script>
  <script src="{{ asset('frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
  <script src="{{ asset('frontend/vendor/purecounter/purecounter_vanilla.js') }}"></script>
  <script src="{{ asset('frontend/vendor/swiper/swiper-bundle.min.js') }}"></script>

  <!-- AOS init -->
  <script>
    if (window.AOS) {
      AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 60 });
    }
  </script>

  <!-- IntelliCareer brand JS -->
  <script src="{{ asset('frontend/js/caria.js') }}"></script>

  @stack('page-js')

  @include('components.toast-notifications')

</body>

</html>
