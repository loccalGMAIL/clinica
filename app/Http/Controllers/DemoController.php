<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class DemoController extends Controller
{
    private function guardProduction(): void
    {
        if (app()->environment('production')) abort(404);
    }

    /** Login directo sin contraseña */
    public function login(Request $request)
    {
        $this->guardProduction();
        $user = User::findOrFail($request->input('user_id'));
        if (!$user->is_active) return back()->with('error', 'Usuario inactivo');

        Auth::login($user);
        $request->session()->regenerate();
        ActivityLog::record('login', $user, $user->name . ' (demo)', $user->id);

        return $user->isProfessional()
            ? redirect()->route('professional.dashboard')
            : redirect()->route('dashboard');
    }

    /** Vista de confirmación del reset */
    public function showReset()
    {
        $this->guardProduction();
        if (!auth()->user()->canAccessModule('system')) abort(403);
        return view('demo.reset');
    }

    /** Ejecuta migrate:fresh --seed y redirige al login */
    public function reset(Request $request)
    {
        $this->guardProduction();
        if (!auth()->user()->canAccessModule('system')) abort(403);

        Auth::logout();
        $request->session()->invalidate();

        Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);

        // Después de migrate:fresh la tabla sessions fue recreada — no podemos usar
        // flash (la sesión en memoria quedó huérfana). Pasamos el mensaje por query param.
        return redirect()->to(route('login') . '?reset=1');
    }
}
