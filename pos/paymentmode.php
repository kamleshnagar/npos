<?php
session_start();


// if (isset($_SESSION['invoiceData']) && $_SESSION['invoiceData']['totalQty'] > 0) {



$invoicedata = json_decode(($_SESSION['invoiceData']), true);
$payableAmount =  $invoicedata['payableAmount'];

if (isset($payableAmount) && $payableAmount > 0) {
    include 'process/functions.php';
    include 'components/_header.php';
    include 'components/_sidebar.php';
    


    if (!isset($_SESSION['transactions'])) {
        $_SESSION['transactions'] = [];
    }

    // Calculate collected amount
    $collectedAmount = 0;
    foreach ($_SESSION['transactions'] as $txn) {
        $collectedAmount += (float)$txn['amount'];
    }

    $remainingAmount = $payableAmount - $collectedAmount;




?>


    <!-- Main Content -->


    <!-- Store Header -->

    <form action="process/actions.php" method="POST" class="">
        <div class=" w-100 p-4 store-header">
            <h1><b>Payment</b></h1>
        </div>


        <div class="container my-5">

            <!-- Summary Boxes -->
            <div class="d-flex  justify-content-center text-center">
                <div class="col-md-2 col-4">
                    <div class="bg-white py-3 shadow-sm">

                        <h1 class="mb-1 fs-2 mb-3"><?= number_format($payableAmount, 0, '.', ''); ?></h1>
                        <p class="mb-0 text-muted ">Total Amount</p>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white py-3 shadow-sm">
                        <h1 class="mb-1 fs-2 mb-3"><?= number_format($collectedAmount, 0, '.', ''); ?></h1>
                        <p class="mb-0 text-muted ">Collected Amount</p>
                    </div>
                </div>
                <div class="col-md-2 col-4">
                    <div class="bg-white py-3 shadow-sm">
                        <h1 class="mb-1 text-danger fs-2 mb-3"><?= number_format($remainingAmount, 0, '.', ''); ?></h1>
                        <p class="mb-0 text-danger  ">Remaining Amount</p>
                    </div>
                </div>
            </div>
            <!-- Payment Method Buttons -->



            <div class="row mt-3 justify-content-center">
                <div class="col-md-6 d-flex flex-wrap gap-3 justify-content-center" role="group">
                    <input type="radio" class="btn-check" name="paymentMode" id="cash" value="CASH" autocomplete="off" checked>
                    <label class="btn btn-indigo" for="cash">Cash</label>

                    <input type="radio" class="btn-check" name="paymentMode" id="card" value="CARD" autocomplete="off">
                    <label class="btn btn-indigo" for="card">Card</label>

                    <input type="radio" class="btn-check" name="paymentMode" id="upi" value="UPI" autocomplete="off">
                    <label class="btn btn-indigo" for="upi">UPI</label>

                    <input type="radio" class="btn-check" name="paymentMode" id="credit" value="CREDIT" autocomplete="off">
                    <label class="btn btn-indigo" for="credit">Credit Note</label>

                    <input type="radio" class="btn-check" name="paymentMode" id="voucher" value="VOUCHER" autocomplete="off">
                    <label class="btn btn-indigo" for="voucher">Gift Voucher</label>

                    <input type="radio" class="btn-check" name="paymentMode" id="loyalty" value="LOYALTY" autocomplete="off">
                    <label class="btn btn-indigo" for="loyalty">Loyalty Points</label>
                </div>
            </div>
        </div>

        <div class=" my-2 mx-4 ">
            <label for="amount" class="">Amount</label>
            <input type="text" name="amount" class="w-100 rounded py-1 p-2 amount">
        </div>


        <?php

        if (isset($_SESSION['PaymentStatus']) &&  $_SESSION['PaymentStatus'] === true) {

            echo '
        </form>
        <div class="d-flex justify-content-center align-items-center gap-2 my-4">
       <button  class="btn btn-success payment-btn text-light px" data-bs-toggle="modal" data-bs-target="#invoiceModal">
  Print
</button>
        <button class="btn  payment-btn border" style="background-color:white; border: 1px solid grey;"><a href="sales.php" class="text-dark text-decoration-none ">Back to Sale</a></button>
        </div>
        ';
        } else {
            echo '
          <div class="d-flex justify-content-center align-items-center gap-2 my-4">
          <button type="submit" class="btn btn-success payment-btn text-light px-3" style=" width:150px;">Accept Payment</button>
        <button class="btn  payment-btn border" style="background-color:white; border: 1px solid grey; width:150px;"><a href="sales.php" class="text-dark text-decoration-none ">Back to Sale</a></button>
        </div>
        </form>
        ';
        }
        ?>


        <div class="m-4 bg-white d-flex flex-column justify-content-center align-items-center p-5  trs_detail  ">
            <div class="w-100 text-center mt-3 mb-2">Transactoion Detail</div>
            <div class=" table-payment bg-white d-flex justify-content-evenly">
                <table class="w-100 ">
                    <thead class="w-100">
                        <tr class="p-2">
                            <th>Payment Mode</th>
                            <th>Amount</th>
                            <th>Authorization</th>
                            <th>Status</th>
                        </tr>

                    </thead>
                    <tbody class="">
                        <?php if (!empty($_SESSION['transactions'])): ?>
                            <?php foreach ($_SESSION['transactions'] as $txn): ?>
                                <tr>
                                    <td><?= htmlspecialchars($txn['mode']) ?></td>
                                    <td><?= number_format($txn['amount'], 2) ?></td>
                                    <td><?= $txn['auth'] ?></td>
                                    <td><span class=""><?= $txn['status'] ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No transaction</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>


    <?php




    include 'invoice_modal.php';




    include 'components/_footer.php';
}
else{
    header('location:sales.php');
}
    ?>