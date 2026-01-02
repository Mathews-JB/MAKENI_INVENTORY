<!-- Material Details Header -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="<?php echo URL_ROOT; ?>/materials" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-2xl font-bold text-gray-900"><?php echo $data['material']->name; ?></h2>
            </div>
            <p class="text-gray-500">Material Details and Inventory Information</p>
        </div>
        <div class="flex gap-3">
            <a href="<?php echo URL_ROOT; ?>/materials/edit/<?php echo $data['material']->id; ?>" 
               class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition flex items-center">
                <i class="fas fa-edit mr-2"></i>
                Edit Material
            </a>
        </div>
    </div>
</div>

<!-- Material Information Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Main Material Info -->
    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-6 flex items-center">
            <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
            Material Information
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Material Name</label>
                <p class="mt-1 text-lg font-medium text-gray-900"><?php echo $data['material']->name; ?></p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">SKU / Code</label>
                <p class="mt-1 text-lg font-mono font-medium text-gray-900 bg-gray-100 px-3 py-1 rounded inline-block">
                    <?php echo $data['material']->sku ?? 'N/A'; ?>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">School</label>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                        <?php echo $data['material']->school_name; ?>
                    </span>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Category</label>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                        <?php echo $data['material']->category_name ?? 'Uncategorized'; ?>
                    </span>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Material Type</label>
                <p class="mt-1">
                    <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800 capitalize">
                        <?php echo $data['material']->type; ?>
                    </span>
                </p>
            </div>
            
            <div>
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Unit of Measure</label>
                <p class="mt-1 text-lg font-medium text-gray-900"><?php echo $data['material']->unit; ?></p>
            </div>
            
            <div class="md:col-span-2">
                <label class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Description / Notes</label>
                <p class="mt-1 text-gray-700 leading-relaxed">
                    <?php echo $data['material']->description ?: 'No description available'; ?>
                </p>
            </div>
        </div>
    </div>
    
    <!-- Quick Stats -->
    <div class="space-y-6">
        <!-- Reorder Level -->
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-exclamation-triangle text-3xl opacity-80"></i>
                <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Low Stock Level</span>
            </div>
            <p class="text-4xl font-bold"><?php echo $data['material']->reorder_level; ?></p>
            <p class="text-sm mt-1 opacity-90">Minimum stock threshold</p>
        </div>
        
        <!-- Total Stock -->
        <?php 
        $total_stock = array_sum(array_column($data['inventory'], 'quantity'));
        ?>
        <div class="bg-gradient-to-br <?php echo ($total_stock == 0) ? 'from-red-500 to-red-600' : 'from-indigo-600 to-purple-600'; ?> rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas <?php echo ($total_stock == 0) ? 'fa-times-circle' : 'fa-cubes'; ?> text-3xl opacity-80"></i>
                <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Total Stock</span>
            </div>
            <p class="text-4xl font-bold">
                <?php echo $total_stock; ?>
            </p>
            <p class="text-sm mt-1 opacity-90"><?php echo ($total_stock == 0) ? 'Completely out of stock' : 'Across all campuses'; ?></p>
        </div>
        
        <!-- Campuses -->
        <div class="bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-2">
                <i class="fas fa-school text-3xl opacity-80"></i>
                <span class="text-sm font-semibold uppercase tracking-wide opacity-90">Campuses</span>
            </div>
            <p class="text-4xl font-bold"><?php echo count($data['inventory']); ?></p>
            <p class="text-sm mt-1 opacity-90">Locations stocking this material</p>
        </div>
    </div>
</div>

<!-- Inventory by Campus -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
            Inventory by Campus
        </h3>
    </div>
    
    <?php if (count($data['inventory']) > 0): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Campus</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Last Updated</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['inventory'] as $inv): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-university text-white"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $inv->campus_name; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-2xl font-bold text-gray-900"><?php echo $inv->quantity; ?></span>
                        <span class="text-sm text-gray-500 ml-1"><?php echo $data['material']->unit; ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <?php if ($inv->quantity == 0): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i>
                                Out of Stock
                            </span>
                        <?php elseif ($inv->quantity <= $data['material']->reorder_level): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                Low Stock
                            </span>
                        <?php elseif ($inv->quantity <= ($data['material']->reorder_level * 1.5)): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                Warning
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>
                                In Stock
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo isset($inv->updated_at) ? date('M d, Y', strtotime($inv->updated_at)) : 'N/A'; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-12">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-inbox text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Inventory Data</h3>
        <p class="text-gray-500">This material has not been stocked in any campus yet</p>
    </div>
    <?php endif; ?>
</div>
