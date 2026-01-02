<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">School Inventory Stock Report</h2>
            <p class="text-gray-500 mt-1"><?php echo Session::get('role') == 'administrator' ? 'Current stock levels across all campuses and schools' : 'Current stock levels for your campus'; ?></p>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.print()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center">
                <i class="fas fa-print mr-2"></i>
                Print
            </button>
            <a href="<?php echo URL_ROOT; ?>/reports" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Reports
            </a>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalMaterials = count($materials);
$totalStock = array_sum(array_map(function($p) { return $p->total_stock; }, $materials));
$avgStock = $totalMaterials > 0 ? round($totalStock / $totalMaterials) : 0;
$uniqueSchools = count(array_unique(array_map(function($p) { return $p->school_name; }, $materials)));
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Materials -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-book text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalMaterials; ?></h3>
        <p class="text-blue-100 text-sm">Total Materials</p>
    </div>

    <!-- Total Stock -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-boxes text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo number_format($totalStock); ?></h3>
        <p class="text-green-100 text-sm">Total Stock Units</p>
    </div>

    <!-- Average Stock -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $avgStock; ?></h3>
        <p class="text-purple-100 text-sm">Average per Material</p>
    </div>

    <!-- Schools -->
    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-university text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $uniqueSchools; ?></h3>
        <p class="text-orange-100 text-sm">Schools</p>
    </div>
</div>

<!-- Filters -->
<?php if(Session::get('role') == 'administrator'): ?>
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="<?php echo URL_ROOT; ?>/reports/stock" method="get" class="flex items-end space-x-4">
        <div class="flex-1">
            <label class="block text-sm font-semibold text-gray-700 mb-2">
                <i class="fas fa-university mr-2 text-indigo-500"></i>Filter by School
            </label>
            <select name="school" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                <option value="">All Schools</option>
                <?php foreach($schools as $school): ?>
                    <option value="<?php echo $school->id; ?>" <?php echo $selected_school == $school->id ? 'selected' : ''; ?>>
                        <?php echo $school->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg font-semibold">
            <i class="fas fa-filter mr-2"></i>Apply Filter
        </button>
    </form>
</div>
<?php endif; ?>

<!-- Report Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(!empty($materials)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">SKU</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">School</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Total Stock</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Unit</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($materials as $material): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-book text-white"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $material->name; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-700 bg-gray-100 px-2 py-1 rounded">
                            <?php echo $material->sku; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                            <i class="fas fa-tag mr-1"></i>
                            <?php echo $material->category_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            <i class="fas fa-university mr-1"></i>
                            <?php echo $material->school_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end">
                            <span class="text-lg font-bold text-gray-900"><?php echo number_format($material->total_stock); ?></span>
                            <i class="fas fa-boxes text-green-500 ml-2 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <span class="text-sm text-gray-600 bg-gray-100 px-2 py-1 rounded">
                            <?php echo $material->unit; ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="bg-gray-50 border-t-2 border-gray-300">
                <tr>
                    <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                        Total Units across Schools:
                    </td>
                    <td class="px-6 py-4 text-right">
                        <span class="text-lg font-bold text-indigo-600"><?php echo number_format($totalStock); ?></span>
                    </td>
                    <td class="px-6 py-4 text-right text-sm text-gray-600">
                        units
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-box-open text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Materials Found</h3>
        <p class="text-gray-500 mb-6">No materials match your current filter criteria</p>
        <a href="<?php echo URL_ROOT; ?>/reports/stock" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-redo mr-2"></i>
            Reset Filters
        </a>
    </div>
    <?php endif; ?>
</div>
