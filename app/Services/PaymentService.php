<?php

namespace App\Services;
use Illuminate\Support\Facades\Http;

class PaymentService
{
    
    public function processPayment(array $data)
    {
       
        $post_data = json_encode($data);
        
        $curlHandlerOptin = curl_init();

        curl_setopt_array($curlHandlerOptin, [
            CURLOPT_URL => 'https://fakedata.nanocorp.io/api/payment/create',
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_POST  => true,
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',  
            ),
          
        ]);
        
        $responseOptin = curl_exec($curlHandlerOptin);
        curl_close($curlHandlerOptin);

        $jsonOptin = json_decode($responseOptin);

        $createResult = array($jsonOptin->result);

        if ($createResult[0] == 'success') {

            // Handle the payment intend was successful.
            $result = collect($jsonOptin->data);

            $intendData = array(
                'payment_intend' => $result['payment_intend'],
            );
              
            $intend_post_data = json_encode($intendData);
            
            $curlHandlerOptinIntend = curl_init();
    
            curl_setopt_array($curlHandlerOptinIntend, [
                CURLOPT_URL => 'https://fakedata.nanocorp.io/api/payment/confirm',
                CURLOPT_RETURNTRANSFER => true,
                CURLINFO_HEADER_OUT => true,
                CURLOPT_POST  => true,
                CURLOPT_POSTFIELDS => $intend_post_data,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                   
                ),
              
            ]);
            
            $responseOptinIntend = curl_exec($curlHandlerOptinIntend);
            curl_close($curlHandlerOptinIntend);
    
            $jsonOptinIntend = json_decode($responseOptinIntend);

            $confirmResult = array($jsonOptinIntend->result);

            return $confirmResult[0];

        }else{

            $msg = 'error';
            return $msg; 

        }
       
    }
}