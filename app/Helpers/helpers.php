<?php

if (!function_exists('notebook_path')) {
    function notebook_path($path = null) {
        if (!$path) {
            return config('filesystems.codeStorage.root') . DIRECTORY_SEPARATOR;
        }

        return config('filesystems.codeStorage.root') . DIRECTORY_SEPARATOR . $path;
    }
}
