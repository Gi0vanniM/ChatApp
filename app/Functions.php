<?php

namespace App;

class Functions
{
    public static function sanitize($data)
    {
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = stripcslashes($data);
        return $data;
    }

    public static function objectInArrayToArray($data)
    {
        $new = [];
        foreach ($data as $key => $value) {
            $new[$key] = (array)$value;
        }
        return $new;
    }
}
