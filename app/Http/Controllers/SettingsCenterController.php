<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Services\SettingService;
use Illuminate\Http\Request;

class SettingsCenterController extends Controller
{
    public function __construct(private SettingService $settings) {}

    public function index()
    {
        $settings = Setting::center()->get()->pluck('value', 'key');

        return view('settings.center.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'center_name'     => 'nullable|string|max:100',
            'center_subtitle' => 'nullable|string|max:100',
            'center_address'  => 'nullable|string|max:200',
            'center_phone'    => 'nullable|string|max:50',
            'center_email'    => 'nullable|email|max:100',
            'logo'            => 'nullable|image|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'login_bg'        => 'nullable|image|mimes:png,jpg,jpeg,webp|max:5120',
        ]);

        // Guardar campos de texto
        $textFields = ['center_name', 'center_subtitle', 'center_address', 'center_phone', 'center_email'];
        foreach ($textFields as $field) {
            Setting::updateOrCreate(
                ['key' => $field],
                ['group' => 'center', 'value' => $request->input($field)]
            );
        }

        // Procesar imágenes
        $centerPath = public_path('center');
        if (! is_dir($centerPath)) {
            mkdir($centerPath, 0755, true);
        }

        if ($request->hasFile('logo')) {
            $this->replaceImage($centerPath, 'logo', $request->file('logo'));
        }

        if ($request->hasFile('login_bg')) {
            $this->replaceImage($centerPath, 'login_bg', $request->file('login_bg'));
        }

        $this->settings->clearCache();

        return redirect()->route('settings.center')->with('success', 'Configuración del centro guardada correctamente.');
    }

    private function replaceImage(string $dir, string $name, $file): void
    {
        // Eliminar archivos anteriores del mismo nombre (cualquier extensión)
        foreach (['png', 'jpg', 'jpeg', 'webp', 'svg'] as $ext) {
            $existing = "{$dir}/{$name}.{$ext}";
            if (file_exists($existing)) {
                unlink($existing);
            }
        }

        $ext = strtolower($file->getClientOriginalExtension());
        $file->move($dir, "{$name}.{$ext}");
    }
}
