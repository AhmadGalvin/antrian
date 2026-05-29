<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Redirect user to appropriate dashboard based on role.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        return match($user->role) {
            'superadmin' => redirect()->route('superadmin.dashboard'),
            'admin', 'teller', 'cs' => redirect()->route('operator.dashboard'),
            'kiosk' => redirect()->route('kiosk.index'),
            default => redirect()->route('login'),
        };
    }
}
