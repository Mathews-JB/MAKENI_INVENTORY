<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Archived Materials</h2>
            <p class="text-gray-500 mt-1">View and restore deactivated materials</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/materials" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105 shadow-lg flex items-center">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Materials
        </a>
    </div>
</div>

<!-- Stats Card -->
<div class="bg-gradient-to-br from-gray-500 to-gray-600 rounded-xl p-6 text-white shadow-lg mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-3xl font-bold mb-1"><?php echo count($data['materials']); ?></h3>
            <p class="text-gray-100 text-sm">Archived Materials</p>
        </div>
        <div class="bg-white bg-opacity-20 rounded-lg p-3">
            <i class="fas fa-archive text-2xl"></i>
        </div>
    </div>
</div>

<!-- Filters and Search -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" id="searchMaterials" placeholder="Search archived materials by name or SKU..." 
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
        </div>
    </div>
</div>

<!-- Archived Materials Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if (count($data['materials']) > 0): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Archived Date</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="materialsTableBody">
                <?php foreach ($data['materials'] as $material): ?>
                <tr class="hover:bg-gray-50 transition material-row" data-name="<?php echo strtolower($material->name); ?>" data-sku="<?php echo strtolower($material->sku ?? ''); ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-gray-400 to-gray-500 rounded-lg flex items-center justify-center shadow-md opacity-60">
                                <i class="fas fa-book text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-600"><?php echo $material->name; ?></div>
                                <div class="text-xs text-gray-400"><?php echo $material->sku ?? 'No SKU'; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-500 bg-gray-100 px-2 py-1 rounded"><?php echo $material->sku ?? 'N/A'; ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600">
                            <?php echo $material->school_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-600 capitalize">
                            <?php echo $material->type; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo date('M d, Y', strtotime($material->created_at)); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <form method="POST" action="<?php echo URL_ROOT; ?>/materials/reactivate/<?php echo $material->id; ?>" 
                                  class="inline" onsubmit="return confirm('Reactivate this material? It will appear in active material lists again.');">
                                <button type="submit" class="text-green-600 hover:text-green-900 hover:bg-green-50 px-4 py-2 rounded-lg transition font-semibold" title="Reactivate">
                                    <i class="fas fa-undo mr-1"></i> Restore
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-archive text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Archived Materials</h3>
        <p class="text-gray-500 mb-6">All materials are currently active</p>
        <a href="<?php echo URL_ROOT; ?>/materials" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to Materials
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
