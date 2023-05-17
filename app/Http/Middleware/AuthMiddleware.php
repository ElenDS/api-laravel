<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware
{
    public function __construct(protected UserRepository $userRepository)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->get('token');
        if (!$token) {
            return response()->json(['message' => 'Token missing']);
        }

        $user = $this->userRepository->getUserByTokenAndEmail($request->get('email'), $token);
        if (!$user) {
            return response()->json(['message' => 'Invalid token']);
        }

        $request->merge(['user' => $user]);
        return $next($request);
    }
}
