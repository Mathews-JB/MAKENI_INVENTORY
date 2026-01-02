<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Stock Levels</h2>
            <p class="text-gray-500 mt-1">
                <?php if (isset($data['is_filtered']) && $data['is_filtered']): ?>
                    Showing inventory for <?php echo $data['campus_name']; ?> campus only
                <?php else: ?>
                    Monitor inventory levels across all campuses
                <?php endif; ?>
            </p>
        </div>
        <div class="flex items-center gap-3">
            <button onclick="window.print()" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center">
                <i class="fas fa-print mr-2 text-gray-600"></i>
                Print
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<?php if(!empty($inventory)): ?>
<?php 
$inStock = array_filter($inventory, function($item) {
    return $item->quantity > 0;
});
$lowStock = array_filter($inventory, function($item) {
    return $item->quantity > 0 && $item->quantity <= $item->reorder_level;
});
$outOfStock = array_filter($inventory, function($item) {
    return $item->quantity == 0;
});
$totalItems = count($inventory);
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Items -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-cubes text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalItems; ?></h3>
        <p class="text-blue-100 text-sm">Total Materials</p>
    </div>

    <!-- In Stock -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-check-circle text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo count($inStock); ?></h3>
        <p class="text-green-100 text-sm">In Stock</p>
        <div class="mt-2 text-xs">
            <?php echo $totalItems > 0 ? round((count($inStock) / $totalItems) * 100) : 0; ?>% of total
        </div>
    </div>

    <!-- Low Stock -->
    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo count($lowStock); ?></h3>
        <p class="text-yellow-100 text-sm">Low Stock</p>
        <div class="mt-2 text-xs">
            Requires attention
        </div>
    </div>

    <!-- Out of Stock -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-times-circle text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo count($outOfStock); ?></h3>
        <p class="text-red-100 text-sm">Out of Stock</p>
        <div class="mt-2 text-xs">
            Urgent reorder needed
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="flex-1">
            <div class="relative">
                <input type="text" id="searchStock" placeholder="Search by material name, SKU, or campus..." 
                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
            </div>
        </div>
        <div class="flex items-center gap-3">
            <?php if(!empty($data['schools'])): ?>
            <select id="schoolFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="all">All Schools</option>
                <?php foreach($data['schools'] as $school): ?>
                    <option value="<?php echo strtolower($school->name); ?>"><?php echo $school->name; ?></option>
                <?php endforeach; ?>
            </select>
            <?php endif; ?>
            
            <select id="statusFilter" class="px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="all">All Status</option>
                <option value="in-stock">In Stock</option>
                <option value="low-stock">Low Stock</option>
                <option value="out-of-stock">Out of Stock</option>
            </select>
        </div>
    </div>
</div>

<!-- Stock Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(!empty($inventory)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Campus</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Current Stock</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Reorder Level</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="stockTableBody">
                <?php foreach($inventory as $item): ?>
                <?php 
                $status = 'in-stock';
                if($item->quantity == 0) {
                    $status = 'out-of-stock';
                } elseif($item->quantity <= $item->reorder_level) {
                    $status = 'low-stock';
                }
                ?>
                <tr class="hover:bg-gray-50 transition stock-row" 
                    data-status="<?php echo $status; ?>"
                    data-school="<?php echo strtolower($item->school_name ?? 'n/a'); ?>"
                    data-search="<?php echo strtolower($item->material_name . ' ' . $item->sku . ' ' . $item->campus_name); ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-book text-white text-lg"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $item->material_name; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-700 bg-gray-100 px-2 py-1 rounded"><?php echo $item->sku; ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <?php echo $item->campus_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            <?php echo $item->school_name ?? 'N/A'; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end space-x-2">
                            <span class="text-lg font-bold <?php echo ($item->quantity <= $item->reorder_level) ? 'text-red-600' : 'text-green-600'; ?>">
                                <?php echo number_format($item->quantity); ?>
                            </span>
                            <?php if($item->quantity <= $item->reorder_level && $item->quantity > 0): ?>
                            <i class="fas fa-arrow-down text-red-500 text-xs animate-pulse"></i>
                            <?php elseif($item->quantity > $item->reorder_level): ?>
                            <i class="fas fa-check text-green-500 text-xs"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <span class="text-sm text-gray-600 font-medium"><?php echo number_format($item->reorder_level); ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php if($item->quantity == 0): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800 border border-red-200">
                                <i class="fas fa-times-circle mr-1"></i> Out of Stock
                            </span>
                        <?php elseif($item->quantity <= $item->reorder_level): ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <i class="fas fa-exclamation-triangle mr-1"></i> Low Stock
                            </span>
                        <?php else: ?>
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                                <i class="fas fa-check-circle mr-1"></i> In Stock
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-school text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Inventory Data</h3>
        <p class="text-gray-500 mb-6">Add materials and stock to see levels here</p>
        <a href="<?php echo URL_ROOT; ?>/materials/add" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Add Your First Material
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Filter & Search Script -->
<script>
// Search functionality
document.getElementById('searchStock').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    filterRows();
});

// Status filter
document.getElementById('statusFilter').addEventListener('change', function(e) {
    filterRows();
});

// School filter
const schoolFilter = document.getElementById('schoolFilter');
if (schoolFilter) {
    schoolFilter.addEventListener('change', function(e) {
        filterRows();
    });
}

function filterRows() {
    const searchTerm = document.getElementById('searchStock').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const schoolFilter = document.getElementById('schoolFilter');
    const schoolValue = schoolFilter ? schoolFilter.value : 'all';
    
    const rows = document.querySelectorAll('.stock-row');
    
    rows.forEach(row => {
        const searchData = row.getAttribute('data-search');
        const status = row.getAttribute('data-status');
        const school = row.getAttribute('data-school');
        
        const matchesSearch = searchData.includes(searchTerm);
        const matchesStatus = statusFilter === 'all' || status === statusFilter;
        const matchesSchool = schoolValue === 'all' || school === schoolValue;
        
        if (matchesSearch && matchesStatus && matchesSchool) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
