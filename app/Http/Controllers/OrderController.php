<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getaccesstoken(){
        $config = array(
            'ShopUrl' => env('SHOPIFY_SHOP'),
            'ApiKey' => env('SHOPIFY_API_KEY'),
            'Password' => env('SHOPIFY_API_SECRET'),
        );
        \PHPShopify\ShopifySDK::config($config);
        $scopes = 'write_orders';
        $redirectUrl = 'http://127.0.0.1:8000/getaccesstoken';
        $accessToken = \PHPShopify\AuthHelper::createAuthRequest($scopes);
        var_dump($accessToken, 'accesstoken');
    }

    public function createOrder(){
        $config = array(
            'ShopUrl' => env('SHOPIFY_SHOP'),
            'AccessToken' => env('SHOPIFY_ACCESS_TOKEN'),
        );
        $shopify = new \PHPShopify\ShopifySDK($config);
        $order =  array(
            'email'=>'abansal2107@yopmail.com', 
            'fulfillment_status'=>'fulfilled',
            'fulfillments'=>[
                [
                    'location_id'=>67720741082
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

    public function webhooks_recived_order(){

        $webhook_payload_cnt = file_get_contents('php://input');
        Log::info('Log message   Order webhooks recived order', ['context_url' => "shopify webhooks", 'context_response' => $webhook_payload_cnt]);
        
    }
}
