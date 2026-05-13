<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogSiteVisitor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $ip = $request->ip();
            if ($ip) {
                $date = now()->toDateString();
                $visitor = \App\Models\SiteVisitor::firstOrCreate(
                    ['ip_address' => $ip, 'visit_date' => $date],
                    ['hits' => 0]
                );
                
                // Increment without triggering timestamps if not needed, but increment() does trigger it which is fine.
                // We use DB to avoid retrieving the model again if we just want to increment
                \DB::table('site_visitors')
                    ->where('id', $visitor->id)
                    ->increment('hits');
            }
        } catch (\Exception $e) {
            // Silently fail if database is not available or other issues
        }

        return $response;
    }
}
