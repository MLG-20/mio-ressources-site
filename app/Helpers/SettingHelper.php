<?php

if (! function_exists('setting')) {
    /**
     * Get a setting value by key
     *
     * @param string $key The setting key
     * @param mixed $default Default value if not found
     * @return mixed
     */
    function setting($key, $default = null) {
        return \App\Models\Setting::where('key', $key)->first()?->value ?? $default;
    }
}
