<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;
?>
<style type="text/css"> 

/* Add these styles to your existing CSS */

/* Custom Modal Styles */
.quantity-modal .modal-dialog {
    max-width: 400px;
}

.quantity-input-lg {
    font-size: 18px;
    padding: 12px;
    height: 50px;
}

/* Toast Notification */
.cart-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 300px;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
    z-index: 9999;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    border-left: 4px solid #28a745;
}

.cart-toast.show {
    transform: translateX(0);
}

.cart-toast .toast-header {
    background: #28a745;
    color: white;
    border-radius: 8px 8px 0 0;
}

.cart-toast .toast-body {
    padding: 15px;
}
    .product-card {
        height: 100%;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        background: white;
        transition: all 0.3s;
    }
    
    .product-card:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .product-img-container {
        height: 150px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 15px;
        background: #f8f9fa;
    }
    
    .product-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid #dee2e6;
    }
    
    .card-body {
        padding: 15px;
    }
    
    .product-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 8px;
        color: #333;
        line-height: 1.4;
    }
    
    .price-tag {
        font-size: 18px;
        font-weight: bold;
        color: #28a745;
        margin: 10px 0;
    }
    
    .card-footer {
        background: #f8f9fa;
        border-top: 1px solid #dee2e6;
        padding: 15px;
    }
    
    .add-to-cart-btn {
        width: 100%;
    }
    
    /* Cart Sidebar */
    .cart-sidebar {
        position: fixed;
        top: 0;
        right: -400px;
        width: 400px;
        height: 100vh;
        background: white;
        box-shadow: -5px 0 15px rgba(0,0,0,0.1);
        transition: right 0.3s ease;
        z-index: 1050;
        overflow-y: auto;
    }
    
    .cart-sidebar.open {
        right: 0;
    }
    
    .cart-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1040;
        display: none;
    }
    
    .cart-overlay.show {
        display: block;
    }
    
    .cart-item {
        border-bottom: 1px solid #dee2e6;
        padding: 10px 0;
    }
    
    .cart-item:last-child {
        border-bottom: none;
    }
    
    /* Floating Cart Button */
    .floating-cart-btn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #28a745;
        color: white;
        border: none;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
        z-index: 1000;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    
    .cart-badge {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    /* Add to your existing CSS */
.order-item {
    border-left: 3px solid #28a745;
    margin-bottom: 10px;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.order-item:hover {
    background-color: #e9ecef;
}

.order-summary-totals {
    background-color: #e3f2fd;
    border-radius: 8px;
    padding: 15px;
    margin-top: 15px;
}

.total-row {
    display: flex;
    justify-content: space-between;
    padding: 5px 0;
    border-bottom: 1px dashed #dee2e6;
}

.total-row:last-child {
    border-bottom: none;
    font-weight: bold;
    color: #28a745;
    font-size: 1.1rem;
}

.grand-total {
    font-size: 1.2rem;
    font-weight: bold;
    color: #28a745;
}
</style>

<!-- Cart Overlay -->
<div class="cart-overlay" id="cartOverlay"></div>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
    <div class="card h-100 border-0 rounded-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">
                <i class="fas fa-shopping-cart mr-2"></i>Your Cart
                <button type="button" class="close text-white" onclick="closeCart()">
                    <span>&times;</span>
                </button>
            </h5>
        </div>
        
        <div class="card-body p-0">
            <div id="cartItemsContainer" style="min-height: 300px;">
                <!-- Cart items will be loaded here -->
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Your cart is empty</p>
                </div>
            </div>
        </div>
        
        <div class="card-footer">
           <!-- <div class="row mb-2">
                <div class="col-6">
                    <strong>Total Items:</strong>
                </div>
                <div class="col-6 text-right">
                    <span id="cartTotalItems">0</span>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-6">
                    <strong>Total Amount:</strong>
                </div>
                <div class="col-6 text-right">
                    <span class="text-success font-weight-bold" id="cartTotalAmount"><?= $currency ?> 0.00</span>
                </div>
            </div>-->
            <div class="row mb-2">
    <div class="col-6"><strong>Total Items:</strong></div>
    <div class="col-6 text-right"><span id="cartTotalItems">0</span></div>
</div>

<div class="row mb-2">
    <div class="col-6"><strong>Taxable Value:</strong></div>
    <div class="col-6 text-right">
        <span id="cartTaxable"><?= $currency ?> 0.00</span>
    </div>
</div>

<div class="row mb-2">
    <div class="col-6"><strong>Total Tax:</strong></div>
    <div class="col-6 text-right">
        <span id="cartTax"><?= $currency ?> 0.00</span>
    </div>
</div>

<div class="row mb-3">
    <div class="col-6"><strong>Grand Total:</strong></div>
    <div class="col-6 text-right">
        <span class="text-success font-weight-bold" id="cartTotalAmount"><?= $currency ?> 0.00</span>
    </div>
</div>
            <button class="btn btn-success btn-block" onclick="submitCart()">
                <i class="fas fa-check mr-1"></i> View Order
            </button>
            <button class="btn btn-outline-secondary btn-block mt-2" onclick="clearCart()">
                <i class="fas fa-trash mr-1"></i> Clear Cart
            </button>
        </div>
    </div>
</div>

<!-- Floating Cart Button -->
<button class="floating-cart-btn" onclick="toggleCart()">
    <i class="fas fa-shopping-cart"></i>
    <span class="cart-badge" id="cartBadge">0</span>
</button>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="<?= base_url('auth') ?>">Home</a></li>
                        <li class="breadcrumb-item"><a href="<?= base_url('purchase') ?>">Purchase</a></li>
                        <li class="breadcrumb-item active">Add with Cart</li>
                    </ol>
                </div>
            </div>
        </section>
        
        <section class="content">
            <div class="container-fluid">
                <!-- Header -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-shopping-cart mr-2"></i>Add Products to Cart
                                </h3>
                                <div class="card-tools">
                                    <a href="<?= base_url('Purchase_request/add') ?>" class="btn btn-sm btn-default">
                                        <i class="fas fa-arrow-left mr-1"></i> Back to Regular Purchase
                                    </a>
                                    <button class="btn btn-sm btn-success" onclick="toggleCart()">
                                        <i class="fas fa-shopping-cart mr-1"></i> View Cart (<span id="headerCartCount">0</span>)
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">Click "Add to Cart" on products below. When done, click the cart button to review and view placed order.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
<!-- Add this in a visible place -->
<div class="card mt-3">
    <div class="card-header bg-info text-white">
        <i class="fas fa-bug mr-2"></i> Debug Tools
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <button onclick="testAddProduct()" class="btn btn-primary btn-block mb-2">
                    <i class="fas fa-plus mr-1"></i> Test Add Real Product
                </button>
            </div>
            <div class="col-md-4">
                <button onclick="debugCart()" class="btn btn-secondary btn-block mb-2">
                    <i class="fas fa-code mr-1"></i> Debug Cart
                </button>
            </div>
            <div class="col-md-4">
                <button onclick="clearLocalStorage()" class="btn btn-danger btn-block mb-2">
                    <i class="fas fa-trash mr-1"></i> Clear Storage
                </button>
            </div>
        </div>
        <div class="mt-2">
            <small class="text-muted">Cart Items: <span id="debugCartCount">0</span></small>
        </div>
    </div>
    <!-- Add this code to your HTML (right after <body> or before </body>) -->

<!-- Quantity Modal -->
<div class="modal fade quantity-modal" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="quantityModalLabel">
                    <i class="fas fa-cart-plus mr-2"></i>Add to Cart
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="modalProductInfo">
                    <!-- Product info will be inserted here -->
                </div>
                <div class="form-group mt-3">
                    <label class="font-weight-bold">Quantity:</label>
                    <input type="number" id="modalQuantity" class="form-control quantity-input-lg" value="1" min="1" max="1000" autofocus>
                    <div class="invalid-feedback" id="quantityError"></div>
                </div>
                <div class="form-group">
                    <label>Remark (Optional):</label>
                    <textarea id="modalRemark" class="form-control" rows="2" placeholder="Add any remarks"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmAddToCart">
                    <i class="fas fa-check mr-1"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="cart-toast" id="cartToast">
    <div class="toast-header">
        <strong class="mr-auto">
            <i class="fas fa-check-circle mr-2"></i>Success
        </strong>
        <button type="button" class="ml-2 mb-1 close text-white" onclick="hideToast()">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="toast-body">
        <h6 id="toastProductName"></h6>
        <p class="mb-1">Quantity: <span id="toastQuantity"></span></p>
        <p class="mb-0">Added to cart successfully</p>
    </div>
</div>
</div>
<!-- Custom Order Confirmation Modal -->
<div class="modal fade" id="orderConfirmModal" tabindex="-1" role="dialog" aria-labelledby="orderConfirmModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="orderConfirmModalLabel">
          <i class="fas fa-shopping-cart mr-2"></i>Confirm Your Order
        </h5>
        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div id="orderSummaryContainer">
          <!-- Order summary will be loaded here via JavaScript -->
          <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
              <span class="sr-only">Loading...</span>
            </div>
            <p class="mt-2">Loading order summary...</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">
          <i class="fas fa-times mr-1"></i>Cancel
        </button>
        <button type="button" class="btn btn-success" id="confirmOrderBtn">
          <i class="fas fa-check mr-1"></i>Place Order
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Loading Modal (for after confirmation) -->
<div class="modal fade" id="processingModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div class="spinner-border text-success mb-3" style="width: 3rem; height: 3rem;" role="status">
          <span class="sr-only">Processing...</span>
        </div>
        <h5>Processing Your Order</h5>
        <p class="text-muted">Please wait while we place your order...</p>
      </div>
    </div>
  </div>
</div>

<!-- Success Modal (optional) -->
<div class="modal fade" id="successModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div class="mb-3">
          <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
        </div>
        <h4 class="text-success">Order Placed Successfully!</h4>
        <p id="successMessage" class="text-muted"></p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-primary" id="viewOrderBtn">View Order</button>
      </div>
    </div>
  </div>
</div>
<script>
// Test with first real product
function testAddProduct() {
    // Get the first product button on the page
    const firstProductBtn = document.querySelector('.add-to-cart-btn');
    if (firstProductBtn) {
        console.log('Testing with first product button:', firstProductBtn);
        addToCart(firstProductBtn);
    } else {
        console.error('No product buttons found');
        Swal.fire('Error', 'No product buttons found on page', 'error');
    }
}

function clearLocalStorage() {
    localStorage.clear();
    cart = [];
    updateCartDisplay();
    Swal.fire('Cleared', 'localStorage cleared and cart reset', 'success');
}

// Update debug count
setInterval(() => {
    document.getElementById('debugCartCount').textContent = cart.length;
}, 1000);
</script>
                <!-- Products Grid -->
                <?php if(empty($productArray)): ?>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                No products available in your pricing catalog.
                            </div>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach ($productArray as $product): ?>
                            <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                                <div class="card product-card h-100">
                                    <div class="product-img-container">
                                        <?php if(!empty($product['product_image'])): ?>
                                            <img src="<?= base_url('assets/product_images/' . $product['product_image']) ?>" 
                                                 class="product-img" 
                                                 alt="<?= htmlspecialchars($product['name']) ?>">
                                        <?php else: ?>
                                            <div class="product-img" style="
                                                background: #e9ecef;
                                                display: flex;
                                                align-items: center;
                                                justify-content: center;
                                                color: #6c757d;
                                            ">
                                                <i class="fas fa-cube fa-2x"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="card-body">
                                        <h6 class="product-title"><?= htmlspecialchars($product['name']) ?></h6>
                                        <p class="text-muted small mb-1">
                                            <strong>Code:</strong> <?= $product['product_code'] ?? 'N/A' ?>
                                        </p>
                                        <p class="text-muted small mb-1">
                                            <strong>UOM:</strong> <?= $product['uom_name'] ?>
                                        </p>
                                        <?php if(!empty($product['category_name'])): ?>
                                            <p class="text-muted small mb-2">
                                                <strong>Category:</strong> <?= $product['category_name'] ?>
                                            </p>
                                        <?php endif; ?>
                                        <div class="price-tag">
                                            <?= $currency ?> <?= number_format($product['price'], 2) ?>
                                        </div>
                                    </div>
                                    <div class="card-footer">
                                        <button class="btn btn-success btn-sm add-to-cart-btn"
        data-product='<?= json_encode($product) ?>'
        onclick="showQuantityModal(this)">
    <i class="fas fa-cart-plus mr-1"></i> Add to Cart
</button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>

<script type="text/javascript">
// Cart data stored in browser memory
let cart = JSON.parse(localStorage.getItem('purchase_cart')) || [];
let currency = '<?= $currency ?>';
let currentProduct = null;

// Debug function
function debugCart() {
    console.log('=== CART DEBUG ===');
    console.log('Cart items:', cart);
    console.log('Cart length:', cart.length);
    console.log('localStorage purchase_cart:', localStorage.getItem('purchase_cart'));
    console.log('==================');
}

// Show quantity modal
function showQuantityModal(button) {
    console.log('=== SHOW QUANTITY MODAL ===');
    
    try {
        // Get product data from button
        const productData = button.getAttribute('data-product');
        currentProduct = JSON.parse(productData);
        
        console.log('Current product:', currentProduct);
        
        if (!currentProduct || !currentProduct.id) {
            console.error('Invalid product data');
            showError('Invalid product data. Please try again.');
            return;
        }
        
        // Update modal content
        const modalContent = `
            <div class="text-center">
                <h5>${currentProduct.name || 'Product'}</h5>
                <p class="text-muted">
                    <strong>Price:</strong> ${currency} ${parseFloat(currentProduct.price || 0).toFixed(2)}
                </p>
                ${currentProduct.product_code ? `<p class="text-muted"><strong>Code:</strong> ${currentProduct.product_code}</p>` : ''}
                ${currentProduct.uom_name ? `<p class="text-muted"><strong>UOM:</strong> ${currentProduct.uom_name}</p>` : ''}
            </div>
        `;
        
        document.getElementById('modalProductInfo').innerHTML = modalContent;
        document.getElementById('modalQuantity').value = 1;
        document.getElementById('modalRemark').value = '';
        document.getElementById('quantityError').textContent = '';
        document.getElementById('modalQuantity').classList.remove('is-invalid');
        
        // Show modal
        $('#quantityModal').modal('show');
        
        // Focus on quantity input
        setTimeout(() => {
            document.getElementById('modalQuantity').focus();
            document.getElementById('modalQuantity').select();
        }, 500);
        
    } catch (error) {
        console.error('Error showing quantity modal:', error);
        showError('Failed to load product information.');
    }
}

// Confirm add to cart
document.getElementById('confirmAddToCart').addEventListener('click', function() {
    console.log('=== CONFIRM ADD TO CART ===');
    
    if (!currentProduct) {
        console.error('No current product selected');
        return;
    }
    
    const quantityInput = document.getElementById('modalQuantity');
    const quantity = parseInt(quantityInput.value) || 0;
    const remark = document.getElementById('modalRemark').value;
    
    console.log('Quantity:', quantity, 'Remark:', remark);
    
    // Validate quantity
    if (isNaN(quantity) || quantity < 1) {
        quantityInput.classList.add('is-invalid');
        document.getElementById('quantityError').textContent = 'Quantity must be at least 1';
        quantityInput.focus();
        return;
    }
    
    if (quantity > 1000) {
        quantityInput.classList.add('is-invalid');
        document.getElementById('quantityError').textContent = 'Maximum quantity is 1000';
        quantityInput.focus();
        return;
    }
    
    // Add to cart
    addProductToCart(currentProduct, quantity, remark);
    
    // Close modal
    $('#quantityModal').modal('hide');
});

// Add product to cart
/*function addProductToCart(product, quantity, remark = '') {
    console.log('Adding to cart:', {
        productId: product.id,
        productName: product.name,
        quantity: quantity,
        remark: remark
    });
    
    // Convert productId to number
    const idToAdd = parseInt(product.id);
    
    // Check if product already in cart
    const existingIndex = cart.findIndex(item => {
        const itemId = parseInt(item.id);
        return itemId === idToAdd;
    });
    
    if (existingIndex > -1) {
        // Update existing item
        const currentQty = parseInt(cart[existingIndex].quantity) || 1;
        cart[existingIndex].quantity = currentQty + quantity;
        if (remark) cart[existingIndex].remark = remark;
        console.log('Updated existing item. New quantity:', cart[existingIndex].quantity);
    } else {
        // Add new item
        const newItem = {
            id: idToAdd,
            name: product.name || 'Unknown Product',
            product_code: product.product_code || '',
            uom_name: product.uom_name || '',
            price: parseFloat(product.price || 0),
            quantity: quantity,
            remark: remark || '',
            product_image: product.product_image || '',
            category_name: product.category_name || '',
            cgst: product.cgst || 9,
            sgst: product.sgst || 9,
            igst: product.igst || 18,
            tax_type: product.tax_type || 'intra'
        };
        
        cart.push(newItem);
        console.log('Added new item:', newItem);
    }
    
    // Save to localStorage
    try {
        localStorage.setItem('purchase_cart', JSON.stringify(cart));
        console.log('Saved to localStorage');
        
        // Verify save
        const saved = JSON.parse(localStorage.getItem('purchase_cart'));
        console.log('Verified save, cart now has:', saved.length, 'items');
    } catch (storageError) {
        console.error('localStorage error:', storageError);
        showError('Failed to save cart. Storage might be full.');
        return;
    }
    
    // Update display
    updateCartDisplay();
    
    // Show success toast
    showSuccessToast(product.name, quantity);
}
*/
function addProductToCart(product, quantity, remark = '') {
    console.log('Adding to cart:', {
        productId: product.id,
        productName: product.name,
        quantity: quantity,
        remark: remark
    });
    
    // Convert productId to number
    const idToAdd = parseInt(product.id);
    
    // Get ALL product data including UOM and tax fields
    const uom_id = product.uom_id || null;
    const uom_uom = product.uom_uom || ''; // Unit like 'PCS', 'BOX', etc.
    const uom_name = product.uom_name || '';
    
    // Check if product already in cart
    const existingIndex = cart.findIndex(item => {
        const itemId = parseInt(item.id);
        return itemId === idToAdd;
    });
    
    if (existingIndex > -1) {
        // Update existing item
        const currentQty = parseInt(cart[existingIndex].quantity) || 1;
        cart[existingIndex].quantity = currentQty + quantity;
        if (remark) cart[existingIndex].remark = remark;
        console.log('Updated existing item. New quantity:', cart[existingIndex].quantity);
    } else {
        // Add new item with ALL required fields
        const newItem = {
            id: idToAdd,
            name: product.name || 'Unknown Product',
            product_code: product.product_code || '',
            // UOM fields - REQUIRED
            uom_id: uom_id,
            uom_uom: uom_uom,
            uom_name: uom_name,
            // Price and quantity
            price: parseFloat(product.price || 0),
            quantity: quantity,
            remark: remark || '',
            // Product info
            product_image: product.product_image || '',
            category_name: product.category_name || '',
            // Tax fields
            cgst: parseFloat(product.cgst || 0),
            sgst: parseFloat(product.sgst || 0),
            igst: parseFloat(product.igst || 0),
            tax_id: product.tax_id || null,
            tax_type: product.tax_type || 'intra'
        };
        
        cart.push(newItem);
        console.log('Added new item with all fields:', newItem);
    }
    
    // Save to localStorage
    try {
        localStorage.setItem('purchase_cart', JSON.stringify(cart));
        console.log('Saved to localStorage');
        
        // Verify save
        const saved = JSON.parse(localStorage.getItem('purchase_cart'));
        console.log('Verified save, cart now has:', saved.length, 'items');
        console.log('First item sample:', saved[0]);
    } catch (storageError) {
        console.error('localStorage error:', storageError);
        showError('Failed to save cart. Storage might be full.');
        return;
    }
    
    // Update display
    updateCartDisplay();
    
    // Show success toast
    showSuccessToast(product.name, quantity);
}

// Show success toast
function showSuccessToast(productName, quantity) {
    document.getElementById('toastProductName').textContent = productName;
    document.getElementById('toastQuantity').textContent = quantity;
    
    const toast = document.getElementById('cartToast');
    toast.classList.add('show');
    
    // Auto hide after 3 seconds
    setTimeout(() => {
        hideToast();
    }, 3000);
}

function hideToast() {
    document.getElementById('cartToast').classList.remove('show');
}

// Show error message
function showError(message) {
    // Create error alert
    const errorAlert = `
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i>${message}
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        </div>
    `;
    
    // Insert at top of content
    const content = document.querySelector('.content');
    if (content) {
        content.insertAdjacentHTML('afterbegin', errorAlert);
    }
}

// Update cart display
function updateCartDisplay() {
    console.log('updateCartDisplay called, cart length:', cart.length);
    
    // Update badge counts
    const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 1), 0);
    
    // Update badges
    const cartBadge = document.getElementById('cartBadge');
    const headerCartCount = document.getElementById('headerCartCount');
    const debugCartCount = document.getElementById('debugCartCount');
    
    if (cartBadge) cartBadge.textContent = totalItems;
    if (headerCartCount) headerCartCount.textContent = totalItems;
    if (debugCartCount) debugCartCount.textContent = cart.length;
    
    // Update cart sidebar
    updateCartSidebar();
}

// Update cart sidebar
function updateCartSidebar() {
    console.log('updateCartSidebar called, cart items:', cart.length);
    
    const container = document.getElementById('cartItemsContainer');
    const totalItemsEl = document.getElementById('cartTotalItems');
    const totalAmountEl = document.getElementById('cartTotalAmount');
    
    if (!container || !totalItemsEl || !totalAmountEl) {
        console.error('Cart sidebar elements not found');
        return;
    }
    
    if (!cart || cart.length === 0) {
        container.innerHTML = `
            <div class="text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Your cart is empty</p>
            </div>
        `;
        totalItemsEl.textContent = '0';
        totalAmountEl.textContent = currency + ' 0.00';
        return;
    }
    
    let html = '';
    let totalItems = 0;
let totalTaxable = 0;
let totalTax = 0;
let grandTotal = 0;

    cart.forEach((item, index) => {
        const itemQty = parseInt(item.quantity) || 1;
        const itemPrice = parseFloat(item.price) || 0;
        const itemTotal = itemPrice * itemQty;
        
        totalItems += itemQty;
      //  totalAmount += itemTotal;
        
// Tax calculation
let taxAmount = 0;

if (item.tax_type === 'intra') {
    const cgst = (item.cgst || 0) / 100;
    const sgst = (item.sgst || 0) / 100;
    taxAmount = itemTotal * (cgst + sgst);
} else {
    const igst = (item.igst || 0) / 100;
    taxAmount = itemTotal * igst;
}

totalTaxable += itemTotal;
totalTax += taxAmount;
grandTotal += (itemTotal + taxAmount);
        html += `
            <div class="cart-item p-3" id="cart-item-${index}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="mb-1">${item.name}</h6>
                        <p class="mb-1 small text-muted">
                            ${currency} ${itemPrice.toFixed(2)} × 
                            <input type="number" 
                                   class="form-control form-control-sm d-inline-block quantity-input" 
                                   value="${itemQty}" 
                                   min="1" 
                                   max="1000"
                                   style="width: 80px;"
                                   data-index="${index}"
                                   onchange="updateCartQuantity(${index}, this.value)">
                        </p>
                        ${item.product_code ? `<p class="mb-1 small"><strong>Code:</strong> ${item.product_code}</p>` : ''}
                        ${item.remark ? `<p class="mb-1 small text-info"><em>${item.remark}</em></p>` : ''}
                        <p class="mb-0 small"><strong>Item Total:</strong> ${currency} ${itemTotal.toFixed(2)}</p>
                    </div>
                    <button class="btn btn-sm btn-outline-danger ml-2" onclick="removeFromCart(${index})" title="Remove item">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        `;
    });
    
    container.innerHTML = html;
   // totalItemsEl.textContent = totalItems;
   // totalAmountEl.textContent = currency + ' ' + totalAmount.toFixed(2);
    
    // Update the cart summary
    totalItemsEl.textContent = totalItems;
    document.getElementById('cartTaxable').textContent = currency + ' ' + totalTaxable.toFixed(2);
    document.getElementById('cartTax').textContent = currency + ' ' + totalTax.toFixed(2);
    document.getElementById('cartTotalAmount').textContent = currency + ' ' + grandTotal.toFixed(2);

    console.log('Cart sidebar updated with', cart.length, 'items');
}

// Update quantity
function updateCartQuantity(index, newQuantity) {
    console.log('Updating quantity for item', index, 'to', newQuantity);
    
    if (newQuantity < 1 || newQuantity > 1000) {
        alert('Quantity must be between 1 and 1000');
        
        // Reset to previous value
        const item = cart[index];
        const input = document.querySelector(`#cart-item-${index} .quantity-input`);
        if (input && item) {
            input.value = item.quantity;
        }
        return;
    }
    
    cart[index].quantity = parseInt(newQuantity);
    localStorage.setItem('purchase_cart', JSON.stringify(cart));
    updateCartDisplay();
}

// Remove from cart
function removeFromCart(index) {
    console.log('Attempting to remove item at index:', index);
    
    if (index < 0 || index >= cart.length) {
        console.error('Invalid index for removal:', index);
        alert('Invalid item index');
        return;
    }
    
    const itemName = cart[index].name || 'Item';
    
    if (confirm(`Are you sure you want to remove "${itemName}" from your cart?`)) {
        console.log('Removing item at index:', index, 'Item:', cart[index]);
        
        // Remove the item
        cart.splice(index, 1);
        
        // Save to localStorage
        localStorage.setItem('purchase_cart', JSON.stringify(cart));
        
        // Update display
        updateCartDisplay();
        
        // Show success message
        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success alert-dismissible fade show';
        successMsg.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>"${itemName}" removed from cart
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        // Insert at top
        const content = document.querySelector('.content');
        if (content) {
            content.insertAdjacentElement('afterbegin', successMsg);
        }
    }
}

// Clear cart
function clearCart() {
    if (cart.length === 0) {
        alert('Your cart is already empty');
        return;
    }
    
    if (confirm(`This will remove all ${cart.length} items from your cart. Are you sure?`)) {
        cart = [];
        localStorage.removeItem('purchase_cart');
        updateCartDisplay();
        closeCart();
        
        // Show success message
        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success alert-dismissible fade show';
        successMsg.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>All items removed from cart
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        const content = document.querySelector('.content');
        if (content) {
            content.insertAdjacentElement('afterbegin', successMsg);
        }
    }
}

// Toggle cart sidebar
function toggleCart() {
    const sidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('cartOverlay');
    
    if (sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
        overlay.classList.remove('show');
    } else {
        sidebar.classList.add('open');
        overlay.classList.add('show');
    }
}

function closeCart() {
    const sidebar = document.getElementById('cartSidebar');
    const overlay = document.getElementById('cartOverlay');
    
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
}

// Submit cart
/*function submitCart() {
    if (cart.length === 0) {
        alert('Add some products to cart first');
        return;
    }
    
    localStorage.setItem('cart_for_checkout', JSON.stringify(cart));
    
    const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 1), 0);
    const totalAmount = cart.reduce((sum, item) => sum + (parseFloat(item.price) * parseInt(item.quantity)), 0).toFixed(2);
    
    if (confirm(`Proceed to checkout?\n\nTotal Items: ${totalItems}\nTotal Amount: ${currency} ${totalAmount}\n\nYou will be redirected to the order summary page.`)) {
        window.location.href = '<?= base_url("purchase_request/add") ?>?from_cart=true';
    }
}*/
/*function submitCart() {
    if (cart.length === 0) {
        alert('Add some products to cart first');
        return;
    }
    
    // Calculate totals for confirmation
    const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 1), 0);
    let totalTaxable = 0;
    let totalTax = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        totalTaxable += itemTotal;
        
        if (item.tax_type === 'intra') {
            const cgst = (item.cgst || 0) / 100;
            const sgst = (item.sgst || 0) / 100;
            totalTax += itemTotal * (cgst + sgst);
        } else {
            const igst = (item.igst || 0) / 100;
            totalTax += itemTotal * igst;
        }
    });
    
    const grandTotal = totalTaxable + totalTax;
    
    if (confirm(`Place this order?\n\nItems: ${totalItems}\nTaxable: ${currency} ${totalTaxable.toFixed(2)}\nTax: ${currency} ${totalTax.toFixed(2)}\nTotal: ${currency} ${grandTotal.toFixed(2)}\n\nClick OK to confirm.`)) {
        
        // Show loading
        Swal.fire({
            title: 'Placing Order',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        // Get CSRF token
        var csrfTokenName = '<?= $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
        
        var postData = {
            cart_items: JSON.stringify(cart),
            internal_note: '',
            external_note: ''
        };
        postData[csrfTokenName] = csrfHash;
        
        // Send cart to server
        $.ajax({
            url: '<?= base_url("Purchase_request/submit_cart_order") ?>',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                Swal.close();
                
                if (response.success) {
                    // Clear cart
                    cart = [];
                    localStorage.removeItem('purchase_cart');
                    
                    // Show success and redirect
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: response.message,
                        showConfirmButton: true
                    }).then(() => {
                        window.location.href = response.redirect_url;
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.close();
                console.error('Error:', error);
                console.error('Status:', status);
                console.error('Response Text:', xhr.responseText);
                
                let errorMsg = 'Failed to place order. ';
                
                // Try to parse error response
                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMsg += response.message || '';
                } catch (e) {
                    errorMsg += 'Server returned HTML instead of JSON. Check server logs.';
                    console.error('Raw response (first 200 chars):', xhr.responseText.substring(0, 200));
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            }
        });
    }
}*/
// Submit cart - Show custom modal instead of confirm
function submitCart() {
    if (cart.length === 0) {
        // Show error toast or alert
        showError('Add some products to cart first');
        return;
    }
    
    // Calculate totals
    const totalItems = cart.reduce((sum, item) => sum + (parseInt(item.quantity) || 1), 0);
    let totalTaxable = 0;
    let totalTax = 0;
    
    cart.forEach(item => {
        const itemTotal = item.price * item.quantity;
        totalTaxable += itemTotal;
        
        if (item.tax_type === 'intra') {
            const cgst = (item.cgst || 0) / 100;
            const sgst = (item.sgst || 0) / 100;
            totalTax += itemTotal * (cgst + sgst);
        } else {
            const igst = (item.igst || 0) / 100;
            totalTax += itemTotal * igst;
        }
    });
    
    const grandTotal = totalTaxable + totalTax;
    
    // Build order summary HTML
    let orderItemsHtml = '';
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        let taxRate = 0;
        if (item.tax_type === 'intra') {
            taxRate = (item.cgst || 0) + (item.sgst || 0);
        } else {
            taxRate = item.igst || 0;
        }
        
        orderItemsHtml += `
            <div class="order-item">
                <div class="row">
                    <div class="col-8">
                        <strong>${item.name}</strong><br>
                        <small class="text-muted">Code: ${item.product_code || 'N/A'}</small><br>
                        <small class="text-muted">UOM: ${item.uom_name || 'N/A'}</small>
                    </div>
                    <div class="col-4 text-right">
                        <span class="badge badge-info">${taxRate}% GST</span><br>
                        <strong>${currency} ${itemTotal.toFixed(2)}</strong><br>
                        <small>Qty: ${item.quantity} × ${currency}${item.price.toFixed(2)}</small>
                    </div>
                </div>
                ${item.remark ? `<div class="mt-1"><small class="text-info"><i class="fas fa-comment mr-1"></i>${item.remark}</small></div>` : ''}
            </div>
        `;
    });
    
    // Build totals HTML
    const totalsHtml = `
        <div class="order-summary-totals">
            <div class="total-row">
                <span>Total Items:</span>
                <span class="font-weight-bold">${totalItems}</span>
            </div>
            <div class="total-row">
                <span>Taxable Value:</span>
                <span>${currency} ${totalTaxable.toFixed(2)}</span>
            </div>
            <div class="total-row">
                <span>Total Tax:</span>
                <span>${currency} ${totalTax.toFixed(2)}</span>
            </div>
            <div class="total-row grand-total">
                <span>Grand Total:</span>
                <span>${currency} ${grandTotal.toFixed(2)}</span>
            </div>
        </div>
    `;
    
    // Combine all HTML
    const modalHtml = `
        <div class="order-summary">
            <h6 class="mb-3">Items (${cart.length})</h6>
            ${orderItemsHtml}
            ${totalsHtml}
        </div>
    `;
    
    // Update modal content
    $('#orderSummaryContainer').html(modalHtml);
    
    // Show the modal
    $('#orderConfirmModal').modal('show');
    
    // Handle confirm button click
    $('#confirmOrderBtn').off('click').on('click', function() {
        // Hide confirm modal
        $('#orderConfirmModal').modal('hide');
        
        // Show processing modal
        $('#processingModal').modal('show');
        
        // Get CSRF token
        var csrfTokenName = '<?= $this->security->get_csrf_token_name(); ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash(); ?>';
        
        var postData = {
            cart_items: JSON.stringify(cart),
            internal_note: '',
            external_note: ''
        };
        postData[csrfTokenName] = csrfHash;
        
        // Send cart to server
        $.ajax({
            url: '<?= base_url("Purchase_request/submit_cart_order") ?>',
            type: 'POST',
            data: postData,
            dataType: 'json',
            success: function(response) {
                $('#processingModal').modal('hide');
                
                if (response.success) {
                    // Clear cart
                    cart = [];
                    localStorage.removeItem('purchase_cart');
                    updateCartDisplay();
                    
                    // Show success message
                    $('#successMessage').text(response.message || 'Your order has been placed successfully.');
                    $('#successModal').modal('show');
                    
                    // Handle view order button
                    $('#viewOrderBtn').off('click').on('click', function() {
                        window.location.href = '<?= base_url("Purchase_request") ?>';
                    });
                    
                    // Handle go to list button
                   /* $('#goToListBtn').off('click').on('click', function() {
                        window.location.href = response.redirect_url;
                    });*/
                    
                } else {
                    showError(response.message || 'Failed to place order');
                }
            },
            error: function(xhr, status, error) {
                $('#processingModal').modal('hide');
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                
                showError('Failed to place order. Please try again.');
            }
        });
    });
}

// Helper function to show error
function showError(message) {
    // You can use a toast or simple alert
    alert(message);
}


// Test with first real product
function testAddProduct() {
    const firstProductBtn = document.querySelector('.add-to-cart-btn');
    if (firstProductBtn) {
        console.log('Testing with first product button');
        showQuantityModal(firstProductBtn);
    } else {
        console.error('No product buttons found');
        alert('No product buttons found on page');
    }
}

function clearLocalStorage() {
    if (confirm('Clear all cart data from localStorage?')) {
        localStorage.clear();
        cart = [];
        updateCartDisplay();
        
        const successMsg = document.createElement('div');
        successMsg.className = 'alert alert-success alert-dismissible fade show';
        successMsg.innerHTML = `
            <i class="fas fa-check-circle mr-2"></i>localStorage cleared and cart reset
            <button type="button" class="close" data-dismiss="alert">
                <span>&times;</span>
            </button>
        `;
        
        const content = document.querySelector('.content');
        if (content) {
            content.insertAdjacentElement('afterbegin', successMsg);
        }
    }
}

// Initialize on page load
$(document).ready(function() {
    console.log('=== PAGE LOADED ===');
    
    // Load cart from localStorage
    try {
        const cartData = localStorage.getItem('purchase_cart');
        console.log('Initial cart data:', cartData);
        
        if (cartData && cartData !== 'null' && cartData !== 'undefined') {
            cart = JSON.parse(cartData);
            console.log('Cart loaded. Items:', cart.length);
        } else {
            cart = [];
            console.log('Starting with empty cart');
        }
    } catch (error) {
        console.error('Error loading cart:', error);
        cart = [];
        localStorage.removeItem('purchase_cart');
    }
    
    // Update display
    updateCartDisplay();
    
    // Close cart when clicking overlay
    const overlay = document.getElementById('cartOverlay');
    if (overlay) {
        overlay.addEventListener('click', closeCart);
    }
    
    // Handle Enter key in quantity modal
    $('#quantityModal').on('shown.bs.modal', function() {
        $('#modalQuantity').focus();
        
        $('#modalQuantity').keypress(function(e) {
            if (e.which === 13) { // Enter key
                e.preventDefault();
                $('#confirmAddToCart').click();
            }
        });
    });
    
    // Clear current product when modal closes
    $('#quantityModal').on('hidden.bs.modal', function() {
        currentProduct = null;
    });
    
    // Add event listeners to all Add to Cart buttons
    document.querySelectorAll('.add-to-cart-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            showQuantityModal(this);
        });
    });
});
</script>
