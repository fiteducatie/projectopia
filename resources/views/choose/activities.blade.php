@extends('layouts.guest')

@section('title', 'Activiteiten – ' . $team->name . ' – Projectopia')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">Activiteiten van {{ $team->name }}</h1>
        <a href="{{ route('choose.teams') }}" class="text-sky-600 hover:underline">← Terug naar teams</a>
    </div>
    <div class="grid md:grid-cols-3 gap-6">
        @forelse ($activities as $activity)
            <a href="{{ route('choose.activity', $activity) }}" class="block rounded-xl border border-slate-200 bg-white p-6 hover:shadow-md">
                <div class="text-lg font-semibold">{{ $activity->name }}</div>
                <div class="mt-1 text-slate-600 text-sm">Domein: {{ ucfirst($activity->domain) }}</div>
                <div class="mt-1 text-slate-600 text-sm">Periode: {{ optional($activity->start_date)->format('d-m-Y') }} – {{ optional($activity->end_date)->format('d-m-Y') }}</div>
            </a>
        @empty
            <div class="col-span-full text-slate-500">Geen activiteiten in dit team.</div>
        @endforelse
    </div>
@endsection



