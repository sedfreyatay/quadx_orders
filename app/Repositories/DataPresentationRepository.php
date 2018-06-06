<?php

namespace App\Repositories;

use App\Interfaces\DataPresentationInterface;

class DataPresentationRepository implements DataPresentationInterface
{
    /**
     * print out data
     */
    public function present_data($data)
    {
        array_walk($data, array($this, 'array_printer'), 0);
    }

    /**
     * Prints Array tree
     * @param $item
     * @param $key
     * @param $depth
     */
    private function array_printer($item, $key, $depth) {
        echo str_pad('', $depth * 4, '_', STR_PAD_LEFT);
        echo $key;
        if(is_array($item)) {
            echo ": <br>";
            array_walk($item, array($this, 'array_printer'), $depth+1);
        } else {
            echo ': '.$item."<br>";
        }
    }

}