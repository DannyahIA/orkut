<?php

if (!function_exists('dd')) {
    function dd($data)
    {
        echo '<pre>';
        var_dump($data);
        echo '</pre>';
        die;
    }
}

if (!function_exists('url')) {
    function url($path)
    {
        // Adjust this if running in a subdirectory
        return $path;
    }
}
