<?php

namespace App\Interfaces;

interface DataPresentationInterface
{

    /**
     * Present data in a specific form
     * @param $params
     * @return mixed
     */
    public function present_data($data);
}