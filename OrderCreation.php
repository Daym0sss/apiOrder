<?php

class OrderCreation
{
    private $client_id = "bb-test";
    private $client_secret = "3c5141aa-5626-4913-a4dd-3c7bdd790543";
    public function __construct()
    {

    }

    private function getToken()
    {

        $ch = curl_init();
        curl_setopt_array($ch, [
           CURLOPT_URL => 'https://account-test.bbunion.ru/auth/realms/bbunion/protocol/openid-connect/token',
           CURLOPT_POST => true,
           CURLOPT_POSTFIELDS => "grant_type=client_credentials&client_id=$this->client_id&client_secret=$this->client_secret",
           CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded'],
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_SSL_VERIFYPEER => false
        ]);

        $token = json_decode(curl_exec($ch))->{'access_token'};

        curl_close($ch);

        return $token;
    }

    private function tokenExpired()
    {
        $ch = curl_init();

        curl_setopt_array($ch, [
           CURLOPT_URL => 'https://api-test.bbunion.ru/v1/lkui/orders',
           CURLOPT_RETURNTRANSFER => true,
           CURLOPT_SSL_VERIFYPEER => false,
           CURLOPT_HTTPHEADER => ["Authorization: Bearer " . $_SESSION['access_token']]
        ]);

        curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);

        if ($headers['http_code'] == 200)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public function createOrder($fields)
    {
        session_start();
        if (!isset($_SESSION['access_token']))
        {
            $_SESSION['access_token'] = $this->getToken();
        }
        else
        {
            if ($this->tokenExpired())
            {
                $_SESSION['access_token'] = $this->getToken();
            }
        }

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://api-test.bbunion.ru/v1/lkui/orders',
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $fields,
            CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Authorization: Bearer " . $_SESSION['access_token']],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);

        curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);

        if ($headers['http_code'] == 201)
        {
            echo "Order has been created successfully <br><br>";
            echo "<a href='index.html'>Get back to order creation page</a>";
        }
        else if ($headers['http_code'] >= 400 && $headers['http_code'] < 500)
        {
            echo "Problems with request body parameters<br><br>";
            echo "<a href='index.html'>Get back to order creation page</a>";
        }

    }
}