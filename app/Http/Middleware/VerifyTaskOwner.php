<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyTaskOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->route()->parameter('taskId');

        $task = auth()->user()->tasks()->find($taskId);

        if( !$task ){
            return response()->json([
                'message' => 'List Not Found'
            ], 404);
        }

        return $next($request);
    }
}
