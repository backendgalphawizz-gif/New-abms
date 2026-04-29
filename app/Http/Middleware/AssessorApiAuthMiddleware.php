<?php

namespace App\Http\Middleware;

use App\Model\Admin;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function App\CPU\translate;

class AssessorApiAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->bearerToken();

        if ($bearerToken && strlen($bearerToken) > 30) {
            $segments = explode('.', $bearerToken);
            if (count($segments) === 3) {
                $payload = json_decode(base64_decode(strtr($segments[1], '-_', '+/')), true);

                if (is_array($payload) && isset($payload['jti'], $payload['sub'])) {
                    $tokenExists = DB::table('oauth_access_tokens')
                        ->where('id', $payload['jti'])
                        ->where('user_id', $payload['sub'])
                        ->where('revoked', 0)
                        ->where(function ($query) {
                            $query->whereNull('expires_at')
                                ->orWhere('expires_at', '>', now());
                        })
                        ->exists();

                    if ($tokenExists) {
                        $assessor = Admin::with('assessor')
                            ->where('id', $payload['sub'])
                            ->where('admin_role_id', 3)
                            ->where('status', 1)
                            ->first();

                        if ($assessor) {
                            $request['assessor'] = $assessor;
                            return $next($request);
                        }
                    }
                }
            }
        }

        return response()->json([
            'auth-001' => translate('Your existing session token does not authorize you any more')
        ], 401);
    }
}
