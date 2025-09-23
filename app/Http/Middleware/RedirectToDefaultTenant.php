<?php

namespace App\Http\Middleware;

use Closure;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectToDefaultTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $panel = Filament::getCurrentPanel();

        if (! $panel || ! $panel->hasTenancy()) {
            return $next($request);
        }

        $route = $request->route();
        $tenantParam = $route?->parameter('tenant');

        if ($tenantParam) {
            return $next($request);
        }

        $user = $request->user();
        if (! $user || ! method_exists($user, 'getTenants')) {
            return $next($request);
        }

        $tenants = $user->getTenants($panel);
        $first = $tenants[0] ?? $tenants->first();

        if (! $first) {
            return $next($request);
        }

        $path = trim($request->path(), '/');
        $adminPrefix = trim($panel->getPath(), '/');

        if ($path === $adminPrefix) {
            return redirect()->to(url($adminPrefix . '/' . $first->getRouteKey()));
        }

        if (str_starts_with($path, $adminPrefix . '/')) {
            $rest = substr($path, strlen($adminPrefix) + 1);
            return redirect()->to(url($adminPrefix . '/' . $first->getRouteKey() . '/' . $rest));
        }

        return $next($request);
    }
}



