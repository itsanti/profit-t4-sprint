<?php

namespace App\Controllers;

use T4\Mvc\Controller;

class Index
    extends Controller
{

    public function actionDefault()
    {
        $this->data->name = $this->app->config->myapp->name;
    }

    public function actionAbout()
    {
    }
}