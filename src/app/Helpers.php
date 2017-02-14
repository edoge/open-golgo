<?php

namespace App;

class Helpers
{
    public static function array_rand_weighted($entries)
    {
        $sum = array_sum($entries);
        $rand = rand(1, $sum);

        foreach($entries as $key => $weight){
            if (($sum -= $weight) < $rand) return $key;
        }
    }
}