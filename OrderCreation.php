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
        $result = $this->sendRequest('https://account-test.bbunion.ru/auth/realms/bbunion/protocol/openid-connect/token', 'POST', ['Content-Type: application/x-www-form-urlencoded'], null, "grant_type=client_credentials&client_id=$this->client_id&client_secret=$this->client_secret");

        return $result['data']->{'access_token'};
    }

    private function sendRequest($url, $method, $headers = null, $params = null, $body = null)
    {
        $ch = curl_init();

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

        $data = curl_exec($ch);
        $headers = curl_getinfo($ch);
        curl_close($ch);

        $result = [];
        $result['data'] = json_decode($data);
        $result['headers'] = $headers;

        return $result;
    }

    public function createOrder($fields)
    {
        if (!session_id())
        {
            session_start();
        }

        if (!isset($_SESSION['access_token']))
        {
            $_SESSION['access_token'] = $this->getToken();
        }

        $result = $this->sendRequest("https://api-test.bbunion.ru/v1/lkui/orders", "POST", ["Content-Type: application/json", "Authorization: Bearer " . $_SESSION['access_token']], null, $fields);

        if ($result['headers']['http_code'] == 201)
        {
            echo "Order has been created successfully <br><br>";
            echo "<a href='index.html'>Get back to order creation page</a>";
        }
        else if ($result['headers']['http_code'] == 401)
        {
            $_SESSION['access_token'] = $this->getToken();
            $this->createOrder($fields);
        }
        else
        {
            echo "Problems with request body parameters<br><br>";
            echo "<a href='index.html'>Get back to order creation page</a>";
        }
    }
}