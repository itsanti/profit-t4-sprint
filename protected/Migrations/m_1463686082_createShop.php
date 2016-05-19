<?php

namespace App\Migrations;

use T4\Orm\Migration;

class m_1463686082_createShop
    extends Migration
{

    public function up()
    {
        $this->createTable('categories', [
            'title'   => ['type' => 'string'],
        ], [], ['tree']);

        $this->insert('categories', [
            'title' => 'Комплектующие для ПК',
            '__lft' => 1,
            '__rgt' => 2,
            '__lvl' => 0,
            '__prt' => 0
        ]);

        $this->createTable('goods', [
            '__category_id' => ['type' => 'link'],
            'title'   => ['type' => 'string'],
            'price' => ['type' => 'float'],
        ]);
    }

    public function down()
    {
        $this->dropTable('categories');
        $this->dropTable('goods');
    }
    
}