<?php

namespace App\Models;

use T4\Core\Std;

class Article
    extends Std
{
    public function getSrc() {
        $app = \T4\Mvc\Application::instance();
        return $app->config->uploads->news . '/' . $this->image;
    }
}