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

    public function tokenExpired()
    {
       $http_code = $this->sendRequest("https://api-test.bbunion.ru/v1/lkui/orders", "GET", null, null, null, true);

        if ($http_code == 200)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    private function sendRequest($url, $method, $headers = null, $params = null, $body = null, $tokenCheck = false)
    {
        if (!session_id())
        {
            session_start();
        }

        if (!$tokenCheck)
        {
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
        }


        $ch = curl_init();

        $headers []= "Authorization: Bearer " . $_SESSION['access_token'];

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        switch($method)
        {
            case "POST":
            {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

                break;
            }
            case "GET":
            {
                if ($params)
                {
                    $url_params = http_build_query($params);
                    curl_setopt($ch, CURLOPT_URL, $url . "?" . $url_params);
                }

                break;
            }
            case "PUT":
            {
                foreach ($params as $value)
                {
                    $url .= "/" . $value;
                }
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                break;
            }
            default:
                return "This method is not supported";
        }

        curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);
        return $headers['http_code'];
    }

    public function createOrder($fields)
    {
        $http_code = $this->sendRequest("https://api-test.bbunion.ru/v1/lkui/orders", "POST", ["Content-Type: application/json"], null, $fields);

        if ($http_code == 201)
        {
            echo "Order has been created successfully <br><br>";
            echo "<a href='index.html'>Get back to order creation page</a>";
        }
        else if ($http_code >= 400 && $http_code < 500)
        {
            echo "Problems with request body parameters<br><br>";
            echo "<a href='index.html'>Get back to order creation page</a>";
        }
    }
}