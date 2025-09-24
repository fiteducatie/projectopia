<!doctype html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'Projectopia')</title>
        <meta name="description" content="Projectopia – AI-projectsupport & simulatie voor onderwijs">
        <link rel="icon" href="/favicon.ico">
        <script src="https://cdn.tailwindcss.com"></script>
        @yield('head')
    </head>
    <body class="min-h-screen bg-slate-50 text-slate-900">
        <header class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-semibold">
                <span class="inline-block h-6 w-6 rounded-md bg-sky-500"></span>
                <span>Projectopia</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="/kies-project" class="hover:text-sky-600">Kies project</a>
                <a href="/admin" class="hover:text-sky-600">Admin</a>
            </nav>

        </header>

        <main class="max-w-7xl mx-auto px-6 py-10">
            @yield('content')
        </main>

        <footer class="max-w-7xl mx-auto px-6 py-10 text-sm text-slate-500 flex items-center justify-between">
            <div>© <script>document.write(new Date().getFullYear())</script> Projectopia</div>
            <div class="space-x-4">
                <a href="/admin" class="hover:text-sky-600">Inloggen</a>
                <a href="/kies-project" class="hover:text-sky-600">Kies project</a>
            </div>
        </footer>
        @yield('scripts')
    </body>
    </html>


