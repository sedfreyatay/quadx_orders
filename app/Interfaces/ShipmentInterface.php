<?php

namespace App\Interfaces;

interface ShipmentInterface
{
    /**
     * Present data in a usable JSON object
     * @param $params
     * @return mixed
     */
    public function present_data($params);

    /**
     * Format object to get needed values
     * @param $params
     * @return mixed
     */
    public function format_object($params);
}