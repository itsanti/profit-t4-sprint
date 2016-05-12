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

    public function findOne( $id )
    {
        $data = $this->findAll();

        if ( array_key_exists( $id, $data ) ) {
            return $data[ $id ];
        } else {
            return false;
        }
    }

    public function findLast()
    {
        return new Article(array_pop($this->toArray()));
    }

    public function add(Article $data)
    {
        $this->append($data);
        $this->save();
        return count($this);
    }
}