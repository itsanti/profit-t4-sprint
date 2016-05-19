<?php

namespace App\Models;

use T4\Orm\Model;

/**
 * Class Good
 * @package App\Models
 *
 * @property string $title
 * @property float $price
 */
class Good
    extends Model
{
    protected static $schema = [

        'columns' => [
            'title'   => ['type' => 'string'],
            'price' => ['type' => 'float'],
        ],
        
        'relations' => [
            'category' => [
                'model' => Category::class,
                'type' => self::BELONGS_TO
            ]
        ]
    ];

    

}