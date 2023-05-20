<?php

namespace App\Http\Middleware;

use App\Models\User;
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
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = str_replace('Bearer ', '', $request->header('Authorization'));
        if (!$token) {
            return response()->json(['message' => 'Token missing']);
        }
        $user = $this->userRepository->getUserByToken($token);
        if ($user instanceof User) {
            $request->merge(['user' => $user]);
            return $next($request);
        } else {
            return response()->json(['message' => 'Invalid token']);
        }
    }
}
