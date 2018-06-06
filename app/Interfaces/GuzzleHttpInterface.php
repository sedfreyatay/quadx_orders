<?php

namespace App\Interfaces;

interface GuzzleHttpInterface
{
    /**
     * Send multiple requests concurrently
     * @param $params
     * @return mixed
     */
    public function send_concurrent_request($params);
}