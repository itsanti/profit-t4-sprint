<?php

namespace App\Models;

use T4\Fs\Exception;
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

    protected function validateTitle($val)
    {
        if (strlen(strip_tags($val)) < 4) {
            yield new Exception('Короткий заголовок категории');
        }
        if (!preg_match('~[^0-9]~', $val)) {
            yield new Exception('Категория не может состоять только из цифр');
        }
        return true;
    }

    protected function sanitizeTitle($val) {
        $val = strip_tags($val);
        $fc = mb_strtoupper(mb_substr($val, 0, 1));
        return $fc.mb_substr($val, 1);
    }

    protected function afterDelete()
    {
        $goods = Good::findAllByColumn('__category_id', $this->getPk());
        foreach ($goods as $good) {
            $good->delete();
        }
    }
}