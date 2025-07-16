let cart = [];
$(function () {

    function getBarcode(callback) {
        $("#inputbarcode").on("keypress", function (e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const barcodeValue = $(this).val().trim();
                if (barcodeValue === '') {
                    showError("This field is required.");
                    return;
                }

                hideError();
                callback(barcodeValue);

            }
        });
    }

    function showError(message) {
        $("#barcodeError").text(message).show();
    }

    function hideError() {
        $("#barcodeError").hide();
    }

    function appendProductToTable(data) {
        const price = parseFloat(data.mrp || 0);
        let existingRow = $(`.products-table tbody tr[data-barcode="${data.barcode}"]`);
        let cartItem = cart.find(item => item.barcode === data.barcode);

        if (existingRow.length > 0) {
            let qtyCell = existingRow.find(".qty-cell");
            let qty = parseInt(qtyCell.text()) + 1;
            qtyCell.text(qty);
            existingRow.find(".product-total").text((qty * price).toFixed(2));

            if (cartItem) cartItem.qty += 1;
        } else {

            const rowHtml = `
            <tr data-barcode="${data.barcode}" class="bg-light">
            <td>
            <button type="button" class="btn btn-link text-danger remove-btn p-0" style="min-width: 24px;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
            stroke-width="1.5" stroke="currentColor"
            class="size-6" style="color: red;">
            <path stroke-linecap="round" stroke-linejoin="round"
            d="M14.74 9L14.394 18m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                            </svg>
                            </button>
                            </td>
                            <td><img src="${data.image || 'imgassets/no-img.jpg'}" alt="No Image" width="50"></td>
                            <td>
                        <strong>${data.style_no || '-'}</strong><br>
                        <small>SKU: ${data.sku || data.style_no + '-' + data.color + '-' + data.size}</small><br>
                        <small>Barcode: ${data.barcode}</small>
                    </td>
                    <td class="qty-cell">1</td>
                    <td>${price.toFixed(2)}</td>
                    <td>
                    <select class="form-select form-select-sm offer-select">
                    <option value="">Select Option</option>
                    
                    </select>
                    </td>
                    <td class="product-total">${price.toFixed(2)}</td>
                        </tr>
                        `;
            $(".products-table tbody").append(rowHtml);

            cart.push({
                barcode: data.barcode,
                name: data.style_no || '-',
                color: data.color || '-',
                size: data.size || '-',
                sku: (data.style_no || '-') + '-' + (data.color || '-') + '-' + (data.size || '-'),
                qty: 1,
                price: price,
                offer: ''
            });
        }

        updateGrandTotal();
        calculateSummary();
    }

    function updateGrandTotal() {
        let grandTotal = 0;
        $(".product-total").each(function () {
            grandTotal += parseFloat($(this).text());
        });
        $("#payableAmount").text(grandTotal.toFixed(2));
    }

    function applySelectedOffer() {
        const selectedOffer = document.getElementById("offerSelect").value;

        $(".products-table tbody tr").each(function () {
            const $row = $(this);
            const barcode = String($row.data("barcode"));
            const item = cart.find(i => String(i.barcode) === barcode);
            if (item) {
                item.offer = selectedOffer;
                $row.find(".offer-select").val(selectedOffer); // Row-wise dropdown update
            }
        });

        calculateSummary();
    }


    function calculateSummary() {
        let totalQty = 0;
        let totalSelling = 0;
        let totalDiscount = 0;
        let taxableAmount = 0;
        let netAmount = 0;

        let allItems = [];

        cart.forEach(item => {
            totalQty += item.qty;
            totalSelling += item.qty * item.price;
            for (let i = 0; i < item.qty; i++) {
                allItems.push({
                    price: item.price,
                    itemRef: item
                });
            }
        });

        const selectedOffer = document.getElementById("offerSelect").value;

        if (selectedOffer === "B1T2|B2T5|B4T10") {
            let freeItems = 0;

            if (totalQty >= 10) freeItems = 6;
            else if (totalQty >= 5) freeItems = 3;
            else if (totalQty >= 2) freeItems = 1;

            allItems.sort((a, b) => a.price - b.price); // Smallest items free
            totalDiscount = allItems.slice(0, freeItems).reduce((sum, i) => sum + i.price, 0);

        } else if (selectedOffer === "B1@40%off") {
            totalDiscount = cart.reduce((sum, item) => {
                return sum + ((item.price * 40 / 100) * item.qty);
            }, 0);
        }

        netAmount = totalSelling - totalDiscount;

        // Proportional tax logic after discount:
        let groupedByGst = { 5: 0, 12: 0 };

        cart.forEach(item => {
            let gstRate = item.price <= 999 ? 5 : 12;
            groupedByGst[gstRate] += item.qty * item.price;
        });

        let totalBeforeDiscount = groupedByGst[5] + groupedByGst[12];
        let discountRatio = totalBeforeDiscount > 0 ? (totalDiscount / totalBeforeDiscount) : 0;

        taxableAmount = 0;
        Object.entries(groupedByGst).forEach(([rate, value]) => {
            let afterDiscount = value - (value * discountRatio);
            let base = afterDiscount / (1 + rate / 100);
            taxableAmount += base;
        });

        const roundOff = Math.round(netAmount) - netAmount;
        const payableAmount = Math.round(netAmount);

        $("#totalQty").text(totalQty);
        $("#totalSelling").text(totalSelling.toFixed(2));
        $("#productDiscount").text(totalDiscount.toFixed(2));
        $("#finalDiscount").text(totalDiscount.toFixed(2));
        $("#netAmount").text(netAmount.toFixed(2));
        $("#taxableAmount").text(taxableAmount.toFixed(2));
        $("#rounding").text(roundOff.toFixed(2));
        $("#payableAmount").text(payableAmount.toFixed(2));
    }

    $(document).on("change", "#offerSelect", function () {
        const selectedOffer = $(this).val();
        console.log("Selected Offer:", selectedOffer);

        cart.forEach(item => {
            item.offer = selectedOffer;
        });

        calculateSummary();
    });


    $(document).on("click", ".remove-btn, .remove-btn svg", function (e) {
        e.preventDefault();

        const $row = $(this).closest("tr");
        const barcode = String($row.data("barcode"));
        const cartItemIndex = cart.findIndex(item => String(item.barcode) === barcode);

        console.log("Cart index found:", cartItemIndex);

        if (cartItemIndex !== -1) {
            const item = cart[cartItemIndex];

            if (item.qty > 1) {
                item.qty -= 1;
                $row.find(".qty-cell").text(item.qty);
                $row.find(".product-total").text((item.qty * item.price).toFixed(2));
            } else {
                cart.splice(cartItemIndex, 1);
                $row.remove();
            }

            updateGrandTotal();
            calculateSummary();
        }
    });



    getBarcode(function (barcode) {
        $.ajax({
            url: '/pos/process/actions.php',
            method: 'POST',
            data: { barcode: barcode },
            success: function (response) {
                try {
                    const data = typeof response === 'string' ? JSON.parse(response) : response;
                    if (data.error) {
                        showError(data.error);
                    } else {
                        hideError();
                        appendProductToTable(data);
                        $("#inputbarcode").val('').focus();
                        console.log("Barcode data received:", data);
                    }
                } catch (e) {
                    showError("Invalid product data.");
                }
            },
            error: function () {
                showError("Server error. Please try again.");
            }
        });
    });

    window.applySelectedOffer = applySelectedOffer;
});


document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("paymentForm");
    if (!form) {
        console.error("Form with ID 'paymentForm' not found.");
        return;
    }

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const selectedOffer = document.getElementById("offerSelect").value;

        const products = cart.map(item => {
            
            let discount = 0;

            if (selectedOffer === "B1@40%off") {
                discount = (item.price * 0.4) * item.qty;
            } else if (selectedOffer === "B1T2|B2T5|B4T10") {
                const totalQty = cart.reduce((sum, i) => sum + i.qty, 0);
                const allTotal = cart.reduce((sum, i) => sum + (i.qty * i.price), 0);
                const ratio = allTotal > 0 ? (item.qty * item.price) / allTotal : 0;

                const totalDiscount = parseFloat(document.getElementById("finalDiscount").textContent) || 0;
                discount = ratio * totalDiscount;
            }

            const value = (item.price * item.qty) - discount;
            const gst = value <= 999 ? 5 : 12;

            return {
                barcode: item.barcode,
                productName: item.name,
                color: item.color,
                size: item.size,
                sku: (item.name || '-') + '-' + (item.color || '-') + '-' + (item.size || '-'),
                qty: item.qty,
                price: item.price,
                gst: gst,
                discount: parseFloat(discount.toFixed(2)),
                value: parseFloat(value.toFixed(2))
            };
        });

        const invoiceDetails = {
            customerName: document.getElementById("customerName").value,
            customerMobile: document.getElementById("customerMobile").value,
            salesman: document.getElementById("salesman").value,
            totalQty: document.getElementById("totalQty").textContent,
            totalSelling: document.getElementById("totalSelling").textContent,
            finalDiscount: document.getElementById("finalDiscount").textContent,
            productDiscount: document.getElementById("productDiscount").textContent,
            netAmount: document.getElementById("netAmount").textContent,
            taxableAmount: document.getElementById("taxableAmount").textContent,
            rounding: document.getElementById("rounding").textContent,
            payableAmount: document.getElementById("payableAmount").textContent,
            offerApplied: selectedOffer,
            couponCode: document.getElementById("coupnecode").value,
            products: products
        };

        document.getElementById("invoiceData").value = JSON.stringify(invoiceDetails);
        console.log("Submitting invoice:", invoiceDetails);

        this.submit();
    });
});
