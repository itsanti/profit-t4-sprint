<?php

namespace App\Models;

use T4\Fs\Exception;
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

    protected function validateTitle($val)
    {
        if (strlen(strip_tags($val)) <= 2) {
            yield new Exception('Короткий заголовок товара');
        }
        if (!preg_match('~[^0-9]~', $val)) {
            yield new Exception('Заголовок не может состоять только из цифр');
        }
        if (preg_match('~^[0-9]~', $val)) {
            yield new Exception('Заголовок не может начинаться с цифры');
        }
        return true;
    }

    protected function sanitizeTitle($val) {
        return strip_tags($val);
    }

    protected function validatePrice($val)
    {
        $val = floatval($val);

        if ($val < 0) {
            yield new Exception('Цена не может быть отрицательной');
        }
        if ($val >= 0 && $val < 2) {
            yield new Exception('Слишком низкая цена');
        }
        return true;
    }

    protected function sanitizePrice($val) {
        return round($val, 2);
    }
}