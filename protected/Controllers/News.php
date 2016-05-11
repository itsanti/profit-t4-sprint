<?php

namespace App\Controllers;

use T4\Mvc\Controller;
use T4\Core\Exception;
use T4\Http\Uploader;

class News
    extends Controller
{

    protected $news = null;

    protected function beforeAction( $action ) {
        $this->news = new \App\Models\News(ROOT_PATH_PROTECTED . '/Components/News/data.php');
        return true;
    }

    public function actionShowAll()
    {
        $this->data->articles = $this->news->findAll();
    }

    public function actionShowById(int $id = 0)
    {

        $this->data->id = $id;
        
        $article = $this->news->findOne($id);

        if (count($article)) {
            $this->data->article = $article;
        } else {
            $this->data->article = null;
        }
    }
    
    public function actionGetLast() {
        $data = $this->news->findLast();
        $this->data->id = $data['id'];
        $this->data->article = $data['article'];
    }

    public function actionAdd()
    {
        if ($this->app->request->method === 'post') {
            $post = $this->app->request->post;
            $file = $this->app->request->files['image'];

            // формируем имя для файла
            $ext = pathinfo($file->name, \PATHINFO_EXTENSION);
            $file->name = 'img' . sprintf('%02d', count($this->news) + 1) . '.' . $ext;

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

                $id = $this->news->add($article);

                $this->redirect('/news/' . $id);
            }
        }
    }
}