<?php

namespace App\Http\Controllers;

use Illuminate\Http\Requests;
use App\Interfaces\OrderInterface as OrderInterface;

class OrderController extends Controller
{

    private $order;

    /**
     * OrderController constructor.
     * @param OrderInterface $order
     */
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    /**
     *
     */
    public function orderTransformer()
    {
        $orders = [
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

        $orders = $this->order->get_orders($orders);
        $orders = $this->order->format_orders($orders);
        $this->order->present_orders($orders);
    }
}