<?php

namespace App\Models;

use T4\Orm\Model;

/**
 * Class Category
 * @package App\Models
 *
 * @property string $title
 */
class Category
    extends Model
{
    protected static $schema = [
        'table' => 'categories',
        
        'columns' => [
            'title'   => ['type' => 'string'],
        ],

        'relations' => [
            'goods' => [
                'model' => Good::class,
                'type' => self::HAS_MANY
            ]
        ]
    ];

    protected static $extensions = ['tree'];
}