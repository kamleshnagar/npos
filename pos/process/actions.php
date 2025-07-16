<?php
session_start();
header('Content-Type: application/json');

include 'functions.php';



$_SESSION['errors'] = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['barcode'])) {

    file_put_contents("invoice_debug.txt", print_r($_POST, true));
    // Handle barcode scan
    if (isset($_POST['barcode'])) {
        $barcode = trim($_POST['barcode']);
        $product = findProductByBarcode($conn, $barcode);

        if ($product) {
            echo json_encode($product);
        } else {
            echo json_encode(["error" => "Inventory or product not available."]);
        }

        // Handle invoice submission
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['invoiceData'])) {

    $invoiceDataJson = $_POST['invoiceData'];

    $invoiceData = json_decode($invoiceDataJson, true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(["error" => "Invalid JSON in invoice data."]);
        exit;
    }
    print_r($_POST);

    $_SESSION['invoiceData'] = $_POST['invoiceData'];
    header('location:../paymentmode.php');
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['paymentMode'], $_POST['amount'])) {
    $mode = $_POST['paymentMode'];
    $amount = (float)$_POST['amount'];
    $auth = 'Manual';
    $status = 'Success';

    // Add the transaction
    $txn = [
        'mode' => $mode,
        'amount' => $amount,
        'auth' => $auth,
        'status' => $status
    ];

    $_SESSION['transactions'][] = $txn;

    // Recalculate collected amount
    $collectedAmount = 0;
    foreach ($_SESSION['transactions'] as $t) {
        $collectedAmount += (float)$t['amount'];
    }

    // Get payable amount
    $invoiceData = json_decode($_SESSION['invoiceData'], true);
    $payableAmount = (float)$invoiceData['payableAmount'];

    // Redirect based on payment completion
    if ($collectedAmount >= $payableAmount) {
         $_SESSION['PaymentStatus']= true;
         header('Location: /pos/paymentmode.php');
        exit;
    } else {
        
        header('Location: /pos/paymentmode.php');
        exit;
    }
}
