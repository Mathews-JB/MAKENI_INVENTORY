<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New Material</h2>
        
        <form action="<?php echo URL_ROOT; ?>/materials/add" method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        School <span class="text-red-500">*</span>
                    </label>
                    <select name="school_id" id="schoolSelect" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['school_err'])) ? 'border-red-500' : ''; ?>">
                        <option value="">Select School</option>
                        <?php foreach ($data['schools'] as $school): ?>
                        <option value="<?php echo $school->id; ?>" <?php echo $data['school_id'] == $school->id ? 'selected' : ''; ?>>
                            <?php echo $school->name; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="text-red-500 text-sm"><?php echo $data['school_err']; ?></span>
                </div>

                <!-- Category -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Category <span class="text-gray-400 text-xs font-normal">(Optional)</span>
                    </label>
                    <select name="category_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="">Select Category</option>
                        <?php if (!empty($data['categories'])): ?>
                            <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?php echo $category->id; ?>" <?php echo $data['category_id'] == $category->id ? 'selected' : ''; ?>>
                                <?php echo $category->name; ?>
                            </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Material Name -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Material Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="materialNameInput" value="<?php echo $data['name']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['name_err'])) ? 'border-red-500' : ''; ?>" placeholder="Enter material name (e.g., Mathematics Textbook Grade 10)">
                    <span class="text-red-500 text-sm"><?php echo $data['name_err']; ?></span>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Material SKU / Code</label>
                    <input type="text" name="sku" id="skuInput" value="<?php echo $data['sku']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="e.g., SCH-MAT-001">
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Material Type</label>
                    <select name="type" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="stationery" <?php echo $data['type'] == 'stationery' ? 'selected' : ''; ?>>Stationery</option>
                        <option value="textbook" <?php echo $data['type'] == 'textbook' ? 'selected' : ''; ?>>Textbook</option>
                        <option value="equipment" <?php echo $data['type'] == 'equipment' ? 'selected' : ''; ?>>Lab/Sports Equipment</option>
                        <option value="other" <?php echo $data['type'] == 'other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <!-- Unit -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Unit of Measure</label>
                    <input type="text" name="unit" value="<?php echo $data['unit']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="e.g., copies, units, packs">
                </div>

                <!-- Price -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Unit Price / Cost (K) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="price" value="<?php echo $data['price'] ?? '0.00'; ?>" step="0.01" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="0.00" required>
                </div>

                <!-- Opening Stock (Initial Balance) -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Opening Stock <span class="text-gray-400 text-xs font-normal">(Initial Quantity)</span>
                    </label>
                    <input type="number" name="opening_stock" value="<?php echo $data['opening_stock'] ?? '0'; ?>" min="0" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="0">
                </div>

                <!-- Reorder Level -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Low Stock Alert Level</label>
                    <input type="number" name="reorder_level" value="<?php echo $data['reorder_level']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="Threshold for reorder alert">
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Material Description / Notes</label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="Additional details about the material"><?php echo $data['description']; ?></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8">
                <a href="<?php echo URL_ROOT; ?>/materials" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Save Material
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const nameInput = document.getElementById('materialNameInput');
    const schoolSelect = document.getElementById('schoolSelect');
    const skuInput = document.getElementById('skuInput');
    
    // Track if user has manually edited the SKU
    let skuManuallyEdited = false;
    // Timeout for debounce
    let debounceTimer;

    // Check initial state
    if(skuInput.value.trim() !== '') {
        skuManuallyEdited = true;
    }

    skuInput.addEventListener('input', function() {
        if(this.value.trim() !== '') {
            skuManuallyEdited = true;
        } else {
            skuManuallyEdited = false;
        }
    });

    async function generateSKU() {
        if (skuManuallyEdited) return;

        const name = nameInput.value.trim();
        const schoolId = schoolSelect.value;
        const schoolName = schoolSelect.options[schoolSelect.selectedIndex].text.trim();

        if (!name || !schoolId || schoolId === "") {
            skuInput.value = '';
            return;
        }

        // 1. Determine School Prefix
        let schoolPrefix = '';
        const schoolLower = schoolName.toLowerCase();
        
        // Generalized school prefix logic: First 3 letters of first word + First letter of second word if exists
        const words = schoolName.split(' ');
        if (words.length > 1) {
            schoolPrefix = (words[0].substring(0, 3) + words[1].substring(0, 1)).toUpperCase();
        } else {
            schoolPrefix = schoolName.substring(0, 3).toUpperCase();
        }

        // 2. Determine Material Prefix (First 3 letters)
        const materialPrefix = name
            .toUpperCase()
            .replace(/[^A-Z0-9]/g, '') // Remove special chars
            .substring(0, 3);
            
        if (materialPrefix.length < 1) return;

        // 3. Construct Base Prefix
        const skuPrefix = `${schoolPrefix}-${materialPrefix}`;

        // 4. Fetch Next Sequence from Backend
        try {
            const response = await fetch(`<?php echo URL_ROOT; ?>/materials/ajaxGetNextSku?prefix=${encodeURIComponent(skuPrefix)}`);
            if (!response.ok) throw new Error('Network response was not ok');
            const data = await response.json();
            
            if (data.sequence) {
                skuInput.value = `${skuPrefix}-${data.sequence}`;
            }
        } catch (error) {
            console.error('Error fetching SKU sequence:', error);
            // Fallback if network fails
            skuInput.value = `${skuPrefix}-001`;
        }
    }

    function debouncedGenerate() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(generateSKU, 500); // Wait 500ms after typing stops
    }

    nameInput.addEventListener('input', debouncedGenerate);
    schoolSelect.addEventListener('change', generateSKU); // No debounce needed for select
});
</script>
