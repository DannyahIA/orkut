<?php

namespace App\Core;

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);

        $viewFile = ROOT_PATH . "/src/Views/{$view}.php";

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View {$view} not found");
        }
    }

    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
}
