<!-- Header Section -->
<div class="mb-6 md:mb-8">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h2 class="text-xl md:text-2xl font-bold text-gray-900">Create Distribution Order</h2>
            <p class="text-sm md:text-base text-gray-500 mt-1">Add new distribution order for recipient</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/distributions" class="px-4 md:px-6 py-2 md:py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center justify-center text-sm md:text-base">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Orders
        </a>
    </div>
</div>

<!-- Form -->
<form action="<?php echo URL_ROOT; ?>/distributions/create" method="POST" id="distributionOrderForm" class="space-y-6">
    <!-- Recipient Information -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-user-graduate mr-2 text-blue-600"></i>
                Recipient Information
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-user-circle mr-2 text-blue-500"></i>Recipient Name / Staff Name *
                    </label>
                    <input type="text" name="recipient_name" required 
                           class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Enter recipient or department name">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-phone mr-2 text-green-500"></i>Phone Number / Extension
                    </label>
                    <input type="text" name="recipient_phone" 
                           class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Enter contact number">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-500"></i>Email Address
                    </label>
                    <input type="email" name="recipient_email" 
                           class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="recipient@example.com">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-file-invoice mr-2 text-orange-500"></i>Allocation Type
                    </label>
                    <select name="payment_method" 
                            class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">Select allocation type</option>
                        <option value="internal">Internal Release</option>
                        <option value="teacher_issue">Teacher Issue</option>
                        <option value="student_issue">Student Issue</option>
                        <option value="other">Other</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Campus Selection (Admin Only) -->
    <?php if (Session::get('role') == 'administrator' && !empty($data['campuses'])): ?>
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-yellow-50 to-amber-50 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-school mr-2 text-yellow-600"></i>
                Campus Selection
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-map-marker-alt mr-2 text-yellow-500"></i>Select Campus *
                </label>
                <select name="campus_id" required 
                        class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">Choose a campus for this distribution</option>
                    <?php foreach ($data['campuses'] as $campus): ?>
                        <option value="<?php echo $campus->id; ?>"><?php echo $campus->name; ?> - <?php echo $campus->school_name; ?></option>
                    <?php endforeach; ?>
                </select>
                <p class="mt-2 text-xs text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>Select which campus this distribution order belongs to
                </p>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Add Materials -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-cubes mr-2 text-green-600"></i>
                Add Materials
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <!-- Material Search -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-search mr-2 text-indigo-500"></i>Search Material
                </label>
                <div class="relative">
                    <input type="text" id="materialSearch" 
                           class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Search by material name or SKU...">
                    <i class="fas fa-search absolute right-3 md:right-4 top-3 md:top-4 text-gray-400"></i>
                </div>
                <div id="materialResults" class="mt-2 bg-white border border-gray-200 rounded-lg shadow-lg hidden max-h-60 overflow-y-auto"></div>
            </div>
        </div>
    </div>

    <!-- Distribution Items -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-list mr-2 text-purple-600"></i>
                Distribution Items
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <!-- Desktop Table -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Material</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Quantity</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Unit Value</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total Value</th>
                            <th class="px-4 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody id="distributionItemsTable" class="bg-white divide-y divide-gray-200">
                        <!-- Items will be added here -->
                    </tbody>
                </table>
            </div>

            <!-- Mobile Cards -->
            <div id="distributionItemsMobile" class="md:hidden space-y-3">
                <!-- Items will be added here -->
            </div>

            <!-- Empty State -->
            <div id="emptyState" class="text-center py-8 md:py-12">
                <div class="w-16 h-16 md:w-20 md:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-book-open text-3xl md:text-4xl text-gray-400"></i>
                </div>
                <p class="text-sm md:text-base text-gray-500">No items added yet. Search and add materials above.</p>
            </div>

            <!-- Total -->
            <div class="mt-6 pt-6 border-t border-gray-200">
                <div class="flex justify-between items-center">
                    <span class="text-base md:text-lg font-bold text-gray-900">Total Value:</span>
                    <span class="text-xl md:text-2xl font-bold text-indigo-600">K <span id="totalAmount">0.00</span></span>
                    <input type="hidden" name="total_amount" id="totalAmountInput" value="0">
                </div>
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="bg-white rounded-lg md:rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-4 md:px-6 py-3 md:py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
            <h3 class="text-base md:text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-sticky-note mr-2 text-gray-600"></i>
                Additional Notes
            </h3>
        </div>
        <div class="p-4 md:p-6">
            <textarea name="notes" rows="3" 
                      class="w-full px-3 md:px-4 py-2 md:py-3 text-sm md:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                      placeholder="Add any additional notes or special instructions..."></textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="flex flex-col md:flex-row gap-3 md:gap-4">
        <button type="submit" class="flex-1 px-6 py-3 md:py-4 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg font-semibold text-sm md:text-base">
            <i class="fas fa-check mr-2"></i>
            Create Distribution Order
        </button>
        <a href="<?php echo URL_ROOT; ?>/distributions" class="flex-1 md:flex-none px-6 py-3 md:py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-semibold text-center text-sm md:text-base">
            <i class="fas fa-times mr-2"></i>
            Cancel
        </a>
    </div>
</form>

<script>
let distributionItems = [];
const materials = <?php echo json_encode($data['materials'] ?? []); ?>;

let stockMap = {};

// Initial load of stock map
function loadStockMap() {
    // Determine campus ID
    let campusId = null;
    const campusSelect = document.querySelector('select[name="campus_id"]');
    
    if (campusSelect) {
        campusId = campusSelect.value;
    } else {
        // Standard user - use session campus
        campusId = 0;
    }

    if (!campusId && campusSelect) return; // Admin hasn't selected yet

    fetch(`<?php echo URL_ROOT; ?>/distributions/getStockLevels/${campusId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                stockMap = data.stock;
            }
        });
}

// Call on load and change
document.addEventListener('DOMContentLoaded', loadStockMap);
if (document.querySelector('select[name="campus_id"]')) {
    document.querySelector('select[name="campus_id"]').addEventListener('change', function() {
        // Clear current items as they might be invalid for new campus
        if (distributionItems.length > 0 && confirm('Changing campus will clear current items. Continue?')) {
            distributionItems = [];
            renderItems();
            loadStockMap();
        } else if (distributionItems.length === 0) {
            loadStockMap();
        } else {
             loadStockMap(); 
        }
    });
}

// Material Search
document.getElementById('materialSearch').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const resultsDiv = document.getElementById('materialResults');
    
    if (searchTerm.length < 2) {
        resultsDiv.classList.add('hidden');
        return;
    }
    
    const filtered = materials.filter(m => 
        m.name.toLowerCase().includes(searchTerm) || 
        m.sku.toLowerCase().includes(searchTerm)
    );
    
    if (filtered.length > 0) {
        resultsDiv.innerHTML = filtered.map(m => {
            const stock = stockMap[m.id] !== undefined ? stockMap[m.id] : 0;
            const stockColor = stock > 0 ? 'text-green-600' : 'text-red-600';
            
            return `
            <div class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-0" onclick="addMaterial(${m.id})">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="font-semibold text-sm text-gray-900">${m.name}</div>
                        <div class="flex items-center space-x-2 text-xs">
                            <span class="text-gray-500">SKU: ${m.sku}</span>
                            <span class="font-medium ${stockColor}">
                                <i class="fas fa-box-open mr-1"></i>Available: ${stock}
                            </span>
                        </div>
                    </div>
                    <div class="text-sm font-bold text-indigo-600">K ${parseFloat(m.price || 0).toFixed(2)}</div>
                </div>
            </div>
            `;
        }).join('');
        resultsDiv.classList.remove('hidden');
    } else {
        resultsDiv.innerHTML = '<div class="p-3 text-sm text-gray-500 text-center">No materials found</div>';
        resultsDiv.classList.remove('hidden');
    }
});

function addMaterial(materialId) {
    const material = materials.find(m => m.id == materialId);
    if (!material) return;
    
    const existing = distributionItems.find(item => item.material_id == materialId);
    if (existing) {
        existing.quantity++;
        existing.subtotal = existing.quantity * existing.unit_price;
    } else {
        distributionItems.push({
            material_id: material.id,
            name: material.name,
            sku: material.sku,
            quantity: 1,
            unit_price: parseFloat(material.price || 0),
            subtotal: parseFloat(material.price || 0)
        });
    }
    
    document.getElementById('materialSearch').value = '';
    document.getElementById('materialResults').classList.add('hidden');
    renderItems();
}

function updateQuantity(index, quantity) {
    if (quantity < 1) return;
    distributionItems[index].quantity = parseInt(quantity);
    distributionItems[index].subtotal = distributionItems[index].quantity * distributionItems[index].unit_price;
    renderItems();
}

function removeItem(index) {
    distributionItems.splice(index, 1);
    renderItems();
}

function renderItems() {
    const tableBody = document.getElementById('distributionItemsTable');
    const mobileContainer = document.getElementById('distributionItemsMobile');
    const emptyState = document.getElementById('emptyState');
    
    if (distributionItems.length === 0) {
        tableBody.innerHTML = '';
        mobileContainer.innerHTML = '';
        emptyState.classList.remove('hidden');
        updateTotal();
        return;
    }
    
    emptyState.classList.add('hidden');
    
    // Desktop Table
    tableBody.innerHTML = distributionItems.map((item, index) => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">
                <div class="text-sm font-semibold text-gray-900">${item.name}</div>
                <div class="text-xs text-gray-500">SKU: ${item.sku}</div>
                <input type="hidden" name="items[${index}][material_id]" value="${item.material_id}">
                <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal}">
            </td>
            <td class="px-4 py-3 text-center">
                <input type="number" name="items[${index}][quantity]" value="${item.quantity}" min="1" 
                       onchange="updateQuantity(${index}, this.value)"
                       class="w-20 px-2 py-1 text-center border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </td>
            <td class="px-4 py-3 text-right">
                <input type="number" name="items[${index}][unit_price]" value="${item.unit_price.toFixed(2)}" step="0.01" min="0"
                       onchange="distributionItems[${index}].unit_price = parseFloat(this.value); distributionItems[${index}].subtotal = distributionItems[${index}].quantity * distributionItems[${index}].unit_price; renderItems();"
                       class="w-24 px-2 py-1 text-right border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </td>
            <td class="px-4 py-3 text-right font-bold text-gray-900">K ${item.subtotal.toFixed(2)}</td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="removeItem(${index})" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
    
    // Mobile Cards
    mobileContainer.innerHTML = distributionItems.map((item, index) => `
        <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
            <div class="flex items-start justify-between mb-2">
                <div class="flex-1">
                    <div class="text-sm font-semibold text-gray-900">${item.name}</div>
                    <div class="text-xs text-gray-500">SKU: ${item.sku}</div>
                </div>
                <button type="button" onclick="removeItem(${index})" class="text-red-600 hover:bg-red-50 p-2 rounded-lg">
                    <i class="fas fa-trash text-sm"></i>
                </button>
            </div>
            <input type="hidden" name="items[${index}][material_id]" value="${item.material_id}">
            <input type="hidden" name="items[${index}][subtotal]" value="${item.subtotal}">
            <div class="grid grid-cols-2 gap-2">
                <div>
                    <label class="text-xs text-gray-600">Quantity</label>
                    <input type="number" name="items[${index}][quantity]" value="${item.quantity}" min="1" 
                           onchange="updateQuantity(${index}, this.value)"
                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="text-xs text-gray-600">Unit Value</label>
                    <input type="number" name="items[${index}][unit_price]" value="${item.unit_price.toFixed(2)}" step="0.01" min="0"
                           onchange="distributionItems[${index}].unit_price = parseFloat(this.value); distributionItems[${index}].subtotal = distributionItems[${index}].quantity * distributionItems[${index}].unit_price; renderItems();"
                           class="w-full px-2 py-1 text-sm border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
            <div class="mt-2 pt-2 border-t border-gray-300 flex justify-between items-center">
                <span class="text-xs text-gray-600">Total Value:</span>
                <span class="text-sm font-bold text-indigo-600">K ${item.subtotal.toFixed(2)}</span>
            </div>
        </div>
    `).join('');
    
    updateTotal();
}

function updateTotal() {
    const total = distributionItems.reduce((sum, item) => sum + item.subtotal, 0);
    document.getElementById('totalAmount').textContent = total.toFixed(2);
    document.getElementById('totalAmountInput').value = total.toFixed(2);
}

// Form validation
document.getElementById('distributionOrderForm').addEventListener('submit', function(e) {
    if (distributionItems.length === 0) {
        e.preventDefault();
        alert('Please add at least one material to the distribution');
        return false;
    }
});
</script>
