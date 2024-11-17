<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SwitchDatabaseIfPrimaryFails
{
    public function handle($request, Closure $next)
    {
        try {

            DB::connection('mysql')->getPdo();
        } catch (\PDOException $e) {
            if ($e->getCode() == 2002) {
                DB::reconnect('sqliteLocal');

                if (Auth::check()) {
                    Auth::setUser(Auth::user());
                }
            }
        }

        return $next($request);
    }
}
