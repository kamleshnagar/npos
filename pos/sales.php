<?php
include 'components/_header.php';

session_start();
session_destroy();
session_start();

include 'process/functions.php';

?>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const inputs = ["customerMobile", "customerName", "salesman"];

        inputs.forEach((id, index) => {
            const input = document.getElementById(id);
            if (input) {
                input.addEventListener("keydown", function(e) {
                    if (e.key === "Enter") {
                        e.preventDefault();
                        const nextInput = document.getElementById(inputs[index + 1]);
                        if (nextInput) {
                            nextInput.focus();
                        }
                    }
                });
            }
        });
    });
</script>


<?php
include 'components/_sidebar.php';
?>
<!-- Main Content -->

<form id="paymentForm" method="POST" action="/pos/process/actions.php">
    <!-- Store Header -->


    <!-- Input Section -->
    <div class="input-section m-2">
        <div class="input-row">
            <div class="input-group d-flex flex-nowrap">
                <input id="customerMobile" name="customerMobile" type="number" class="form-input" placeholder="Cust Mob No" required>
                <button class="search-button">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
            </div>
            <p id="mobieError" class="form-error text-danger" style="display: none;">Enter a valid 10-digit number</p>

            <div class="input-group d-flex flex-nowrap" required>
                <input id="customerName" name="customerName" type="text" class="form-input" placeholder="Cust Name" required>
            </div>


        </div>
        <div class="input-row">
            <div class="input-group selection-box" required>
                <select id="salesman" name="salesman" class="form-select">
                    <option value="" disabled selected>Sales Person</option>
                    <option value="AXE VASTRAL">AXE VASTRAL</option>
                    <option value="KAMLESH NAGAR">KAMLESH NAGAR</option>
                    <option value="HARESH VISHWAKARMA">HARESH VISHWAKARMA</option>
                </select>
            </div>

            <div class="input-group d-flex flex-nowrap">
                <input id="inputbarcode" name="inputbarcode" type="text" class="form-input" placeholder="Item Barcode scan">
                <div class="barcodebutton">
                    <button class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </div>
            </div>
            <p id="barcodeError" class="form-error" style="display:none;"></p>
        </div>

        <div class="button-row d-flex justify-content-center align-items-center p-2">
            <div class="action-buttons">
                <button class="btn btn-primary">PRODUCTS</button>
                <button class="btn btn-danger">RETURN</button>
            </div>
            <div class="action-buttons">
                <button class="btn btn-secondary">CALCULATOR</button>
                <button class="btn btn-outline">BILL ON HOLD</button>
            </div>
        </div>
    </div>

    <!-- Main Layout -->
    <div class="d-flex">
        <!-- Products Table -->
        <div class="products-section m-2">
            <table class="products-table">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>Image</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Discount / Offers</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Products will be added dynamically by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Summary Panel -->
        <div class="summary-panel m-2">
            <h2>Summary</h2>
            <div class="summary-content">

                <!-- Offer Dropdown -->
                <select id="offerSelect" name="offerSelect" class="form-select form-select-sm offer-select">
                    <option value="">Select Option</option>
                    <option value="B1@40%off">B1@40%off</option>
                    <option value="B1T2|B2T5|B4T10">B1T2|B2T5|B4T10</option>
                </select>
                <div class="summary-row discount-row m-0 p-0">
                    <label for="offerSelect">Discount Coupon</label><br>
                    <div class="input-group d-flex flex-nowrap m-0 p-0">
                        <input id="coupnecode" name="coupnecode" type="text" class="form-input" placeholder="Input Coupne Code">
                        <div class="barcodebutton">
                            <button class="search-button">Apply</button>
                        </div>
                    </div>
                </div>

                <!-- Summary Table -->
                <table class="summary-table table-auto w-full text-left text-sm mt-4">
                    <tbody>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Total Quantity</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="totalQty" class="text-gray-600 text-right">0</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Total Selling Price</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="totalSelling" class="text-gray-600 text-right">0.00</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Final Discount</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="finalDiscount" class="text-gray-600 text-right">0.00</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Product Discoun</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="productDiscount" class="text-gray-600 text-right">0.00</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Return Amount</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td class="text-gray-600 text-right">0.00</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Net Amount</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="netAmount" class="text-gray-600 text-right">0.00</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Taxable Amount</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="taxableAmount" class="text-gray-600 text-right">0.00</td>
                        </tr>
                        <tr class="border-b py-2">
                            <td class="text-gray-700">Rounding Off</td>
                            <td class="text-gray-700 text-center">:</td>
                            <td id="rounding" class="text-gray-600 text-right">0.00</td>
                        </tr>
                    </tbody>
                </table>

                <!-- Payment Section -->
                <div class="d-flex flex-column justify-content-center align-items-center mt-4">
                    <div class="payable-amount d-flex mb-3">
                        <label class="me-2">Payable Amount:</label>
                        <span id="payableAmount">0.00</span>
                    </div>
                    <div class="summary-buttons">
                        <input type="hidden" name="invoiceData" id="invoiceData">
                        <button type="submit" class="btn btn-success text-light">PAY BILL</button>
                        <button class="btn btn-outline btn-hold-1">HOLD BILL</button>

                    </div>
                </div>
            </div>
        </div>
    </div>





</form>
<script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>

<script>
    $(document).ready(function () {
        const errorMsg = $('#mobieError');

        $('#customerMobile').on("keyup", function () {
            const mobile = $(this).val().trim();

            if (mobile.length !== 10 || isNaN(mobile)) {
                errorMsg.show().text('Enter a valid 10-digit number');
                $('#customerName').val('');
            } else {
                errorMsg.hide();
            }
        });

        $('#customerMobile').on('keydown', function (e) {
            if (e.key === 'Enter' || e.keyCode === 13) {
                e.preventDefault();

                const mobile = $(this).val().trim();

                if (mobile.length === 10 && !isNaN(mobile)) {
                    // AJAX request to get customer name
                    $.ajax({
                        url: 'process/get_customer.php',
                        method: 'POST',
                        data: { mobile: mobile },
                        success: function (response) {
                            $('#customerName').val(response.trim()).focus();
                        },
                        error: function () {
                            console.error('Error fetching customer');
                            $('#customerName').val('');
                        }
                    });
                }
            }
        });
    });
</script>




<!-- External JS -->
<?php include 'components/_footer.php'; ?>