<?php $this->load->view('layout/header'); ?>

<div class="content-wrapper">
    <section class="content-header">
        <h1 class="text-center">Edit Pricing for <?= htmlspecialchars($company->company_name) ?></h1>
    </section>

    <section class="content">
        <div id="alert-message"></div>

        <div class="card card-info">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Product Pricing List</h3>
                <div>
                    <label class="mb-0">
                        <input type="checkbox" id="select_all_products"> Select All
                    </label>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <?php foreach ($products as $product): 
                        $checked = isset($pricing[$product->id]);
                        $price = $checked ? $pricing[$product->id]['price'] : '';
                    ?>
                        <div class="col-md-3">
                            <div class="product-card card p-2 text-center">
                                <img src="<?= base_url('assets/product_images/'.$product->product_image) ?>" 
                                     width="80" height="80" 
                                     class="mx-auto d-block" 
                                     alt="<?= $product->name ?>">

                                <h6><?= $product->name ?></h6>
                                <label>
                                    <input type="checkbox" class="product-checkbox" data-product-id="<?= $product->id ?>" <?= $checked ? 'checked' : '' ?>>
                                    Select
                                </label>
                                <input type="hidden" class="product-id-hidden" value="<?= $product->id ?>">
                                <input type="number" step="any" 
                                       class="form-control mt-2 price-input" 
                                       placeholder="Enter Price"
                                       value="<?= $price ?>" 
                                       <?= $checked ? '' : 'disabled' ?>>
                                <input type="text" 
                                       class="form-control mt-2 uom-input" 
                                       placeholder="Enter UOM (e.g., Kg, Ltr)" 
                                       value="<?= $checked && isset($pricing[$product->id]['uom']) ? $pricing[$product->id]['uom'] : '' ?>" 
                                       <?= $checked ? '' : 'disabled' ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card-footer">
                <button type="button" id="savePricingBtn" class="btn btn-primary">Save Pricing</button>
                <a href="<?= base_url('clients') ?>" class="btn btn-secondary float-right">Back</a>
            </div>
        </div>
    </section>
</div>

<!-- Custom Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-check-circle" style="font-size: 60px; color: #28a745;"></i>
                </div>
                <h4 class="mb-3" style="color: #28a745;">SUCCESS!</h4>
                <p id="successMessage" class="mb-4" style="font-size: 16px;">Pricing updated successfully.</p>
                <button type="button" class="btn btn-success btn-lg px-4" data-dismiss="modal" id="successOkBtn">Ok, got it!</button>
            </div>
        </div>
    </div>
</div>

<!-- Custom Error Modal -->
<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-4">
                <div class="mb-3">
                    <i class="fas fa-exclamation-circle" style="font-size: 60px; color: #dc3545;"></i>
                </div>
                <h4 class="mb-3" style="color: #dc3545;">ERROR!</h4>
                <p id="errorMessage" class="mb-4" style="font-size: 16px;"></p>
                <button type="button" class="btn btn-danger btn-lg px-4" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectAll = document.getElementById('select_all_products');
    const checkboxes = document.querySelectorAll('.product-checkbox');
    const saveBtn = document.getElementById('savePricingBtn');
    
    // Function to toggle inputs
    function toggleInputs(checkbox, enabled) {
        const card = checkbox.closest('.product-card');
        const priceInput = card.querySelector('.price-input');
        const uomInput = card.querySelector('.uom-input');
        
        if (priceInput) priceInput.disabled = !enabled;
        if (uomInput) uomInput.disabled = !enabled;
    }
    
    // Enable/Disable for each product
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            toggleInputs(this, this.checked);
        });
    });
    
    // Select All toggle
    if (selectAll) {
        selectAll.addEventListener('change', function () {
            const isChecked = selectAll.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
                toggleInputs(checkbox, isChecked);
            });
        });
    }
    
    // Show success modal
    function showSuccessModal(message) {
        const successMessageEl = document.getElementById('successMessage');
        if (successMessageEl) {
            successMessageEl.textContent = message;
        }
        $('#successModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    
    // Show error modal
    function showErrorModal(message) {
        const errorMessageEl = document.getElementById('errorMessage');
        if (errorMessageEl) {
            errorMessageEl.textContent = message;
        }
        $('#errorModal').modal({
            backdrop: 'static',
            keyboard: false
        });
    }
    
    // Save button click handler
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            const companyId = <?= $company->id ?>;
            const pricingData = {};
            let selectedCount = 0;
            let missingPrices = [];
            
            // Collect all selected products
            checkboxes.forEach(checkbox => {
                if (checkbox.checked) {
                    const card = checkbox.closest('.product-card');
                    const productId = checkbox.getAttribute('data-product-id');
                    const priceInput = card.querySelector('.price-input');
                    const uomInput = card.querySelector('.uom-input');
                    const price = priceInput ? priceInput.value.trim() : '';
                    const uom = uomInput ? uomInput.value.trim() : '';
                    
                    if (!price) {
                        missingPrices.push(productId);
                    } else {
                        selectedCount++;
                        pricingData[productId] = {
                            product_id: productId,
                            price: parseFloat(price),
                            uom: uom
                        };
                    }
                }
            });
            
            if (selectedCount === 0) {
                showErrorModal('Please select at least one product to save pricing.');
                return;
            }
            
            if (missingPrices.length > 0) {
                showErrorModal('Please enter price for selected products (IDs: ' + missingPrices.join(', ') + ')');
                return;
            }
            
            // Disable button and show loading
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving...';
            
            // Create form data for AJAX
            const formData = new FormData();
            formData.append('company_id', companyId);
            formData.append('pricing_data', JSON.stringify(pricingData));
            formData.append('<?= $this->security->get_csrf_token_name(); ?>', '<?= $this->security->get_csrf_hash(); ?>');
            
            // Send AJAX request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= base_url("clients/save_pricing_ajax") ?>', true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        
                        if (response.success) {
                            // Show success modal
                            showSuccessModal(response.message);
                            
                            // Update the displayed pricing data
                            updateDisplayedPricing(pricingData);
                            
                            // Re-enable button
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = 'Save Pricing';
                            
                            // Add event listener for modal close to optionally reload
                            $('#successModal').on('hidden.bs.modal', function (e) {
                                // Optional: reload page or stay as is
                                // location.reload(); // Uncomment if you want to reload
                            });
                        } else {
                            showErrorModal(response.message);
                            saveBtn.disabled = false;
                            saveBtn.innerHTML = 'Save Pricing';
                        }
                    } catch (e) {
                        console.error("Error parsing response:", e);
                        showErrorModal('Error saving pricing. Please check console.');
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = 'Save Pricing';
                    }
                } else {
                    showErrorModal('Error saving pricing. Status: ' + xhr.status);
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = 'Save Pricing';
                }
            };
            xhr.onerror = function() {
                showErrorModal('Network error. Please try again.');
                saveBtn.disabled = false;
                saveBtn.innerHTML = 'Save Pricing';
            };
            xhr.send(formData);
        });
    }
    
    // Function to update displayed pricing without page reload
    function updateDisplayedPricing(pricingData) {
        Object.keys(pricingData).forEach(productId => {
            const checkbox = document.querySelector(`.product-checkbox[data-product-id="${productId}"]`);
            if (checkbox) {
                const card = checkbox.closest('.product-card');
                const priceInput = card.querySelector('.price-input');
                const uomInput = card.querySelector('.uom-input');
                const productData = pricingData[productId];
                
                // Update values
                if (priceInput) priceInput.value = productData.price;
                if (uomInput) uomInput.value = productData.uom || '';
                
                // Ensure checkbox is checked
                if (!checkbox.checked) {
                    checkbox.checked = true;
                    toggleInputs(checkbox, true);
                }
            }
        });
    }
});
</script>

<style>
/* Custom Modal Styles */
.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

.modal-body {
    padding: 2rem;
}

.modal-body i {
    animation: scaleIn 0.3s ease-out;
}

@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

.btn-success, .btn-danger {
    border-radius: 50px;
    padding: 10px 30px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-success:hover, .btn-danger:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.modal.fade .modal-dialog {
    transform: scale(0.8);
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: scale(1);
}
</style>

<?php $this->load->view('layout/footer'); ?>