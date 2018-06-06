<?php

namespace App\Repositories;

use App\Interfaces\GuzzleHttpInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class GuzzleHttpRepository implements GuzzleHttpInterface
{
    /**
     * send multiple requests concurrently
     * @param $params
     * @return array
     */
    public function send_concurrent_request($params)
    {
        $headers = [];
        $source_uri = '';

        extract($params);

        if (empty($items) || empty($source_uri)) {
            return [];
        }

        $client = new Client(['base_uri' => $source_uri, 'headers' => $headers]);

        foreach ($items as &$item) {
            $item = $client->getAsync($item);
        }

        try {
            // Wait on all of the requests to complete. Throws a ConnectException
            // if any of the requests fail
            $results = Promise\unwrap($items);
        } catch (ConnectException $e) {
            return header('Bad Request', true, 400);
        }

        foreach ($results as &$result) {
            $result = $result->getBody()->getContents();
        }

        return $results;
    }
}