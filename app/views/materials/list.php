<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Materials</h2>
            <p class="text-gray-500 mt-1">Manage your educational material inventory</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo URL_ROOT; ?>/materials/archive" class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition transform hover:scale-105 shadow-lg flex items-center">
                <i class="fas fa-archive mr-2"></i>
                Archive
            </a>
            <a href="<?php echo URL_ROOT; ?>/materials/add" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105 shadow-lg flex items-center">
                <i class="fas fa-plus mr-2"></i>
                Add New Material
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total Materials</p>
                <h3 class="text-2xl font-bold text-gray-900"><?php echo count($data['materials']); ?></h3>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-book text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Categories</p>
                <h3 class="text-2xl font-bold text-gray-900"><?php echo count(array_unique(array_column($data['materials'], 'category_name'))); ?></h3>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-tags text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Schools</p>
                <h3 class="text-2xl font-bold text-gray-900"><?php echo count($data['schools']); ?></h3>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-school text-green-600 text-xl"></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Material Types</p>
                <h3 class="text-2xl font-bold text-gray-900"><?php echo count(array_unique(array_column($data['materials'], 'type'))); ?></h3>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-layer-group text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" id="searchMaterials" placeholder="Search materials by name or SKU..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <?php if (Session::get('role') == 'administrator'): ?>
            <form method="GET" class="flex items-center">
                <select name="school" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="this.form.submit()">
                    <option value="">All Schools</option>
                    <?php foreach ($data['schools'] as $school): ?>
                    <option value="<?php echo $school->id; ?>" <?php echo $data['selected_school'] == $school->id ? 'selected' : ''; ?>>
                        <?php echo $school->name; ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </form>
            <?php endif; ?>
            <button class="px-4 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-filter text-gray-600"></i>
            </button>
        </div>
    </div>
</div>

<!-- Materials Grid/Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if (count($data['materials']) > 0): ?>
    <!-- Table for Desktop -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Reorder Level</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Price (K)</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="materialsTableBody">
                <?php foreach ($data['materials'] as $material): ?>
                <tr class="hover:bg-gray-50 transition material-row" data-name="<?php echo strtolower($material->name); ?>" data-sku="<?php echo strtolower($material->sku ?? ''); ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-book text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $material->name; ?></div>
                                <div class="text-xs text-gray-500"><?php echo $material->sku ?? 'No SKU'; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-700 bg-gray-100 px-2 py-1 rounded"><?php echo $material->sku ?? 'N/A'; ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo $material->school_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php if (isset($material->is_active) && $material->is_active == 1): ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                            <i class="fas fa-check-circle mr-1"></i>
                            Active
                        </span>
                        <?php else: ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                            <i class="fas fa-times-circle mr-1"></i>
                            Inactive
                        </span>
                        <?php endif; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800 capitalize">
                            <?php echo $material->type; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                        <?php echo $material->unit; ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-orange-100 text-orange-800">
                            <?php echo $material->reorder_level; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-semibold text-green-700">K<?php echo number_format($material->price ?? 0, 2); ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <?php if (isset($material->is_active) && $material->is_active == 1): ?>
                                <!-- Active material actions -->
                                <a href="<?php echo URL_ROOT; ?>/materials/viewMaterial/<?php echo $material->id; ?>" 
                                   class="text-blue-600 hover:text-blue-900 hover:bg-blue-50 p-2 rounded-lg transition" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?php echo URL_ROOT; ?>/materials/edit/<?php echo $material->id; ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-2 rounded-lg transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="<?php echo URL_ROOT; ?>/materials/delete/<?php echo $material->id; ?>" 
                                      class="inline" onsubmit="return confirm('Deactivate this material? It will be hidden from lists but historical data will be preserved.');">
                                    <button type="submit" class="text-orange-600 hover:text-orange-900 hover:bg-orange-50 p-2 rounded-lg transition" title="Deactivate">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                </form>
                            <?php else: ?>
                                <!-- Inactive material actions -->
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                                    <i class="fas fa-ban mr-1"></i>
                                    Deactivated
                                </span>
                                <form method="POST" action="<?php echo URL_ROOT; ?>/materials/reactivate/<?php echo $material->id; ?>" 
                                      class="inline" onsubmit="return confirm('Reactivate this material? It will appear in active lists again.');">
                                    <button type="submit" class="text-green-600 hover:text-green-900 hover:bg-green-50 p-2 rounded-lg transition" title="Reactivate">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Cards for Mobile -->
    <div class="md:hidden divide-y divide-gray-100">
        <?php foreach ($data['materials'] as $material): ?>
        <div class="p-4 hover:bg-gray-50 transition material-card" data-name="<?php echo strtolower($material->name); ?>" data-sku="<?php echo strtolower($material->sku ?? ''); ?>">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm flex-shrink-0">
                        <i class="fas fa-book text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-bold text-gray-900"><?php echo $material->name; ?></div>
                        <div class="text-xs text-gray-500">SKU: <?php echo $material->sku ?? 'N/A'; ?></div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold text-green-700 block">K<?php echo number_format($material->price ?? 0, 2); ?></span>
                    <span class="text-[10px] text-gray-500 uppercase font-semibold">per <?php echo $material->unit; ?></span>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-2 mb-4">
                <div class="bg-gray-50 p-2 rounded-lg">
                    <p class="text-[10px] text-gray-500 uppercase font-bold mb-0.5">School</p>
                    <p class="text-xs font-semibold text-gray-900 truncate"><?php echo $material->school_name; ?></p>
                </div>
                <div class="bg-gray-50 p-2 rounded-lg">
                    <p class="text-[10px] text-gray-500 uppercase font-bold mb-0.5">Type</p>
                    <p class="text-xs font-semibold text-gray-900 capitalize"><?php echo $material->type; ?></p>
                </div>
                <div class="bg-gray-50 p-2 rounded-lg">
                    <p class="text-[10px] text-gray-500 uppercase font-bold mb-0.5">Status</p>
                    <?php if (isset($material->is_active) && $material->is_active == 1): ?>
                        <span class="text-xs font-bold text-green-600 flex items-center">
                            <i class="fas fa-check-circle mr-1"></i> Active
                        </span>
                    <?php else: ?>
                        <span class="text-xs font-bold text-red-600 flex items-center">
                            <i class="fas fa-times-circle mr-1"></i> Inactive
                        </span>
                    <?php endif; ?>
                </div>
                <div class="bg-gray-50 p-2 rounded-lg">
                    <p class="text-[10px] text-gray-500 uppercase font-bold mb-0.5">Reorder</p>
                    <p class="text-xs font-semibold text-gray-900"><?php echo $material->reorder_level; ?> <?php echo $material->unit; ?></p>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-2 pt-2 border-t border-gray-50">
                <a href="<?php echo URL_ROOT; ?>/materials/viewMaterial/<?php echo $material->id; ?>" 
                   class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center">
                    <i class="fas fa-eye mr-1.5"></i> View
                </a>
                <a href="<?php echo URL_ROOT; ?>/materials/edit/<?php echo $material->id; ?>" 
                   class="bg-indigo-50 text-indigo-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center">
                    <i class="fas fa-edit mr-1.5"></i> Edit
                </a>
                <?php if (isset($material->is_active) && $material->is_active == 1): ?>
                <form method="POST" action="<?php echo URL_ROOT; ?>/materials/delete/<?php echo $material->id; ?>" 
                      class="inline" onsubmit="return confirm('Deactivate this material?');">
                    <button type="submit" class="bg-orange-50 text-orange-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center">
                        <i class="fas fa-ban mr-1.5"></i> Deactivate
                    </button>
                </form>
                <?php else: ?>
                <form method="POST" action="<?php echo URL_ROOT; ?>/materials/reactivate/<?php echo $material->id; ?>" 
                      class="inline" onsubmit="return confirm('Reactivate this material?');">
                    <button type="submit" class="bg-green-50 text-green-600 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center">
                        <i class="fas fa-check-circle mr-1.5"></i> Activate
                    </button>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-book-open text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Materials Found</h3>
        <p class="text-gray-500 mb-6">Get started by adding your first material to the inventory</p>
        <a href="<?php echo URL_ROOT; ?>/materials/add" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Add Your First Material
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Search Functionality -->
<script>
document.getElementById('searchMaterials').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('.material-row');
    
    rows.forEach(row => {
        const name = row.getAttribute('data-name');
        const sku = row.getAttribute('data-sku');
        
        if (name.includes(searchTerm) || sku.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
</script>
