<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BranchMiddleware
{
    /**
     * Ensure user can only access their own branch data.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        
        // Superadmin can access all branches
        if ($user->isSuperadmin()) {
            return $next($request);
        }

        // Check if route has branch_id parameter
        $branchId = $request->route('branch_id') ?? $request->route('branch');
        
        if ($branchId && $user->branch_id != $branchId) {
            abort(403, 'Anda tidak memiliki akses ke cabang ini.');
        }

        return $next($request);
    }
}
