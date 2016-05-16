<?php

namespace App\Models;

use T4\Orm\Model;

/**
 * Class DbNews
 * @package App\Models
 *
 * @property string $title
 * @property string $text
 * @property string $image
 * @property int $created
 */
class DbNews
    extends Model
{
    protected static $schema = [
        'table' => 'news',
        'columns' => [
            'title'   => ['type' => 'string'],
            'text'    => ['type' => 'text'],
            'image'   => ['type' => 'string', 'length' => 15],
            'created' => ['type' => 'integer']
        ],
    ];

    public function getSrc() {
        $app = \T4\Mvc\Application::instance();
        return $app->config->uploads->news . '/' . $this->image;
    }
}