<?php
    require 'OrderCreation.php';

    //change it right in the code in senderPointCode and pickupPointCode for successful creation
    $tempPointCodeForCreation = "70130010";

    $order = [
        "trackNumber" => $_POST['track_number'],
        "partnerOrderId" => $_POST['partner_order_id'],
        "declaredCost" => (int)($_POST['declared_cost']),
        "paymentAmount" => (int) ($_POST['payment_amount']),
        "cashOnDeliverySum" => (int) ($_POST['cash_on_delivery_sum']),
        "deliveryAcceptanceActNumber" => $_POST['delivery_acceptance_act_number'],
        "senderData" =>
        [
            "phone" => $_POST['sender_phone'],
            "email" => $_POST['sender_email'],
            "fullName" => $_POST['sender_fullName'],
            "passport" => $_POST['sender_passport'],
            "senderPointCode" => $tempPointCodeForCreation //$_POST['sender_point_code']
        ],
        "recipientData" =>
        [
            "phone" => $_POST['recipient_phone'],
            "email" => $_POST['recipient_email'],
            "fullName" => $_POST['recipient_fullName'],
            "pickupPointCode" => $tempPointCodeForCreation //$_POST['recipient_pickup_point_code']
        ],
        "products" => [],
        "boxesData" => []
    ];

    $productNumber = "productNumber";
    $productName = "productName";
    $productNds = "productNds";
    $productPrice = "productPrice";
    $productQuantity = "productQuantity";

    $boxBarcode = "boxBarcode";
    $boxWeight = "boxWeight";
    $boxSizeX = "boxSizeX";
    $boxSizeY = "boxSizeY";
    $boxSizeZ = "boxSizeZ";

    $counter = 1;

    while (isset($_POST[$productNumber . $counter]))
    {
        $order['products'] []= [
            "number" => $_POST[$productNumber . $counter],
            "name" => $_POST[$productName . $counter],
            "nds" => (int) ($_POST[$productNds . $counter]),
            "price" => (int) ($_POST[$productPrice . $counter]),
            "quantity" => (int) ($_POST[$productQuantity . $counter]),
        ];

        $order['boxesData'] []= [
            "barcode" => $_POST[$boxBarcode . $counter],
            "boxWeight" => (int) ($_POST[$boxWeight . $counter]),
            "sizeX" => (int) ($_POST[$boxSizeX . $counter]),
            "sizeY" => (int) ($_POST[$boxSizeY . $counter]),
            "sizeZ" => (int) ($_POST[$boxSizeZ . $counter]),
        ];

        $counter++;
    }

    $handler = new OrderCreation();
    $handler->createOrder(json_encode($order));
