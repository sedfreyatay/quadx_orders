<?php

namespace App\Interfaces;

interface OrderInterface{

    /**
     * Get Orders from API source
     * @param $params
     * @return mixed
     */
    public function get_orders($params);

    /**
     * Present orders in a usable JSON object
     * @param $params
     * @return mixed
     */
    public function present_orders($params);
}