<?php

/**
 * Created by PhpStorm.
 * User: jcastellanosg
 * Date: 3/5/2015
 * Time: 2:19 PM
 */



class FratrisValidaciones
{
    private $operator = [
        "=" => "Igual=",
        "Incluye" => "Incluye",
        "Inicia con" => "IniciaCon"
    ];

    private $types = ["string",
        "int"
    ];


    public function getValue($operator, $value, $type)
    {
        $value = $this->{$operator}($value, $type);
        return $value;
    }

    private function igual($value,$type)
    {

    }

    private function Incluye()
    {

    }

    private function IniciaCon()
    {

    }

}