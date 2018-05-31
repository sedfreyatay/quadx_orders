<?php

namespace App\Repositories;

use App\Interfaces\ShipmentInterface;

class ShipmentRepository implements ShipmentInterface{

    /**
     * print out data
     */
    public function present_data($data)
    {
        if(empty($data)){
            echo 'Something went wrong. Please try again.';
            return false;
        }

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


    /**
     * Format data to the required values
     * @param $data
     * @return bool
     */
    public function format_object($data)
    {
        if(empty($data)){
            return false;
        }

        $data = array_map("json_decode", $data);
        $total_sales = 0;
        $total_collections = 0;

        foreach ($data as $val){
            $data_key = $val->tracking_number.' ('.$val->status.')';

            foreach ($val->tat as $status => $tat){
                $history[$status] = $tat->date;
            }

            $data_info[$data_key]['history'] = array_flip(array_sort($history));
            $data_info[$data_key]['breakdown'] = [
                'subtotal' => $val->subtotal,
                'shipping' => $val->shipping,
                'tax' => $val->tax,
                'fee' => $val->fee,
                'insurance' => $val->insurance,
                'discount' => $val->discount,
                'total' => $val->total
            ];
            $data_info[$data_key]['fees'] = [
                'shipping_fee' => $val->shipping_fee,
                'insurance_fee' => $val->insurance_fee,
                'transaction_fee' => $val->transaction_fee
            ];

            $total_collections = $total_collections + $val->total;
            $total_sales = $total_sales + array_sum($data_info[$data_key]['fees']);
        }

        $data_info['total collections'] = $total_collections;
        $data_info['total sales'] = $total_sales;

        return $data_info;
    }

}