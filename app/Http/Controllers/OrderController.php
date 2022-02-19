<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Config;

class OrderController extends Controller
{
    public function getaccesstoken()
    {
        $config = config('shopify.config');
        \PHPShopify\ShopifySDK::config($config);
        $scopes = 'write_orders';
        $url = URL::to('/');
        $redirectUrl = $url . '/getaccesstoken';
        $accessToken = \PHPShopify\AuthHelper::createAuthRequest($scopes);
        var_dump($accessToken, 'accesstoken');
    }

    public function createOrder()
    {
        $config = config('shopify.config');
        $shopify = new \PHPShopify\ShopifySDK($config);
        $order =  array(
            'email' => 'abansal2107@yopmail.com',
            'fulfillment_status' => 'fulfilled',
            'fulfillments' => [
                [
                    'location_id' => 67720741082
                ]
            ],
            'line_items' => [
                [
                    'variant_id' => 42387422707930,
                    'quantity' => 1
                ]
            ]
        );

        $result = $shopify->Order->post($order);
        var_dump($result);
    }


    public function refundOrder()
    {
        $config = config('shopify.config');
        $order_id = 4679025393882;
        $shopify = new \PHPShopify\ShopifySDK($config);
        $data =  array(
            "id" =>4679025393882,
            "currency" => "INR",
            "notify" => true,
            "note" => "Wrong Size",
            "kind" => "refund",
            "shipping" => [
                "full_refund" => true
            ],
            "refund_line_items" => [["line_item_id" => 12011944673498, "quantity" => 1, "restock_type" => "return", "location_id" => "67720741082"]],
            // "transactions" => [["amount" => 350.00, "kind" => "refund", "gateway" => "store-credit", "status" => "success", "source_name" => "My API"]]
        );

        $result = $shopify->Order($order_id)->Refund->post($data);
        var_dump($result);
    }

    public function webhooks_recived_order()
    {

        $webhook_payload_cnt = file_get_contents('php://input');
        $output = json_decode($webhook_payload_cnt);

        $result = "\n\n" . "Order Id : " . $output->{'id'} . "\n\n" . "Shopify Id :  " . $output->{'app_id'} . "\n\n" . "Order Date : "  . $output->{'created_at'} . "\n\n" . "Amount : " . $output->{'current_subtotal_price'} . "\n\n";

        $text = print_r($result, true);
        file_put_contents($output->{'id'} . '.txt', $text, TRUE);

        Log::info('Log message   Order webhooks recived order', ['context_url' => "shopify webhooks", 'context_response' => $result]);
        die('end');
    }

    public function webhooks_recieved_refund()
    {

        $webhook_payload_cnt = file_get_contents('php://input');
        $output = json_decode($webhook_payload_cnt);

        $result = "\n" . "Refund Id : " . $output->{'id'} . "\n" . "Order Id :  " . $output->{'order_id'} . "\n" . "Refund Date : "  . $output->{'created_at'} . "\n" . "Refund Note : " . $output->{'note'} . "\n";

        $text = print_r($result, true);
        file_put_contents('Refund' . $output->{'id'} . '.txt', $text, TRUE);

        Log::info('Log message   Refund webhooks recieved', ['context_url' => "shopify webhooks", 'context_response' => $result]);
        die('end');
    }
}
