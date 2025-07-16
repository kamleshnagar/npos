<!-- Bootstrap Modal -->



<div class="modal fade " id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" style="max-width: 800px;">
        <div class="modal-content">


            <!-- Begin Modal Body -->
            <div class="modal-body" id="invoiceContent">
                <!-- ---------------------------------------------------------------------------------------------- -->



                <div id="invoice-content" style="width:100%;">
                    <style>
                        .invoice-modal {
                            font-family: 'Segoe UI', sans-serif;
                            font-size: 14px;
                            font-weight: 500;
                            color: #000;
                            margin: 0px;
                            padding: 0px;
                            width: 100%;
                        }

                        /* .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 1px;
            border: 1px solid #ffffff;
        } */

                        .dashed {
                            border-top: 3px dashed #4747479d;
                            margin: 10px 0;
                        }

                        .invoice-title {
                            font-weight: bold;
                        }

                        .barcode {
                            font-size: 18px;
                            font-weight: bold;
                            text-align: center;
                            padding: 10px 0;
                        }

                        .table th {
                            border-bottom: 3px solid #4747479d;
                            background-color: white;
                            color: black;
                            font-weight: 700;
                        }

                        .table td {
                            vertical-align: middle !important;

                            border-bottom: 1px solid #4747474b;
                        }

                        .table td {
                            padding: 6px;
                        }

                        .summary-list li {
                            padding: 6px 12px;
                            border: 1px solid #ddd;
                        }

                        .tax-table td,
                        .tax-table th {
                            padding: 6px;
                            border-bottom: 3px dashed #4747479d;
                        }

                        .footer-note {


                            text-align: center;
                        }

                        .strong {
                            font-weight: bold;
                        }

                        .text-right {
                            text-align: right;
                        }

                        .text-end {
                            text-align: end;
                        }

                        .n-pos {
                            position: absolute;
                            right: 220px;
                        }
                    </style>

                    <?php
                    include 'process/db.php';
                    // session_start();

                    $result = mysqli_query($conn, "SELECT id FROM invoices ORDER BY id DESC LIMIT 1");
                    $row = mysqli_fetch_assoc($result);
                    $lastInvoiceID = $row['id'] ?? 0;

                    // Optional: Create a custom formatted invoice number
                    $invoiceNo = "S888/2526/" . str_pad($lastInvoiceID + 1, 3, '0', STR_PAD_LEFT);

                    $transactions = $_SESSION['transactions'] ?? [];
                    $invoiceData = json_decode($_SESSION['invoiceData'] ?? '{}', true);
                    $products = $invoiceData['products'] ?? [];

                    $customername = strtoupper(htmlspecialchars($invoiceData['customerName'] ?? '-'));
                    $customerMobile = htmlspecialchars($invoiceData['customerMobile'] ?? '-');
                    $salesman = htmlspecialchars($invoiceData['salesman'] ?? '-');
                    $totalQty = number_format($invoiceData['totalQty'] ?? 0);
                    $totalSelling = $invoiceData['totalSelling'] ?? 0;
                    $finalDiscount = $invoiceData['finalDiscount'] ?? 0;
                    $netAmount = $invoiceData['netAmount'] ?? 0;
                    $payableAmount = $invoiceData['payableAmount'] ?? 0;
                    $invoiceNo = htmlspecialchars($invoiceData['invoiceNo'] ?? $invoiceNo);
                    $date = date('M d Y H:i:s');
                    $offerApplied = $invoiceData['offerApplied'];




                    ?>


                    <div class="invoice-modal">

                        <h5 class="text-center mt-3"><strong>Retail Invoice</strong></h5>

                        <div class="text-center mb-3">
                            AXE<br>
                            AXE_AMD_VASTRAL<br>
                            <strong>LACONIC ENTERPRISE PRIVATE LIMITED</strong><br>
                            24AADCL3990Q2ZV<br>
                        </div>
                        GF-8, SatvaIcon., S.P.Ring Road, Vastral, Ahmedabad, 382418 Gujarat, India

                        <div class="dashed"></div>

                        <div class="row mb-3">
                            <div class="col-12 d-flex justify-content-between">
                                <div>Invoice No:</div>
                                <div><strong><?= $invoiceNo ?></strong></div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <div> Customer Name:</div>
                                <div><?= $customername ?></div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <div>Mobile:</div>
                                <div> <?= $customerMobile ?></div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <div>Date:</div>
                                <div> <?= $date ?></div>
                            </div>
                            <div class="col-12 d-flex justify-content-between">
                                <div>Sales Person:</div>
                                <div><strong><?= $salesman ?></strong></div>
                            </div>

                        </div>


                        <div class="dashed"></div>

                        <div class="barcode">
                            <svg id="invoiceBarcode"></svg>
                        </div>
                        <div class="dashed"></div>
                        <table class="table ">
                            <thead class="">
                                <tr>
                                    <th>SNO</th>
                                    <th>Item</th>
                                    <th>MRP</th>
                                    <th>QTY</th>
                                    <th>Disc</th>
                                    <th>HSN</th>
                                    <th>Amount</th>

                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                $sno = 1;
                                echo print_r($invoiceNo);
                                foreach ($products as $item) {
                                    $barcode = htmlspecialchars($item['barcode']);
                                    $name = htmlspecialchars($item['productName']);
                                    $color = htmlspecialchars($item['color']);
                                    $size = htmlspecialchars($item['size']);
                                    $sku = htmlspecialchars($item['sku']);
                                    $qty = (int)$item['qty'];
                                    $price =  (float)$item['price'];
                                    $discount =  (float)$item['discount'];
                                    $value = (float)$item['value'];
                                    echo '<pre>';
                                    echo print_r($value);
                                    echo '</pre>';
                                    $gst = (float)$item['gst'];
                                    $tax =  $value * ($gst / 100);
                                    $taxable = $value - $tax;

                                    $total = $value * $qty;
                                    $totalTax =  number_format(($tax * $qty), 2);
                                    $cat = substr($name, 0, 2);
                                    if ($cat === "SH") {
                                        $hsn = 62052000;
                                    } else if ($cat === "TR" || $cat === "JN") {
                                        $hsn = 62034200;
                                    } else if ($cat === "TS") {
                                        $hsn = 61091000;
                                    }

                                    if (!isset($_SESSION['InsertSelesData']) || $_SESSION['InsertSelesData'] !== true) {



                                        $InsertSelesData = "INSERT INTO `sales` (`customer_name`, `customer_mo`, `invoice_no`, `date`, `barcode`, `article_no`, `color`, `size`, `qty`, `mrp`, `discount`, `taxable`, `tax`, `value`) VALUES
                                         ('$customername', '$customerMobile', '$invoiceNo', current_timestamp(), '$barcode', '$name', '$color', '$size','$qty', '$price', '$discount', '$taxable', '$tax', '$total')";
                                        $result = mysqli_query($conn, $InsertSelesData);
                                    }

                                    echo
                                    "<tr>
                                        <td class='text-center'>{$sno}</td>
                                        <td >{$name}<br>{$sku}</td>
                                        <td  class='text-center'>{$price}</td>
                                        <td class='text-center'>{$qty}</td>
                                        <td  class='text-center'>{$discount}</td>
                                        <td  class='text-center'>{$hsn}</td>
                                        <td>
                                            <div  class='text-center'>{$taxable}</div>
                                            <div  class='text-center'>Tax:$tax</div>
                                        </td>

                                        <td  class='text-center'>$total</td>
                                    </tr>";
                                    $sno++;
                                }
                                $_SESSION['InsertSelesData'] = true;

                                ?>

                            </tbody>
                        </table>
                        <?php

                        $gst5Total = $gst12Total = 0;
                        foreach ($products as $item) {
                            $gst = (float)$item['gst'];
                            $value = (float)$item['value'];
                            $qty = (int)$item['qty'];
                            $totalTax = $value * ($gst / 100) * $qty;
                            if ($gst == 5.0) {
                                $gst5Total += $totalTax;
                            } elseif ($gst == 12.0) {
                                $gst12Total += $totalTax;
                            }
                        }
                        $totalTax = $gst12Total + $gst5Total;
                        $totalTaxable = $payableAmount - $totalTax;

                        if (!isset($_SESSION['InsertInvoiceData']) || $_SESSION['InsertInvoiceData'] !== true) {

                            $InsertInvoiceData = "INSERT INTO `invoices` ( `invoice_no`, `date`, `customer_name`, `customer_mo`, `salesman`, `qty`, `total_mrp`, `total_discount`, `payable_amount`, `selected_offer`) VALUES ('$invoiceNo',  current_timestamp(), '$customername', '$customerMobile', '$salesman', '$totalQty', '$totalSelling', '$finalDiscount', '$payableAmount', '$offerApplied')";
                            $result = mysqli_query($conn, $InsertInvoiceData);
                            $invoiceId = mysqli_insert_id($conn);

                            $_SESSION['InsertInvoiceData'] = true;
                        }


                        ?>


                        <div class="row d-flex flex-column">
                            <div class="col-12">
                                <div class="d-flex justify-content-between">
                                    <div><strong>Total Quantity:</strong></div>
                                    <div><strong><?= number_format($totalQty, 2) ?></strong></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><strong>Total Discount:</strong></div>
                                    <div><strong>₹<?= number_format($finalDiscount, 2) ?></strong></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><strong>Total Taxable Amount:</strong></div>
                                    <div><strong>₹<?= number_format($totalTaxable, 2) ?></strong></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><strong>Total Tax:</strong></div>
                                    <div><strong>₹<?= number_format($totalTax, 2) ?></strong></div>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <div><strong>Total Payable:</strong></div>
                                    <div><strong> ₹<?= number_format($payableAmount, 2) ?></strong></div>
                                </div>
                                <div class="dashed"></div>
                            </div>
                            <div class="col-12 text-end">
                                <?php
                                $hasTransaction = false;
                                foreach ($transactions as $txn) {
                                    if ($txn['amount'] > 0) {
                                        $hasTransaction = true;
                                        echo "<strong>" . ucfirst($txn['mode']) . ": ₹" . number_format($txn['amount'], 2) . "</strong><br>";
                                    }
                                }

                                ?>


                                <div class="dashed"></div>
                            </div>
                        </div>



                        <div class="text-center mb-2">
                            <strong>Tax Breakup</strong>
                            <div class="dashed"></div>
                        </div>



                        <table class="table table-sm text-center tax-table">
                            <thead class="table-light">
                                <tr>
                                    <th>Tax %</th>
                                    <th>SGST %</th>
                                    <th>CGST %</th>
                                    <th>SGST Amount</th>
                                    <th>CGST Amount</th>
                                    <th>Tax Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($gst5Total > 0): ?>
                                    <tr>
                                        <td>5%</td>
                                        <td>2.5%</td>
                                        <td>2.5%</td>
                                        <td>₹<?= number_format($gst5Total / 2, 2) ?></td>
                                        <td>₹<?= number_format($gst5Total / 2, 2) ?></td>
                                        <td>₹<?= number_format($gst5Total, 2) ?></td>
                                    </tr>
                                <?php endif; ?>

                                <?php if ($gst12Total > 0): ?>
                                    <tr>
                                        <td>12%</td>
                                        <td>6%</td>
                                        <td>6%</td>
                                        <td>₹<?= number_format($gst12Total / 2, 2) ?></td>
                                        <td>₹<?= number_format($gst12Total / 2, 2) ?></td>
                                        <td>₹<?= number_format($gst12Total, 2) ?></td>
                                    </tr>
                                <?php endif; ?>

                            </tbody>

                        </table>

                        <div class="footer">
                            <div class="footer-note">
                                <strong>Thank you! Visit again!</strong><br>
                            </div>
                            <p>*All prices are tax-inclusive *</p>
                            <p> *Exchange accepted within 7 days of purchase on original condition.*</p>
                        </div>
                    </div>
                    <script>
                        // Pass PHP invoice number to JavaScript
                        const invoiceNo = "<?= $invoiceNo ?>";

                        // Render barcode using JsBarcode
                        JsBarcode("#invoiceBarcode", invoiceNo, {
                            format: "CODE128",
                            lineColor: "#000",
                            width: 2,
                            height: 80,
                            displayValue: true
                        });
                    </script>


                </div>


                <!-- ---------------------------------------------------------------------------------------------- -->





                <div class="modal-footer">
                    <!-- // i want this print button paste the invoice inside this modal -->
                    <script>
                        function printInvoice() {
                            const printContent = document.getElementById('invoice-content').innerHTML;
                            const originalContent = document.body.innerHTML;

                            document.body.innerHTML = printContent;
                            window.print();
                            document.body.innerHTML = originalContent;

                            // Optional: reload to restore event listeners/styles
                            location.reload();
                        }
                    </script>
                    <button class="btn btn-primary" style="width:150px;" onclick="printInvoice()">Print</button>
                    <button class=" btn btn-secondary" style="width:150px;"><a href="sales.php" class="text-decoration-none btn btn-secondary" style="width:150px;">Exit</a></button>
                </div>
            </div>
        </div>
    </div>

    <?php

    ?>