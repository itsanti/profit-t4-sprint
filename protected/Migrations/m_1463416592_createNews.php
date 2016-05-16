<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1463416592_createNews
    extends Migration
{

    public function up()
    {
        $this->createTable('news', [
            'title'   => ['type' => 'string'],
            'text'    => ['type' => 'text'],
            'image'   => ['type' => 'string', 'length' => 15],
            'created' => ['type' => 'integer']
        ]);

        // import news from file HW2
        $news = new \App\Models\News(ROOT_PATH_PROTECTED . '/Components/News/data.php');
        $data = $news->findAll();

        foreach ( $data as $article ) {
            $this->insert('news', [
                'title'   => $article->title,
                'text'    => $article->text,
                'image'   => $article->image,
                'created' => $this->getTimestamp()
            ]);
        }
    }

    public function down()
    {
        $this->dropTable('news');
    }
    
}