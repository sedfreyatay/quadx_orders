<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class OrderRepository implements OrderInterface{

    private $source_uri = 'https://api.staging.lbcx.ph/v1/orders/';

    /**
     * Get Orders from API
     * @param $order_list
     * @return array|bool
     */
    public function get_orders($order_list)
    {
        if(empty($order_list)){
            return false;
        }

        $headers = ['X-Time-Zone' => 'Asia/Manila'];
        $client = new Client(['base_uri' => $this->source_uri, 'headers' => $headers]);

        foreach ($order_list as &$order){
            $order = $client->getAsync($order);
        }

        try {
            // Wait on all of the requests to complete. Throws a ConnectException
            // if any of the requests fail
            $results = Promise\unwrap($order_list);
        } catch (ConnectException $e) {
            return false;
        }

        foreach ($results as &$result){
            $result = $result->getBody()->getContents();
        }

        return $results;
    }

    /**
     * print out data
     */
    public function present_orders($data)
    {
        if(!$data){
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
    public function format_orders($data)
    {
        if(empty($data)){
            return false;
        }

        $data = array_map("json_decode", $data);
        $total_sales = 0;
        $total_collections = 0;

        foreach ($data as $order_data){
            $order_key = $order_data->tracking_number.' ('.$order_data->status.')';

            foreach ($order_data->tat as $status => $tat){
                $history[$status] = $tat->date;
            }

            $order_info[$order_key]['history'] = array_flip(array_sort($history));
            $order_info[$order_key]['breakdown'] = [
                'subtotal' => $order_data->subtotal,
                'shipping' => $order_data->shipping,
                'tax' => $order_data->tax,
                'fee' => $order_data->fee,
                'insurance' => $order_data->insurance,
                'discount' => $order_data->discount,
                'total' => $order_data->total
            ];
            $order_info[$order_key]['fees'] = [
                'shipping_fee' => $order_data->shipping_fee,
                'insurance_fee' => $order_data->insurance_fee,
                'transaction_fee' => $order_data->transaction_fee
            ];

            $total_collections = $total_collections + $order_data->total;
            $total_sales = $total_sales + array_sum($order_info[$order_key]['fees']);
        }

        $order_info['total collections'] = $total_collections;
        $order_info['total sales'] = $total_sales;

        return $order_info;
    }

}