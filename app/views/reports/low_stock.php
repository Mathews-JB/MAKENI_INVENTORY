<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Material Low Stock Alerts</h2>
            <p class="text-gray-600">Materials below reorder level requiring attention</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.print()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-print mr-2"></i>Print
            </button>
            <a href="<?php echo URL_ROOT; ?>/reports" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                <i class="fas fa-arrow-left mr-2"></i>Back
            </a>
        </div>
    </div>

    <!-- Filters -->
    <?php if(Session::get('role') == 'administrator'): ?>
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <form action="<?php echo URL_ROOT; ?>/reports/lowStock" method="get" class="flex items-end space-x-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Filter by School</label>
                <select name="school" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Schools</option>
                    <?php foreach($schools as $school): ?>
                        <option value="<?php echo $school->id; ?>" <?php echo $selected_school == $school->id ? 'selected' : ''; ?>>
                            <?php echo $school->name; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                Apply Filter
            </button>
        </form>
    </div>
    <?php endif; ?>

    <!-- Report Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($materials)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center py-8">
                                <i class="fas fa-check-circle text-green-500 text-4xl mb-3"></i>
                                <p class="text-lg font-medium text-gray-900">All Good!</p>
                                <p class="text-sm text-gray-500">No low stock materials found.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($materials as $material): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo $material->name; ?></div>
                            <div class="text-sm text-gray-500"><?php echo $material->sku; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $material->school_name; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-red-600">
                            <?php echo $material->total_stock ?? 0; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                            <?php echo $material->reorder_level; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <?php if(($material->total_stock ?? 0) == 0): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Out of Stock
                                </span>
                            <?php else: ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Low Stock
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                            <a href="<?php echo URL_ROOT; ?>/materials" class="text-indigo-600 hover:text-indigo-900 mr-3">View</a>
                            <a href="<?php echo URL_ROOT; ?>/procurement/create" class="text-green-600 hover:text-green-900">Procure</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
