<?php

namespace App\Repositories;

use App\Interfaces\ShipmentInterface;

class ShipmentRepository implements ShipmentInterface
{
    /**
     * @param $data
     * @return array
     */
    public function format_object($data)
    {
        $data_info = [];
        if(empty($data)){
            return $data_info;
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