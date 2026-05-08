<?php
$products = $products ?? []; // Pass products from controller
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Order</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
   <style>
        .card {
            max-width: 100%;
            width: 100%;
            margin: auto;
        }
        .table td, .table th {
            vertical-align: middle;
            text-align: center;
        }
        .action-buttons > * {
            margin: 0 2px;
        }
    </style>

</head>

<body>

<main class="main-wrapper py-4">
    <div class="container">
        <div class="card shadow">
            <div class="card-header bg-light">
                <h5 class="mb-0">Purchase Order</h5>
            </div>
            <div class="card-body">
              
                <table class="table table-bordered align-middle" id="product-table">
                    <thead class="table-secondary">
                       
                        <tr>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>HSN Code</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>GST</th>
                            <th>Subtotal</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="product-rows">
                            <tr>
                                <td>
                                    <select class="form-control product-name" onchange="updatePriceAndTotal(this)">
                                        <option value="">Select Product</option>
                                        <?php foreach ($products as $product): ?>
                                           <option value="<?= $product->id ?>" 
                                                data-price="<?= $product->price ?>" 
                                                data-description="<?= $product->description ?>" 
                                                data-hsn="<?= $product->hsn ?>">
                                            <?= $product->name ?>
                                        </option>

                                        <?php endforeach; ?>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                              <td><input type="number" class="form-control quantity" style="width: 80px;" value="1" min="1" onchange="updateTotal(this)"></td>
                                <td><span class="price">₹0</span></td>
                                <td><span class="total">₹0</span></td>
                                <td><span class="gst">₹0</span></td>
                                <td><span class="subtotal">₹0</span></td>
                                <td class="text-center d-flex gap-2">
                                    <button type="button" class="btn text-light btn-primary btn-sm" onclick="addRow(this)">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="text-end">
                        <button class="btn btn-primary" onclick="submitOrder()">Save Order</button>
                    </div>
                </div>
            </div>
        </div>
</main>
<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let productData = <?= json_encode($products); ?>;
    function addRow(afterBtn) {
        const newRow = `
            <tr>
                <td>
                    <select class="form-control product-name" onchange="updatePriceAndTotal(this)">
                        <option value="">Select Product</option>
                        ${productData.map(product => `
                        <option value="${product.id}" 
                            data-price="${product.price}" 
                            data-description="${product.description}" 
                            data-hsn="${product.hsn}">
                            ${product.name}
                        </option>
                    `).join('')}
                    </select>
                </td>
                <td></td>
                <td></td>
                <td><input type="number"   style="width: 80px;" class="form-control quantity" value="1" min="1" onchange="updateTotal(this)"></td>
                <td><span class="price">₹0</span></td>
                <td><span class="total">₹0</span></td>
                <td><span class="gst">₹0</span></td>
                <td><span class="subtotal">₹0</span></td>
                <td class="text-center d-flex gap-2">
                    <button type="button" class="btn text-light btn-primary btn-sm" onclick="addRow(this)">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRow(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
        $(afterBtn).closest('tr').after(newRow);
    }

    function deleteRow(btn) {
        $(btn).closest('tr').remove();
    }

    function updatePriceAndTotal(select) {
    const row = $(select).closest('tr');
    const selected = $(select).find('option:selected');

    const price = selected.data('price') || 0;
    const desc = selected.data('description') || '';
    const hsn = selected.data('hsn') || '';

    row.find('.price').text('₹' + price);
    row.find('td:eq(1)').text(desc); // Description column
    row.find('td:eq(2)').text(hsn);  // HSN column
    row.find('.quantity').val(1);

    updateTotal(row.find('.quantity')[0]);
}


    function updateTotal(input) {
        const row = $(input).closest('tr');
        const price = parseFloat(row.find('.price').text().replace('₹', '')) || 0;
        const qty = parseInt($(input).val()) || 1;
        const total = price * qty;
        const gst = total * 0.05;
        const subtotal = total + gst;

        row.find('.total').text('₹' + total.toFixed(2));
        row.find('.gst').text('₹' + gst.toFixed(2));
        row.find('.subtotal').text('₹' + subtotal.toFixed(2));
    }

    function submitOrder() {
        let orderItems = [];

        $('#product-rows tr').each(function () {
            const productId = $(this).find('.product-name').val();
            const price = parseFloat($(this).find('.price').text().replace('₹', '')) || 0;
            const qty = parseInt($(this).find('.quantity').val()) || 1;

            if (productId) {
                orderItems.push({
                    product_id: productId,
                    qty: qty,
                    price: price
                });
            }
        });

        if (orderItems.length === 0) {
            alert("Please select at least one product.");
            return;
        }

        $.ajax({
            url: "<?= base_url('cartcontroller/request_order') ?>",
            type: "POST",
            data: { items: orderItems },
            dataType: "json",
            success: function (response) {
                alert(response.message);
                if (response.status === 'success') {
                    location.reload();
                }
            },
            error: function () {
                alert("Error submitting order.");
            }
        });
    }
</script>

</body>
</html>