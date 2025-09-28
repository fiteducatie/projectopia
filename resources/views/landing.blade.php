<!doctype html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projectopia – Slim projectbeheer voor teams</title>
        <meta name="description" content="Projectopia: Organiseer je projecten met persona's, user stories en bestanden. Perfect voor teams die gestructureerd willen werken.">
        <link rel="icon" href="{{ Vite::asset('resources/images/logo.png') }}">
        <script src="https://cdn.tailwindcss.com"></script>
        <style>
            :root { --pri:#0ea5e9; --pri-600:#0284c7; --acc:#22c55e }
            .bg-hero { background: radial-gradient(900px 480px at 10% -10%, rgba(14,165,233,.18), transparent 60%),
                                 radial-gradient(700px 420px at 110% 0%, rgba(34,197,94,.14), transparent 60%),
                                 linear-gradient(180deg, #ffffff 0%, #f8fafc 100%); }
        </style>
    </head>
    <body class="min-h-screen bg-hero text-slate-900">
        <header class="max-w-7xl mx-auto px-6 py-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 font-semibold">
                <div class="logo">
                    <img src="{{ Vite::asset('resources/images/logo.png') }}" alt="Projectopia" class="h-10 w-10">
                </div>
                <span>Projectopia</span>
            </a>
            <nav class="hidden md:flex items-center gap-6 text-sm">
                <a href="#features" class="hover:text-sky-600">Functies</a>
                <a href="#personas" class="hover:text-sky-600">Persona's</a>
                <a href="#hoe" class="hover:text-sky-600">Hoe werkt het</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="/admin" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">Inloggen</a>
                <a href="/kies-project" class="px-4 py-2 rounded-lg bg-sky-500 text-white hover:bg-sky-600">Kies project</a>
            </div>
        </header>

        <main>
            <!-- Hero -->
            <section class="max-w-7xl mx-auto px-6 pt-10 pb-16 md:pt-16 md:pb-24 grid md:grid-cols-2 gap-10 items-start">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                        Simuleer <span class="text-sky-600">projecten</span> met <span class="text-emerald-600">virtuele belanghebbenden</span> en chat in realtime.
                    </h1>
                    <p class="mt-4 text-slate-600 text-lg">
                        Projectopia helpt teams om te communiceren met duidelijke projectcontext, stakeholder persona's en uitgewerkte user stories.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="/admin" class="px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start nu</a>
                        <a href="#features" class="px-5 py-3 rounded-lg border border-slate-200 hover:bg-slate-50">Bekijk functies</a>
                    </div>
                    <div class="mt-6 flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span> Project context</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Stakeholder persona's</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> User stories</span>
                    </div>
                </div>
                <!-- Chat interface image -->
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-sky-100 to-emerald-100 blur-2xl rounded-xl"></div>
                    <div class="relative max-w-sm mx-auto p-4 bg-white rounded-xl shadow-lg border-2 border-slate-200">
                        <div class="bg-slate-50 rounded-lg p-2 border border-slate-200">
                            <img src="{{ asset('images/chat.jpg') }}" alt="Chat interface met virtuele belanghebbenden" class="w-full h-auto rounded-lg shadow-sm">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features -->
            <section id="features" class="bg-white border-y border-slate-200">
                <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center mb-3">💬</div>
                        <h3 class="font-semibold text-lg mb-1">Realtime Chat</h3>
                        <p class="text-slate-600">Chat in realtime met virtuele stakeholders die reageren zoals echte personen.</p>
                    </div>
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">🎭</div>
                        <h3 class="font-semibold text-lg mb-1">Virtuele Persona's</h3>
                        <p class="text-slate-600">Persona's met eigen doelen, eigenschappen en communicatiestijl die realistisch reageren.</p>
                    </div>
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mb-3">🎯</div>
                        <h3 class="font-semibold text-lg mb-1">Project Simulatie</h3>
                        <p class="text-slate-600">Oefen projectgesprekken in een veilige omgeving zonder echte consequenties.</p>
                    </div>
                </div>
            </section>

            <!-- Persona's -->
            <section id="personas" class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="text-2xl font-bold">Stakeholder Persona's</h2>
                    <p class="mt-3 text-slate-600">Maak gedetailleerde persona's van je stakeholders met doelen, eigenschappen en communicatiestijl. Dit helpt je team om beter te begrijpen voor wie je bouwt.</p>
                    <ul class="mt-4 space-y-2 text-slate-700">
                        <li>• Definiëer doelen en eigenschappen per persona</li>
                        <li>• Koppel persona's aan user stories</li>
                        <li>• Organiseer bestanden per relevante stakeholder</li>
                    </ul>
                    <a href="/admin" class="inline-block mt-6 px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Bekijk persona's</a>
                </div>
                <div class="relative">

                    </div>
                </div>
            </section>

            <!-- Hoe werkt het -->
            <section id="hoe" class="px-6 py-16 bg-sky-50 border-t border-slate-200">
                <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-6">
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 1</div>
                        <div class="font-semibold">Project aanmaken</div>
                        <div class="text-slate-600 mt-1 text-sm">Context, doelstellingen en randvoorwaarden</div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 2</div>
                        <div class="font-semibold">Persona's maken</div>
                        <div class="text-slate-600 mt-1 text-sm">Stakeholders met doelen en eigenschappen</div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 3</div>
                        <div class="font-semibold">User Stories</div>
                        <div class="text-slate-600 mt-1 text-sm">Verhalen met acceptatiecriteria</div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 4</div>
                        <div class="font-semibold">Bestanden uploaden</div>
                        <div class="text-slate-600 mt-1 text-sm">Documenten en media per persona</div>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto mt-8 text-center">
                    <a href="/admin" class="px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start je eerste project</a>
                </div>
            </section>
        </main>

        <footer class="max-w-7xl mx-auto px-6 py-10 text-sm text-slate-500 flex items-center justify-between">
            <div>© <script>document.write(new Date().getFullYear())</script> Projectopia</div>
            <div class="space-x-4">
                <a href="/admin" class="hover:text-sky-600">Inloggen</a>
                <a href="#features" class="hover:text-sky-600">Functies</a>
                <a href="#personas" class="hover:text-sky-600">Persona's</a>
            </div>
        </footer>
    </body>
    </html>


