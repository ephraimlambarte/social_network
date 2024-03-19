<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\FriendRequest;

class FriendRequestActionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $fr = FriendRequest::find($request->friendRequest)->first();

        if ($fr && $fr->isActionable()) {
            return $next($request);
        }
        return response()->json([
            'message' => 'Friend request invalid'
        ], 404);
    }
}
