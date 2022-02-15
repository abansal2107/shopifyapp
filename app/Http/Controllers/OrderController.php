<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

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
        $url = URL::to('/'); 
        $redirectUrl = $url . '/getaccesstoken';
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
        $output = json_decode($webhook_payload_cnt);
        
        $result = "\n\n" . "Order Id : " . $output->{'id'} . "\n\n" . "Shopify Id :  " . $output->{'app_id'} . "\n\n" . "Order Date : "  . $output->{'created_at'} . "\n\n" . "Amount : " . $output->{'current_subtotal_price'} . "\n\n" ;
        
        $text = print_r($result,true);
        file_put_contents($output->{'id'} . '.txt', $text, TRUE);
        
        Log::info('Log message   Order webhooks recived order', ['context_url' => "shopify webhooks", 'context_response' => $result]);
        die('end');
    }
}
