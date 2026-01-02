<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Create Sales Order</h2>
        
        <form id="salesOrderForm" action="<?php echo URL_ROOT; ?>/sales/create" method="post">
            <!-- Customer Information -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name *</label>
                    <input type="text" name="customer_name" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" name="customer_phone" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="customer_email" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
            </div>

            <!-- Barcode Scanner -->
            <div class="bg-indigo-50 p-4 rounded-lg mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-barcode mr-2"></i>Scan or Enter Product Code
                </label>
                <div class="flex gap-2">
                    <input type="text" id="barcodeInput" placeholder="Scan barcode or enter SKU..." class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500" autofocus>
                    <button type="button" onclick="startCameraScanner()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-camera"></i> Camera
                    </button>
                </div>
                <div id="scannerFeedback" class="mt-2 text-sm"></div>
            </div>

            <!-- Manual Product Selection -->
            <div class="bg-green-50 p-4 rounded-lg mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-2"></i>Or Search & Select Product Manually
                </label>
                <div class="flex gap-2">
                    <input type="text" id="productSearch" placeholder="Type product name to search..." class="flex-1 border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="button" onclick="loadAllProducts()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        <i class="fas fa-list"></i> Browse All
                    </button>
                </div>
                <div id="productResults" class="mt-2 max-h-60 overflow-y-auto hidden">
                    <!-- Product search results will appear here -->
                </div>
            </div>

            <!-- Order Items Table -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-3">Order Items</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Available</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Quantity</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                                <th class="px-4 py-2"></th>
                            </tr>
                        </thead>
                        <tbody id="orderItemsTable" class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                    <i class="fas fa-shopping-cart text-4xl mb-2"></i>
                                    <p>No items added yet. Scan or enter product codes above.</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Total -->
            <div class="flex justify-end mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-right">
                        <span class="text-gray-600">Total Amount:</span>
                        <span id="totalAmount" class="text-2xl font-bold text-indigo-600 ml-4">K0.00</span>
                    </div>
                </div>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" name="items_json" id="itemsJson">
            <input type="hidden" name="total_amount" id="totalAmountInput">

            <!-- Actions -->
            <div class="flex justify-end gap-3">
                <a href="<?php echo URL_ROOT; ?>/sales" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">Cancel</a>
                <button type="submit" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">
                    <i class="fas fa-check mr-2"></i>Create Order
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Camera Scanner Modal -->
<div id="cameraScannerModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 max-w-2xl w-full">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold">Camera Barcode Scanner</h3>
            <button onclick="stopCameraScanner()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <div id="cameraScanner" class="bg-black rounded-lg overflow-hidden"></div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@ericblade/quagga2@1.8.4/dist/quagga.min.js"></script>
<script>
let orderItems = [];

// Barcode input handler
document.getElementById('barcodeInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        lookupProduct(this.value);
        this.value = '';
    }
});

// Lookup product by barcode/SKU
function lookupProduct(code) {
    if (!code) return;

    const feedback = document.getElementById('scannerFeedback');
    feedback.innerHTML = '<i class="fas fa-spinner fa-spin text-indigo-600"></i> Looking up product...';

    fetch('<?php echo URL_ROOT; ?>/sales/lookup?code=' + encodeURIComponent(code))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                addItemToOrder(data.product);
                feedback.innerHTML = '<span class="text-green-600"><i class="fas fa-check-circle"></i> Product added!</span>';
                setTimeout(() => feedback.innerHTML = '', 2000);
            } else {
                feedback.innerHTML = '<span class="text-red-600"><i class="fas fa-exclamation-circle"></i> ' + data.message + '</span>';
            }
        })
        .catch(error => {
            feedback.innerHTML = '<span class="text-red-600">Error looking up product</span>';
        });
}

// Load all products for browsing
function loadAllProducts() {
    fetch('<?php echo URL_ROOT; ?>/inventory/getProducts')
        .then(response => response.json())
        .then(data => {
            displayProductResults(data.products || []);
        })
        .catch(error => {
            console.error('Error loading products:', error);
        });
}

// Product search with debounce
let searchTimeout;
document.getElementById('productSearch').addEventListener('input', function(e) {
    clearTimeout(searchTimeout);
    const query = e.target.value.trim();
    
    if (query.length < 2) {
        document.getElementById('productResults').classList.add('hidden');
        return;
    }
    
    searchTimeout = setTimeout(() => {
        fetch('<?php echo URL_ROOT; ?>/inventory/searchProducts?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => {
                displayProductResults(data.products || []);
            });
    }, 300);
});

// Display product search results
function displayProductResults(products) {
    const resultsDiv = document.getElementById('productResults');
    
    if (products.length === 0) {
        resultsDiv.innerHTML = '<p class="p-3 text-gray-500 text-sm">No products found</p>';
        resultsDiv.classList.remove('hidden');
        return;
    }
    
    resultsDiv.innerHTML = products.map(product => `
        <div class="p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 flex justify-between items-center"
             onclick='selectProduct(${JSON.stringify(product)})'>
            <div>
                <div class="font-medium text-gray-900">${product.name}</div>
                <div class="text-sm text-gray-500">SKU: ${product.sku} | Stock: ${product.available_stock || 0}</div>
            </div>
            <button type="button" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm hover:bg-indigo-700">
                <i class="fas fa-plus"></i> Add
            </button>
        </div>
    `).join('');
    
    resultsDiv.classList.remove('hidden');
}

// Select product from search results
function selectProduct(product) {
    addItemToOrder(product);
    document.getElementById('productSearch').value = '';
    document.getElementById('productResults').classList.add('hidden');
}

// Add item to order
function addItemToOrder(product) {
    const existingItem = orderItems.find(item => item.product_id === product.id);
    
    if (existingItem) {
        existingItem.quantity++;
        existingItem.subtotal = existingItem.quantity * existingItem.unit_price;
    } else {
        orderItems.push({
            product_id: product.id,
            name: product.name,
            sku: product.sku,
            available_stock: product.available_stock,
            quantity: 1,
            unit_price: 0,
            subtotal: 0
        });
    }
    
    renderOrderItems();
}

// Render order items table
function renderOrderItems() {
    const tbody = document.getElementById('orderItemsTable');
    
    if (orderItems.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">No items added yet.</td></tr>';
        updateTotal();
        return;
    }

    tbody.innerHTML = orderItems.map((item, index) => `
        <tr>
            <td class="px-4 py-2 text-sm text-gray-900">K{item.name}</td>
            <td class="px-4 py-2 text-sm text-gray-500">K{item.sku}</td>
            <td class="px-4 py-2 text-sm text-right text-gray-500">K{item.available_stock}</td>
            <td class="px-4 py-2 text-right">
                <input type="number" value="K{item.quantity}" min="1" max="K{item.available_stock}" 
                       onchange="updateQuantity(K{index}, this.value)"
                       class="w-20 border-gray-300 rounded text-right">
            </td>
            <td class="px-4 py-2 text-right">
                <input type="number" value="K{item.unit_price}" min="0" step="0.01"
                       onchange="updatePrice(K{index}, this.value)"
                       class="w-24 border-gray-300 rounded text-right">
            </td>
            <td class="px-4 py-2 text-right font-semibold">KK{item.subtotal.toFixed(2)}</td>
            <td class="px-4 py-2 text-right">
                <button type="button" onclick="removeItem(K{index})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    
    updateTotal();
}

function updateQuantity(index, quantity) {
    orderItems[index].quantity = parseInt(quantity);
    orderItems[index].subtotal = orderItems[index].quantity * orderItems[index].unit_price;
    renderOrderItems();
}

function updatePrice(index, price) {
    orderItems[index].unit_price = parseFloat(price);
    orderItems[index].subtotal = orderItems[index].quantity * orderItems[index].unit_price;
    renderOrderItems();
}

function removeItem(index) {
    orderItems.splice(index, 1);
    renderOrderItems();
}

function updateTotal() {
    const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
    document.getElementById('totalAmount').textContent = 'K' + total.toFixed(2);
    document.getElementById('totalAmountInput').value = total.toFixed(2);
    document.getElementById('itemsJson').value = JSON.stringify(orderItems);
}

// Camera scanner
function startCameraScanner() {
    document.getElementById('cameraScannerModal').classList.remove('hidden');
    
    Quagga.init({
        inputStream: {
            type: "LiveStream",
            target: document.querySelector('#cameraScanner'),
            constraints: {
                width: 640,
                height: 480,
                facingMode: "environment"
            }
        },
        decoder: {
            readers: ["ean_reader", "code_128_reader", "upc_reader"]
        }
    }, function(err) {
        if (err) {
            console.error(err);
            alert('Failed to start camera');
            return;
        }
        Quagga.start();
    });

    Quagga.onDetected(function(result) {
        const code = result.codeResult.code;
        lookupProduct(code);
        stopCameraScanner();
    });
}

function stopCameraScanner() {
    Quagga.stop();
    document.getElementById('cameraScannerModal').classList.add('hidden');
}

// Form validation
document.getElementById('salesOrderForm').addEventListener('submit', function(e) {
    if (orderItems.length === 0) {
        e.preventDefault();
        alert('Please add at least one item to the order');
        return false;
    }
});
</script>
