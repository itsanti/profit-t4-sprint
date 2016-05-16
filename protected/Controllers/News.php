<?php

namespace App\Controllers;

use App\Models\DbNews;
use T4\Mvc\Controller;
use T4\Core\Exception;
use T4\Http\Uploader;

class News
    extends Controller
{

    public function actionAdmin()
    {
    }

    public function actionShowAllTbl()
    {
        $this->data->articles = DbNews::findAll();
    }

    public function actionShowAll()
    {
        $this->data->articles = DbNews::findAll(['order' => 'created DESC']);
    }

    public function actionShowById(int $id = 0)
    {
        $this->data->id      = $id;
        $this->data->article = DbNews::findByPK($id);
    }
    
    public function actionGetLast() {
        $this->data->article = DbNews::find(['order' => 'created DESC']);
    }

    public function actionAdd()
    {
        if ($this->app->request->method === 'post') {

            $post = $this->app->request->post;
            $file = $this->app->request->files['image'];

            // формируем временное имя для файла
            $ext = pathinfo($file->name, \PATHINFO_EXTENSION);
            $file->name = 'i' . time() . '.' . $ext;

            $file = $this->uploadImage('image', $post);
            
            if (is_null($file)) {
                $this->redirect('/admin/news/add/');
            } else {

                $file = ROOT_PATH_PUBLIC . $file;

                $article = new DbNews();

                foreach ( $post as $key => $value ) {
                    $article[$key] = $value;
                }

                $article['created'] = time();
                $article->save();

                $id = $article->getPk();

                // формируем постоянное имя для файла
                $ext = pathinfo($file, \PATHINFO_EXTENSION);
                $name = 'img' . sprintf('%02d', $id) . '.' . $ext;

                rename($file, pathinfo($file, \PATHINFO_DIRNAME) . DS . $name);

                $article['image'] = $name;
                $article->save();

                $this->redirect('/news/' . $id);
            }
        }
    }

    public function actionDel(int $id)
    {
        $this->data->id = $id;

        if ($this->app->request->method === 'post' and $id > 0) {

            $article = DbNews::findByPK($id);

            if (!empty($article)) {
                $this->deleteImageFile($article->image);
                $article->delete();
            }

            $this->redirect('/admin/news/list/');
        }
    }

    public function actionEdit(int $id = 0)
    {
        
        $article = DbNews::findByPK($id);

        if (empty($article)) {
            $this->redirect('/admin/news/list/');
        }

        $this->data->article = $article;
            
        if ($this->app->request->method === 'post' and $id > 0) {

            $post = $this->app->request->post;

            if ($this->app->request->isUploaded('image')) {
                $file = $this->app->request->files['image'];
                $file->name = $article->image;
                $file = $this->uploadImage('image', $post);
                
                if (is_null($file)) {
                    $this->redirect('/admin/news/edit/' . $id);    
                } else {
                    $this->deleteImageFile($article->image);
                    $file = ROOT_PATH_PUBLIC . $file;
                    rename($file, pathinfo($file, \PATHINFO_DIRNAME) . DS . $article->image);
                }
            }
            
            $article->title = $post->title;
            $article->text  = $post->text;
            $article->save();

            $this->redirect('/news/' . $id);
        }
    }
    
    private function uploadImage($name, $post)
    {
        // обрабатываем загрузку файла
        $uploader = new Uploader($name, ['png', 'jpg']);
        $uploader->setPath($this->app->config->uploads->news);

        $file = null;
        try {
            $file = $uploader();
        } catch (Exception $e) {
            $this->app->flash->error   = true;
            $this->app->flash->message = $e->getMessage();
            $this->app->flash->title   = $post->title;
            $this->app->flash->text    = $post->text;
        }
        return $file;
    }

    private function deleteImageFile($name)
    {
        $file = ROOT_PATH_PUBLIC . $this->app->config->uploads->news . '/' . $name;

        if (file_exists($file)) {
            return unlink($file);
        }

        return false;
    }
}