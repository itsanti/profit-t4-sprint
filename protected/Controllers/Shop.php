<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Good;
use T4\Mvc\Controller;


class Shop
    extends Controller
{

    public function actionDefault()
    {
        $categories = Category::findAllTree();
        $this->data->root       = $categories->first();
        $this->data->categories = $this->renderCategories($categories->slice(1), 1);

        /*$cat1 = Category::findByPK(3);
        $cat = new Category();
        $cat->title = 'Radeon';
        $cat->parent = $cat1;
        $cat->save();*/
    }

    public function actionAdd()
    {
        $this->data->categories = Category::findAllTree();

        if ($this->app->request->method === 'post') {
            $post = $this->app->request->post;
            $parent = Category::findByPK($post['cat']);
            if(!empty($parent)) {
                $cat = new Category();
                $cat->title = $post['title'];
                $cat->parent = $parent;
                $cat->save();
            }
            $this->redirect('/shop');
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
            $this->data->cattitle = $cat->title;
            $this->data->goods = $cat->goods;
        }
    }

    public function actionAddGood()
    {
        $this->data->categories = Category::findAllTree();

        if ($this->app->request->method === 'post') {
            $post = $this->app->request->post;
            $cat = Category::findByPK($post['cat']);
            if(!empty($cat)) {
                $good = new Good();
                $good->title = $post['title'];
                $good->price = $post['price'];
                $good->category = $cat;
                $good->save();
            }
            $this->redirect('/shop/show?id='.$cat->getPk());
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