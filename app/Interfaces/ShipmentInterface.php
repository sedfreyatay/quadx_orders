<?php

namespace App\Interfaces;

interface ShipmentInterface
{

    /**
     * Format object to get needed values
     * @param $params
     * @return mixed
     */
    public function format_object($params);
}