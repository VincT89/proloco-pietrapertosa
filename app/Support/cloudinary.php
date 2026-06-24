<?php

if (! function_exists('cloudinary_url')) {
    function cloudinary_url(?string $url, string $preset = 'default'): ?string
    {
        if (! $url || ! str_contains($url, '/upload/')) {
            return $url;
        }

        $transforms = [
            'hero' => 'w_1920,h_900,c_fill,g_auto/f_auto/q_auto',
            'large' => 'w_1600,c_limit/f_auto/q_auto',
            'card' => 'w_600,h_400,c_fill,g_auto/f_auto/q_auto',
            'thumb' => 'w_400,h_300,c_fill,g_auto/f_auto/q_auto',
            'small' => 'w_300,c_limit/f_auto/q_auto',
            'poster' => 'w_900,c_limit/f_auto/q_auto',
            'poster_blur' => 'w_900,h_1200,c_fill,g_auto,e_blur:800,q_auto,f_auto',
            'default' => 'w_1200,c_limit/f_auto/q_auto',
        ];

        $transform = $transforms[$preset] ?? $transforms['default'];

        return str_replace('/upload/', '/upload/' . $transform . '/', $url);
    }
}
