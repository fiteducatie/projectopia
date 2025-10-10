<!doctype html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projectopia – Slimme projectsimulatie voor teams en studenten</title>
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
                <a href="#personas" class="hover:text-sky-600">Belanghebbenden</a>
                <a href="#hoe" class="hover:text-sky-600">Hoe werkt het</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="/admin" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">Inloggen</a>
                <a href="/kies-activiteit" class="px-4 py-2 rounded-lg bg-sky-500 text-white hover:bg-sky-600">Kies activiteit</a>
            </div>
        </header>

        <main>
            <!-- Hero -->
            <section class="max-w-7xl mx-auto px-6 pt-10 pb-16 md:pt-16 md:pb-24 grid md:grid-cols-2 gap-10 items-start">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                        Oefen <span class="text-sky-600">challenges</span>, simuleer <span class="text-emerald-600">projecten</span> en volg <span class="text-purple-600">trainingen</span> met virtuele assistenten.
                    </h1>
                    <p class="mt-4 text-slate-600 text-lg">
                        Projectopia helpt teams om vaardigheden te ontwikkelen door challenges te oefenen, projecten te simuleren en trainingen te volgen met virtuele assistenten die jou meenemen in de wereld van jouw vakgebied.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="/admin" class="px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start nu</a>
                        <a href="#features" class="px-5 py-3 rounded-lg border border-slate-200 hover:bg-slate-50">Bekijk functies</a>
                    </div>
                    <div class="mt-6 flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span> Challenges</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Project simulatie</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-purple-500"></span> Trainingen</span>
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
                <div class="max-w-7xl mx-auto px-6 py-16">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold mb-4">Oefen challenges en projectvaardigheden</h2>
                        <p class="text-slate-600 text-lg">Ontwikkel je vaardigheden door te oefenen met specifieke onderwerpen in realistische projectscenario's</p>
                    </div>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                            <div class="h-10 w-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-3">🎓</div>
                            <h3 class="font-semibold text-lg mb-1">Masterclasses</h3>
                            <p class="text-slate-600">Oefen specifieke onderwerpen zoals stakeholder management, requirements gathering, en projectcommunicatie.</p>
                        </div>
                        <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                            <div class="h-10 w-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center mb-3">💬</div>
                            <h3 class="font-semibold text-lg mb-1">Realtime Chat</h3>
                            <p class="text-slate-600">Chat in realtime met virtuele stakeholders die reageren zoals echte personen.</p>
                        </div>
                        <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                            <div class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">🎭</div>
                            <h3 class="font-semibold text-lg mb-1">Virtuele Belanghebbenden</h3>
                            <p class="text-slate-600">Belanghebbenden met eigen doelen, eigenschappen en communicatiestijl die realistisch reageren.</p>
                        </div>
                        <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                            <div class="h-10 w-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-3">📎</div>
                            <h3 class="font-semibold text-lg mb-1">Gerichte Informatie Uitwisseling</h3>
                            <p class="text-slate-600">Belanghebbenden hebben geheime documenten die ze alleen delen als je de juiste vragen stelt. Oefen hoe je informatie verkrijgt door goed te luisteren en door te vragen.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Masterclasses -->
            <section id="challenges" class="max-w-7xl mx-auto px-6 py-16">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold mb-4">Masterclasses voor projectvaardigheden</h2>
                    <p class="text-slate-600 text-lg">Oefen specifieke onderwerpen in realistische projectscenario's met virtuele belanghebbenden</p>
                </div>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="h-12 w-12 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center mb-4 text-xl">👥</div>
                        <h3 class="font-semibold text-lg mb-2">Stakeholder Management</h3>
                        <p class="text-slate-600 mb-4">Leer hoe je effectief communiceert met verschillende belanghebbenden, hun verwachtingen beheert en conflicten oplost.</p>
                        <ul class="text-sm text-slate-700 space-y-1">
                            <li>• Identificeer en analyseer stakeholders</li>
                            <li>• Communicatiestrategieën ontwikkelen</li>
                            <li>• Conflicten beheren en oplossen</li>
                        </ul>
                    </div>
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="h-12 w-12 rounded-lg bg-green-100 text-green-600 flex items-center justify-center mb-4 text-xl">📋</div>
                        <h3 class="font-semibold text-lg mb-2">Requirements Gathering</h3>
                        <p class="text-slate-600 mb-4">Oefen het verzamelen van duidelijke en complete requirements door de juiste vragen te stellen aan belanghebbenden.</p>
                        <ul class="text-sm text-slate-700 space-y-1">
                            <li>• Interviewtechnieken perfectioneren</li>
                            <li>• Verborgen requirements ontdekken</li>
                            <li>• Conflicterende eisen herkennen</li>
                        </ul>
                    </div>
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                        <div class="h-12 w-12 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center mb-4 text-xl">💬</div>
                        <h3 class="font-semibold text-lg mb-2">Project Communicatie</h3>
                        <p class="text-slate-600 mb-4">Verbeter je communicatievaardigheden in projectcontext, van status updates tot moeilijke gesprekken.</p>
                        <ul class="text-sm text-slate-700 space-y-1">
                            <li>• Effectieve status rapportage</li>
                            <li>• Slecht nieuws communiceren</li>
                            <li>• Cross-functionele samenwerking</li>
                        </ul>
                    </div>
                </div>
                <div class="text-center mt-12">
                    <a href="/admin" class="px-6 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start met oefenen</a>
                </div>
            </section>

            <!-- Belanghebbenden -->
            <section id="personas" class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="text-2xl font-bold">Virtuele Belanghebbenden</h2>
                    <p class="mt-3 text-slate-600">Interacteer met realistische virtuele belanghebbenden die reageren op basis van hun doelen, eigenschappen en communicatiestijl. Perfect voor het oefenen van challenges.</p>
                    <ul class="mt-4 space-y-2 text-slate-700">
                        <li>• Definiëer doelen en eigenschappen per belanghebbende</li>
                        <li>• Koppel belanghebbenden aan user stories</li>
                        <li>• Organiseer bestanden per relevante belanghebbende</li>
                    </ul>
                    <a href="/admin" class="inline-block mt-6 px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Bekijk belanghebbenden</a>
                </div>
                <div class="relative">

                    </div>
                </div>
            </section>

            <!-- Hoe werkt het -->
            <section id="hoe" class="px-6 py-16 bg-sky-50 border-t border-slate-200">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-12">
                        <h2 class="text-3xl font-bold mb-4">Hoe werkt Projectopia?</h2>
                        <p class="text-slate-600 text-lg">Van project setup tot challenge oefening in 4 eenvoudige stappen</p>
                    </div>
                    <div class="grid md:grid-cols-4 gap-6">
                        <div class="p-6 rounded-xl bg-white border border-slate-200">
                            <div class="text-slate-500 text-sm">Stap 1</div>
                            <div class="font-semibold">Project aanmaken</div>
                            <div class="text-slate-600 mt-1 text-sm">Context, doelstellingen en randvoorwaarden</div>
                        </div>
                        <div class="p-6 rounded-xl bg-white border border-slate-200">
                            <div class="text-slate-500 text-sm">Stap 2</div>
                            <div class="font-semibold">Masterclass kiezen</div>
                            <div class="text-slate-600 mt-1 text-sm">Selecteer het onderwerp dat je wilt oefenen</div>
                        </div>
                        <div class="p-6 rounded-xl bg-white border border-slate-200">
                            <div class="text-slate-500 text-sm">Stap 3</div>
                            <div class="font-semibold">Belanghebbenden configureren</div>
                            <div class="text-slate-600 mt-1 text-sm">Stakeholders met doelen en eigenschappen</div>
                        </div>
                        <div class="p-6 rounded-xl bg-white border border-slate-200">
                            <div class="text-slate-500 text-sm">Stap 4</div>
                            <div class="font-semibold">Oefenen en leren</div>
                            <div class="text-slate-600 mt-1 text-sm">Chat met virtuele belanghebbenden en ontwikkel je vaardigheden</div>
                        </div>
                    </div>
                    <div class="max-w-7xl mx-auto mt-8 text-center">
                        <a href="/admin" class="px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start je eerste challenge</a>
                    </div>
                </div>
            </section>
        </main>

        <footer class="max-w-7xl mx-auto px-6 py-10 text-sm text-slate-500 flex items-center justify-between">
            <div>© <script>document.write(new Date().getFullYear())</script> Projectopia</div>
            <div class="space-x-4">
                <a href="/admin" class="hover:text-sky-600">Inloggen</a>
                <a href="#features" class="hover:text-sky-600">Functies</a>
                <a href="#personas" class="hover:text-sky-600">Belanghebbenden</a>
            </div>
        </footer>
    </body>
    </html>


