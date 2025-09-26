<!doctype html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Projectopia – AI-projectsupport & simulatie</title>
        <meta name="description" content="Projectopia: AI-ondersteuning voor projecten in het onderwijs. Genereer backlog, plan sprints en simuleer stakeholders.">
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
                <a href="#ai" class="hover:text-sky-600">AI-support</a>
                <a href="#sim" class="hover:text-sky-600">Simulatie</a>
                <a href="#hoe" class="hover:text-sky-600">Hoe werkt het</a>
            </nav>
            <div class="flex items-center gap-3">
                <a href="/admin" class="px-4 py-2 rounded-lg border border-slate-200 hover:bg-slate-50">Inloggen</a>
                <a href="/kies-project" class="px-4 py-2 rounded-lg bg-sky-500 text-white hover:bg-sky-600">Kies project</a>
            </div>
        </header>

        <main>
            <!-- Hero -->
            <section class="max-w-7xl mx-auto px-6 pt-10 pb-16 md:pt-16 md:pb-24 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h1 class="text-4xl md:text-5xl font-bold leading-tight">
                        AI‑gerichte <span class="text-sky-600">projectsupport</span> en <span class="text-emerald-600">simulatie</span>
                    </h1>
                    <p class="mt-4 text-slate-600 text-lg">
                        Laat studenten sneller opstarten met context→backlog→sprints, en oefen stakeholdergesprekken in een veilige simulatie.
                    </p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="/admin" class="px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start nu</a>
                        <a href="#ai" class="px-5 py-3 rounded-lg border border-slate-200 hover:bg-slate-50">Wat kan AI hier?</a>
                    </div>
                    <div class="mt-6 flex items-center gap-4 text-sm text-slate-500">
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-sky-500"></span> Backlog & planning</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span> Persona‑chat</span>
                        <span class="flex items-center gap-2"><span class="h-2.5 w-2.5 rounded-full bg-amber-500"></span> Coachmodus</span>
                    </div>
                </div>
                <!-- Simple AI mock panel (no vendor imagery) -->
                <div class="relative">
                    <div class="absolute -inset-4 bg-gradient-to-tr from-sky-100 to-emerald-100 blur-2xl rounded-xl"></div>
                    <div class="relative rounded-xl border border-slate-200 bg-white shadow-lg overflow-hidden">
                        <div class="p-4 border-b border-slate-200 flex items-center justify-between text-xs text-slate-500">
                            <span>AI Projectassistent</span>
                            <span class="px-2 py-1 rounded bg-sky-50 text-sky-600">Simulatie</span>
                        </div>
                        <div class="p-6 grid gap-4">
                            <div class="text-sm text-slate-500">Maria (Klant): Wat is belangrijker: online boeking of ritinformatie?</div>
                            <div class="p-3 rounded-lg bg-slate-50 text-slate-700">We adviseren te starten met boeking (impact op omzet), en ritinformatie als parallelle Epic met lichte MVP.</div>
                            <div class="flex gap-2">
                                <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs">KPI: conversie</span>
                                <span class="px-3 py-1 rounded-full bg-sky-50 text-sky-700 text-xs">Epic: Ticketing</span>
                                <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs">Risico: scope</span>
                            </div>
                            <div class="flex gap-3 justify-end">
                                <button class="px-3 py-2 rounded-md border border-slate-200 text-slate-600">Vraag opnieuw</button>
                                <button class="px-3 py-2 rounded-md bg-sky-500 text-white">Genereer backlog</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- AI Support -->
            <section id="ai" class="bg-white border-y border-slate-200">
                <div class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-sky-100 text-sky-600 flex items-center justify-center mb-3">🏗️</div>
                        <h3 class="font-semibold text-lg mb-1">Context → Epics → Stories</h3>
                        <p class="text-slate-600">AI verfijnt context naar epics en user stories met acceptatiecriteria en prioriteit.</p>
                    </div>
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center mb-3">🗂️</div>
                        <h3 class="font-semibold text-lg mb-1">Sprintplanner</h3>
                        <p class="text-slate-600">Automatisch indelen op capaciteit, afhankelijkheden en leerdoelen.</p>
                    </div>
                    <div class="p-6 rounded-xl border border-slate-200 shadow-sm">
                        <div class="h-10 w-10 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center mb-3">🧭</div>
                        <h3 class="font-semibold text-lg mb-1">Coachmodus & rubrics</h3>
                        <p class="text-slate-600">Hints tijdens de dialoog en rubric‑scores voor reflectie en beoordeling.</p>
                    </div>
                </div>
            </section>

            <!-- Simulatie -->
            <section id="sim" class="max-w-7xl mx-auto px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
                <div>
                    <h2 class="text-2xl font-bold">Stakeholder‑simulatie</h2>
                    <p class="mt-3 text-slate-600">Praat met persona’s (klant, PO, doelgroep) met realistische doelen en communicatiestijl. Oefen prioriteren en omgaan met conflicterende belangen.</p>
                    <ul class="mt-4 space-y-2 text-slate-700">
                        <li>• Persona’s met doelen, eigenschappen en toon</li>
                        <li>• Scenario’s: scope creep, budget, compliance</li>
                        <li>• Reflectie: wat ging goed, wat kan beter</li>
                    </ul>
                    <a href="/admin" class="inline-block mt-6 px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Probeer simulatie</a>
                </div>
                <div class="relative">
                    <div class="absolute -inset-3 bg-sky-100 blur-2xl rounded-xl"></div>
                    <div class="relative rounded-xl border border-slate-200 bg-white shadow-lg p-6 grid gap-3">
                        <div class="text-sm text-slate-500">Tom (PO): Wat is je acceptatiecriterium voor checkout?</div>
                        <div class="p-3 rounded-lg bg-slate-50 text-slate-700 text-sm">Betaling slaagt en orderbevestiging met QR‑ticket binnen 30 seconden.</div>
                        <div class="flex gap-2">
                            <span class="px-2.5 py-1 rounded bg-slate-100 text-slate-700 text-xs">AC gedekt</span>
                            <span class="px-2.5 py-1 rounded bg-emerald-50 text-emerald-700 text-xs">Risico verlaagd</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Hoe werkt het -->
            <section id="hoe" class="px-6 py-16 bg-sky-50 border-t border-slate-200">
                <div class="max-w-7xl mx-auto grid md:grid-cols-4 gap-6">
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 1</div>
                        <div class="font-semibold">Context & doelen</div>
                        <div class="text-slate-600 mt-1 text-sm">Projectkader en KPI’s</div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 2</div>
                        <div class="font-semibold">Persona’s</div>
                        <div class="text-slate-600 mt-1 text-sm">Stakeholders & toon</div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 3</div>
                        <div class="font-semibold">Backlog</div>
                        <div class="text-slate-600 mt-1 text-sm">Epics → stories + AC</div>
                    </div>
                    <div class="p-6 rounded-xl bg-white border border-slate-200">
                        <div class="text-slate-500 text-sm">Stap 4</div>
                        <div class="font-semibold">Sprints</div>
                        <div class="text-slate-600 mt-1 text-sm">Capaciteit & planning</div>
                    </div>
                </div>
                <div class="max-w-7xl mx-auto mt-8 text-center">
                    <a href="/admin" class="px-5 py-3 rounded-lg bg-sky-500 text-white font-semibold hover:bg-sky-600">Start je eerste sprint</a>
                </div>
            </section>
        </main>

        <footer class="max-w-7xl mx-auto px-6 py-10 text-sm text-slate-500 flex items-center justify-between">
            <div>© <script>document.write(new Date().getFullYear())</script> Projectopia</div>
            <div class="space-x-4">
                <a href="/admin" class="hover:text-sky-600">Inloggen</a>
                <a href="#ai" class="hover:text-sky-600">AI-support</a>
                <a href="#sim" class="hover:text-sky-600">Simulatie</a>
            </div>
        </footer>
    </body>
    </html>


