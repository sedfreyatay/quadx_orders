<?php

namespace App\Http\Controllers;

use Illuminate\Http\Requests;
use App\Interfaces\ShipmentInterface as ShipmentInterface;
use App\Interfaces\GuzzleHttpInterface as GuzzleHttpInterface;
use App\Interfaces\DataPresentationInterface as DataPresentationInterface;


class ShipmentController extends Controller
{

    private $shipment;
    private $http_request;
    private $present;
    private $timezone = 'Asia/Manila';

    /**
     * OrderController constructor.
     * @param ShipmentInterface $shipment
     * @param GuzzleHttpInterface $http_request
     */
    public function __construct(
        ShipmentInterface $shipment,
        GuzzleHttpInterface $http_request,
        DataPresentationInterface $present )
    {
        $this->shipment = $shipment;
        $this->http_request = $http_request;
        $this->present = $present;
    }

    /**
     *
     */
    public function processShipment()
    {
        $source_uri = 'https://api.staging.lbcx.ph/v1/orders/';

        $items = [
            '0077-0424-NSHE',
            '0077-0516-VBTW',
            '0077-0522-QAYC',
            '0077-0526-EBDW',
            '0077-0836-PEFL',
            '0077-1456-TESV',
            '0077-6478-DMAR',
            '0077-6490-VNCM',
            '0077-6491-ASLK',
            '0077-6495-AYUX'
        ];

        $headers = ['X-Time-Zone' => $this->timezone];
        $params = compact('source_uri', 'headers', 'items');

        //send out http requests and get the json object of shipments
        $shipments = $this->http_request->send_concurrent_request($params);

        //format the json object to the desired format
        $shipments = $this->shipment->format_object($shipments);

        //print them out in a nice looking data
        $this->present->present_data($shipments);
    }
}