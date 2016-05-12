<?php

namespace App\Controllers;

use T4\Mvc\Controller;
use T4\Core\Exception;
use T4\Http\Uploader;

class News
    extends Controller
{

    public function actionShowAll()
    {
        $news = new \App\Models\News(ROOT_PATH_PROTECTED . '/Components/News/data.php');
        $this->data->articles = $news->findAll();
    }

    public function actionShowById(int $id = 0)
    {
        $news = new \App\Models\News(ROOT_PATH_PROTECTED . '/Components/News/data.php');
        $this->data->id      = $id;
        $this->data->article = $news->findOne($id);
    }
    
    public function actionGetLast() {
        $news = new \App\Models\News(ROOT_PATH_PROTECTED . '/Components/News/data.php');
        $this->data->article = $news->findLast();
    }

    public function actionAdd()
    {
        if ($this->app->request->method === 'post') {

            $news = new \App\Models\News(ROOT_PATH_PROTECTED . '/Components/News/data.php');

            $post = $this->app->request->post;
            $file = $this->app->request->files['image'];

            // формируем имя для файла
            $ext = pathinfo($file->name, \PATHINFO_EXTENSION);
            $file->name = 'img' . sprintf('%02d', count($news) + 1) . '.' . $ext;

            // обрабатываем загрузку файла
            $uploader = new Uploader('image', ['png', 'jpg']);
            $uploader->setPath($this->app->config->uploads->news);

            $file = null;
            try {
                $file = $uploader();
            } catch (Exception $e) {

                $this->app->flash->error   = true;
                $this->app->flash->message = $e->getMessage();
                $this->app->flash->title   = $post->title;
                $this->app->flash->text   = $post->text;

                $this->redirect('/news/add/');
            }

            if ($file) {

                $article = new \App\Models\Article();

                foreach ( $post as $key => $value ) {
                    $article[$key] = $value;
                }

                $article['image'] = pathinfo($file, \PATHINFO_BASENAME);

                $id = $news->add($article);

                $this->redirect('/news/' . $id);
            }
        }
    }
}