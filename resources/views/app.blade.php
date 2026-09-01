<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <script>
        const theme = localStorage.getItem('theme') || 'dark';
        if (theme === 'light') {
            document.documentElement.classList.remove('dark');
        } else {
            document.documentElement.classList.add('dark');
        }
    </script>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.tsx'])
    @routes
    @inertiaHead

    @if(config('services.clarity.id'))
        <script type="text/javascript">
            (function(c,l,a,r,i,t,y){
                c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
                t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
                y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
            })(window, document, "clarity", "script", "{{ config('services.clarity.id') }}");
        </script>
    @endif
</head>
<body class="font-sans antialiased bg-canvas dark:bg-canvas-dark min-h-dvh">
    <!--
      THESIS: church office desk, not a SaaS command center of identical KPI cards.
      OWN-WORLD: mint-stone canvas, SIB teal #2F8A7E, Figtree, job-grouped nav.
      STORY: staff see where they are, what needs attention, and the next action.
      FIRST VIEWPORT: Início as this week's notice board — birthdays, visitors, caixa, next culto.
      FORM: operate / secretaria SIB / restrained teal.
      FINISH: unreviewed and undocumented is unfinished; this build ends with the finish review, the verdict, DESIGN.md, and every shipping raster carrying its provenance
    -->
    @inertia
</body>
</html>
