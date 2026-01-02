<!-- Page Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Create Procurement Order</h2>
            <p class="text-gray-500 mt-1">Initiate a new material procurement for a specific campus</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/procurements" class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Orders
        </a>
    </div>
</div>

<form id="purchaseOrderForm" action="<?php echo URL_ROOT; ?>/procurements/create" method="POST" class="space-y-6">
    <!-- Vendor Information -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-truck mr-2 text-indigo-600"></i>
                Vendor Information
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php if (!empty($data['campuses'])): ?>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Campus <span class="text-red-500">*</span>
                    </label>
                    <select name="campus_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">-- Select Campus --</option>
                        <?php foreach ($data['campuses'] as $campus): ?>
                            <option value="<?php echo $campus->id; ?>" data-school-id="<?php echo $campus->school_id; ?>">
                                <?php echo $campus->name; ?> (<?php echo $campus->school_name ?? 'No School'; ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Vendor Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="vendor_name" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                           placeholder="Enter vendor name">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone / Ext</label>
                    <input type="text" name="vendor_phone"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                           placeholder="Vendor phone">
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email / Contact</label>
                    <input type="email" name="vendor_email"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition"
                           placeholder="Vendor email">
                </div>
            </div>
        </div>
    </div>

    <!-- Add Materials -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-plus-circle mr-2 text-green-600"></i>
                Add Materials
            </h3>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Select Material 
                        <span class="text-gray-500 text-xs">(<?php echo count($data['materials']); ?> materials available)</span>
                    </label>
                    <select id="materialSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                        <option value="">-- Select a material --</option>
                        <?php if (empty($data['materials'])): ?>
                            <option value="" disabled>No materials found. Please add materials first.</option>
                        <?php else: ?>
                            <?php foreach ($data['materials'] as $material): ?>
                            <option value="<?php echo $material->id; ?>" 
                                    data-name="<?php echo htmlspecialchars($material->name); ?>"
                                    data-sku="<?php echo htmlspecialchars($material->sku ?? ''); ?>"
                                    data-price="<?php echo $material->price ?? 0; ?>"
                                    data-school-id="<?php echo $material->school_id; ?>">
                                <?php echo $material->name; ?> (<?php echo $material->sku ?? 'No SKU'; ?>) - K<?php echo number_format($material->price ?? 0, 2); ?>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="materialQuantity" value="1" min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition">
                </div>
                
                <div class="flex items-end">
                    <button type="button" onclick="addMaterialToOrder()" 
                            class="w-full px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-semibold shadow-md">
                        <i class="fas fa-plus mr-2"></i>Add to Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Materials -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-indigo-50 border-b border-gray-200">
            <h3 class="text-lg font-bold text-gray-900 flex items-center">
                <i class="fas fa-shopping-cart mr-2 text-purple-600"></i>
                Material Items (<span id="materialCount">0</span>)
            </h3>
        </div>
        
        <div id="emptyState" class="p-12 text-center text-gray-500">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-4xl text-gray-300"></i>
            </div>
            <p class="text-lg font-medium">No materials added yet.</p>
            <p class="text-sm">Select materials above to build your procurement order.</p>
        </div>
        
        <div id="orderMaterialsContainer" class="hidden">
            <!-- Desktop Table View -->
            <div class="hidden md:block overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Material</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Unit Value</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity</th>
                            <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Total Value</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="orderMaterialsTable" class="bg-white divide-y divide-gray-200">
                    </tbody>
                </table>
            </div>

            <!-- Mobile Card View -->
            <div id="orderMaterialsCards" class="md:hidden divide-y divide-gray-100 bg-white">
            </div>
            
            <!-- Order Summary -->
            <div class="px-6 py-6 bg-gray-50 border-t border-gray-200">
                <div class="flex flex-col md:flex-row justify-between items-center gap-2 md:gap-4 md:justify-end">
                    <span class="text-lg md:text-xl font-bold text-gray-900">Grand Total:</span>
                    <span class="text-2xl md:text-3xl font-black text-indigo-600">K <span id="totalValue">0.00</span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Section -->
    <div class="flex flex-col md:flex-row justify-end gap-4 pt-4">
        <a href="<?php echo URL_ROOT; ?>/procurements" 
           class="px-8 py-4 border-2 border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition font-bold text-center">
            <i class="fas fa-times mr-2"></i>Cancel
        </a>
        <button type="button" id="submitBtn" onclick="submitOrder(event)" disabled
                class="px-8 py-4 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl hover:from-green-700 hover:to-emerald-700 transition font-bold shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
            <i class="fas fa-check-circle mr-2"></i>Create Procurement Order
        </button>
    </div>

    <!-- Hidden inputs for form submission -->
    <input type="hidden" name="items_json" id="itemsJson">
    <input type="hidden" name="total_amount" id="totalValueInput">
</form>

<script>
let orderItems = [];

function addMaterialToOrder() {
    const select = document.getElementById('materialSelect');
    const quantity = parseInt(document.getElementById('materialQuantity').value);
    
    if (!select.value) {
        alert('Please select a material');
        return;
    }
    
    if (quantity < 1) {
        alert('Quantity must be at least 1');
        return;
    }
    
    const option = select.options[select.selectedIndex];
    const materialId = select.value;
    const materialName = option.dataset.name;
    const materialSku = option.dataset.sku;
    const unitPrice = parseFloat(option.dataset.price);
    
    console.log('Adding material:', {materialId, materialName, materialSku, unitValue: unitPrice, quantity});
    
    // Check if material already in order
    const existingIndex = orderItems.findIndex(item => item.material_id == materialId);
    
    if (existingIndex >= 0) {
        // Update quantity
        orderItems[existingIndex].quantity += quantity;
        orderItems[existingIndex].subtotal = orderItems[existingIndex].quantity * orderItems[existingIndex].unit_price;
        alert('Material quantity updated!');
    } else {
        // Add new item
        orderItems.push({
            material_id: materialId,
            name: materialName,
            sku: materialSku,
            quantity: quantity,
            unit_price: unitPrice,
            subtotal: quantity * unitPrice
        });
        alert('Material added to order!');
    }
    
    console.log('Current order materials:', orderItems);
    
    // Reset form
    select.value = '';
    document.getElementById('materialQuantity').value = 1;
    
    renderOrderItems();
}

function updateQuantity(index, newQuantity) {
    if (newQuantity < 1) return;
    orderItems[index].quantity = parseInt(newQuantity);
    orderItems[index].subtotal = orderItems[index].quantity * orderItems[index].unit_price;
    renderOrderItems();
}

function updatePrice(index, newPrice) {
    if (newPrice < 0) return;
    orderItems[index].unit_price = parseFloat(newPrice);
    orderItems[index].subtotal = orderItems[index].quantity * orderItems[index].unit_price;
    renderOrderItems();
}

function removeItem(index) {
    orderItems.splice(index, 1);
    renderOrderItems();
}

function renderOrderItems() {
    const tableBody = document.getElementById('orderMaterialsTable');
    const cardsContainer = document.getElementById('orderMaterialsCards');
    const emptyState = document.getElementById('emptyState');
    const container = document.getElementById('orderMaterialsContainer');
    const submitBtn = document.getElementById('submitBtn');
    const materialCount = document.getElementById('materialCount');
    
    if (orderItems.length === 0) {
        emptyState.classList.remove('hidden');
        container.classList.add('hidden');
        submitBtn.disabled = true;
        materialCount.textContent = '0';
        return;
    }
    
    emptyState.classList.add('hidden');
    container.classList.remove('hidden');
    submitBtn.disabled = false;
    materialCount.textContent = orderItems.length;
    
    let tableHtml = '';
    let cardsHtml = '';
    let total = 0;
    
    orderItems.forEach((item, index) => {
        total += item.subtotal;
        
        // Table Row (Desktop)
        tableHtml += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <div class="text-sm font-semibold text-gray-900">${item.name}</div>
                    <div class="text-xs text-gray-500">${item.sku || 'No SKU'}</div>
                </td>
                <td class="px-6 py-4 text-right">
                    <input type="number" value="${item.unit_price}" step="0.01" min="0"
                           onchange="updatePrice(${index}, this.value)"
                           class="w-24 px-2 py-1 border border-gray-300 rounded text-right">
                </td>
                <td class="px-6 py-4 text-center">
                    <input type="number" value="${item.quantity}" min="1"
                           onchange="updateQuantity(${index}, this.value)"
                           class="w-20 px-2 py-1 border border-gray-300 rounded text-center">
                </td>
                <td class="px-6 py-4 text-right">
                    <span class="text-sm font-semibold text-gray-900">K ${item.subtotal.toFixed(2)}</span>
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="removeItem(${index})" 
                            class="text-red-600 hover:text-red-800">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        // Card (Mobile)
        cardsHtml += `
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <div class="text-sm font-bold text-gray-900">${item.name}</div>
                        <div class="text-xs text-gray-500">SKU: ${item.sku || 'N/A'}</div>
                    </div>
                    <button type="button" onclick="removeItem(${index})" class="text-red-500 p-2">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Unit Price</label>
                        <div class="relative">
                            <span class="absolute left-2 top-2 text-xs text-gray-400">K</span>
                            <input type="number" value="${item.unit_price}" step="0.01" min="0"
                                   onchange="updatePrice(${index}, this.value)"
                                   class="w-full pl-6 pr-2 py-2 border border-gray-200 rounded-lg text-sm font-semibold">
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] uppercase font-bold text-gray-400 mb-1">Quantity</label>
                        <input type="number" value="${item.quantity}" min="1"
                               onchange="updateQuantity(${index}, this.value)"
                               class="w-full px-3 py-2 border border-gray-200 rounded-lg text-sm font-semibold text-center">
                    </div>
                </div>
                <div class="flex justify-between items-center py-2 bg-indigo-50 px-3 rounded-lg border border-indigo-100">
                    <span class="text-xs font-bold text-indigo-600">Subtotal</span>
                    <span class="text-sm font-black text-indigo-700">K ${item.subtotal.toFixed(2)}</span>
                </div>
            </div>
        `;
    });
    
    tableBody.innerHTML = tableHtml;
    cardsContainer.innerHTML = cardsHtml;
    document.getElementById('totalValue').textContent = total.toFixed(2);
}

// Filter materials based on selected campus (Administrator)
const campusSelect = document.querySelector('select[name="campus_id"]');
if (campusSelect) {
    campusSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const schoolId = selectedOption.dataset.schoolId; 
        console.log('Campus changed. School ID:', schoolId);
        filterMaterialsBySchool(schoolId);
    });
}

function filterMaterialsBySchool(schoolId) {
    const materialSelect = document.getElementById('materialSelect');
    const options = materialSelect.options;
    let visibleCount = 0;
    
    // Reset selection
    materialSelect.value = "";
    
    for (let i = 0; i < options.length; i++) {
        const option = options[i];
        if (option.value === "") continue; 
        
        const materialSchoolId = option.dataset.schoolId;
        
        if (schoolId && materialSchoolId) {
            if (materialSchoolId == schoolId) {
                option.style.display = "";
                option.disabled = false;
                visibleCount++;
            } else {
                option.style.display = "none";
                option.disabled = true; 
            }
        } else {
            option.style.display = "";
            option.disabled = false;
             visibleCount++;
        }
    }
}


function submitOrder(e) {
    e.preventDefault(); 
    
    console.log('Validating order...');
    if (orderItems.length === 0) {
        alert('Please add at least one material to the order');
        return false;
    }
    
    const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);
    
    // Populate hidden fields
    const itemsJsonInput = document.getElementById('itemsJson');
    const totalInput = document.getElementById('totalValueInput');
    
    itemsJsonInput.value = JSON.stringify(orderItems);
    totalInput.value = total;
    
    console.log('Items JSON populated:', itemsJsonInput.value);
    
    // Submit the form programmatically
    document.getElementById('purchaseOrderForm').submit();
}

const form = document.getElementById('purchaseOrderForm');
</script>
