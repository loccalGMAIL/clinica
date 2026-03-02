<?php

if (! function_exists('setting')) {
    function setting(string $key, mixed $default = null): mixed
    {
        return app(\App\Services\SettingService::class)->get($key, $default);
    }
}

if (! function_exists('center_image')) {
    function center_image(string $name, ?string $fallback): ?string
    {
        $base = public_path("center/{$name}");
        foreach (['png', 'jpg', 'jpeg', 'webp', 'svg', 'ico'] as $ext) {
            if (file_exists("{$base}.{$ext}")) {
                return asset("center/{$name}.{$ext}") . '?v=' . filemtime("{$base}.{$ext}");
            }
        }

        return $fallback ? asset($fallback) : null;
    }
}
