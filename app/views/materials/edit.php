<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Edit Material</h2>
        
        <form action="<?php echo URL_ROOT; ?>/materials/edit/<?php echo $data['id']; ?>" method="post">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- School (Read-only) -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">School</label>
                    <input type="text" value="<?php echo $data['material']->school_name; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100" readonly>
                </div>

                <!-- Material Name -->
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Material Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="<?php echo $data['name']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['name_err'])) ? 'border-red-500' : ''; ?>" placeholder="Enter material name">
                    <span class="text-red-500 text-sm"><?php echo $data['name_err']; ?></span>
                </div>

                <!-- SKU -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Material SKU / Code</label>
                    <input type="text" name="sku" value="<?php echo $data['sku']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="Material SKU">
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

                <!-- Reorder Level -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Low Stock Alert Level</label>
                    <input type="number" name="reorder_level" value="<?php echo $data['reorder_level']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="Threshold for reorder alert">
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
            <!-- Divider -->
            <div class="md:col-span-2 border-t border-gray-100 my-4"></div>

            <!-- Current Stock Summary -->
            <?php if (!empty($data['inventory'])): ?>
            <div class="md:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-warehouse mr-2 text-indigo-600"></i>
                        Current Inventory Levels
                    </h3>
                    <?php 
                        $total = array_sum(array_column($data['inventory'], 'quantity'));
                    ?>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-xs font-bold border border-indigo-100">
                        Total Units: <?php echo number_format($total); ?>
                    </span>
                </div>

                <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Campus</th>
                                <th class="px-6 py-3 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Available Stock</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            <?php foreach ($data['inventory'] as $inv): ?>
                            <tr>
                                <td class="px-6 py-3 whitespace-nowrap text-sm text-gray-700 font-medium">
                                    <?php echo $inv->campus_name; ?>
                                </td>
                                <td class="px-6 py-3 whitespace-nowrap text-right">
                                    <span class="text-sm font-black <?php echo ($inv->quantity <= $data['reorder_level']) ? 'text-red-600' : 'text-indigo-600'; ?>">
                                        <?php echo number_format($inv->quantity); ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <div class="flex items-center justify-end space-x-4 mt-8">
                <a href="<?php echo URL_ROOT; ?>/materials" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Update Material
                </button>
            </div>
        </form>
    </div>
</div>
