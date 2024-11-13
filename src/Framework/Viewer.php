<?php
declare(strict_types=1);
namespace Framework;

class Viewer {

    public function render(string $template, array $data=[]):string{
        extract($data, EXTR_SKIP);
        ob_start();
        require "views/$template.php";

        return ob_get_clean();

    }
}