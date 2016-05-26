<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Good;
use T4\Core\MultiException;
use T4\Mvc\Controller;


class Shop
    extends Controller
{

    public function actionDefault()
    {
        $categories = Category::findAllTree();
        $this->data->root       = $categories->first();
        $this->data->categories = $this->renderCategories($categories->slice(1), 1);
    }

    public function actionAdd($cat = null)
    {
        $this->data->categories = Category::findAllTree();
        $this->data->cat = $cat;

        if ($this->app->request->method === 'post') {

            $parent = Category::findByPK($cat['id']);

            if(!empty($parent)) {
                try {
                    $data = new Category();

                    $data->fill($cat);

                    $data->parent = $parent;

                    $data->save();

                    $this->redirect('/shop');

                } catch (MultiException $errors) {
                    $this->data->errors = $errors;
                }
            }
        }
    }

    public function actionDel()
    {
        $this->data->categories = Category::findAllTree();

        if ($this->app->request->method === 'post') {
            $post = $this->app->request->post;
            $cat = Category::findByPK($post['cat']);
            if(!empty($cat)) {
                $cat->delete();
            }
            $this->redirect('/shop');
        }
    }

    public function actionShow($id){
        $cat = Category::findByPK($id);
        if (!empty($cat)) {
            $this->data->cat = $cat;
        }
    }

    public function actionAddGood($good = null)
    {
        $this->data->categories = Category::findAllTree();
        $this->data->good = $good;

        if ($this->app->request->method === 'post') {

            $cat = Category::findByPK($good['cat']);

            if(!empty($cat)) {
                $data = new Good();

                try {
                    $data->fill($good);

                    $data->category = $cat;

                    $data->save();

                    $this->redirect('/shop/show?id=' . $cat->getPk());

                } catch (MultiException $errors) {
                    $this->data->errors = $errors;
                }
            }
        }
    }
    
    public function renderCategories($categories, $lvl)
    {
        $html = '<ul class="list-group">';
        foreach ( $categories as $category ) {
            $link = '<a href="/shop/show?id='.$category->getPk().'">' . $category->title . '</a>';
            if ($category->hasChildren()) {
                $html .= '<li class="list-group-item">' . $link;
                $html .= $this->renderCategories($category->findAllChildren(), $category->__lvl + 1) . '</li>';
            } elseif ($lvl == $category->__lvl) {
                $html .= '<li class="list-group-item">' . $link . '</li>';
            }
        }
        $html .= '</ul>';
        return $html;
    }

}