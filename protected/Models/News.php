<?php

namespace App\Models;

use T4\Core\Config;

class News
    extends Config {

    public function findAll(): array {
        $data = [ ];

        foreach ( $this->toArray() as $key => $item ) {
            $data[ $key ] = new Article( $item );
        }

        return $data;
    }

    public function findOne( $id ): Article {
        $data = $this->findAll();

        if ( array_key_exists( $id, $data ) ) {
            return $data[ $id ];
        } else {
            return new Article();
        }
    }

    public function findLast()
    {
        $data = $this->findAll();
        return [
            'id' => count($data),
            'article' => array_pop($data)
        ];
    }

    public function add(Article $data)
    {
        $this->append($data);
        $this->save();
        return count($this);
    }
}