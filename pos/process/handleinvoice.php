<?php
session_start();
$transactions = $_SESSION['transactions'] ?? [];
$invoiceData = json_decode($_SESSION['invoiceData'] ?? '{}', true);
$products = $invoiceData['products'] ?? [];

$customername = htmlspecialchars($invoiceData['customerName'] ?? '-');
$customerMobile = htmlspecialchars($invoiceData['customerMobile'] ?? '-');
$salesman = htmlspecialchars($invoiceData['salesman'] ?? '-');
$totalQty = number_format($invoiceData['totalQty'] ?? 0);
$totalSelling = number_format($invoiceData['totalSelling'] ?? 0, 2);
$finalDiscount = number_format($invoiceData['finalDiscount'] ?? 0, 2);
$netAmount = number_format($invoiceData['netAmount'] ?? 0, 2);
$payableAmount = number_format($invoiceData['payableAmount'] ?? 0, 2);
$invoiceNo = htmlspecialchars($invoiceData['invoiceNo'] ?? 'S888/2526/XXX');
$date = date('M d Y H:i:s');











?>
