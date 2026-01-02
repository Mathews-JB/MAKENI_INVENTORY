<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-6 border-b pb-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Stock Movement History</h2>
            <p class="text-gray-600">Complete audit trail of stock movements, procurements, and distributions</p>
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
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <form action="<?php echo URL_ROOT; ?>/reports/transactions" method="get" class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <?php if(Session::get('role') == 'administrator'): ?>
            <div>
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
            <?php endif; ?>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Movement Type</label>
                <select name="type" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">All Types</option>
                    <option value="purchase" <?php echo ($selected_type == 'purchase') ? 'selected' : ''; ?>>Procurement</option>
                    <option value="sale" <?php echo ($selected_type == 'sale') ? 'selected' : ''; ?>>Distribution</option>
                    <option value="transfer" <?php echo ($selected_type == 'transfer') ? 'selected' : ''; ?>>Transfer</option>
                    <option value="adjustment" <?php echo ($selected_type == 'adjustment') ? 'selected' : ''; ?>>Adjustment</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                <input type="date" name="end_date" value="<?php echo $end_date; ?>" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            </div>
            <div class="flex space-x-2">
                <button type="submit" class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                    Filter
                </button>
                <a href="<?php echo URL_ROOT; ?>/reports/transactions" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition text-center">Reset</a>
            </div>
        </form>
    </div>

    <!-- Report Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($transactions)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No stock movements found for the selected criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach($transactions as $trx): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo date('M d, Y H:i', strtotime($trx->created_at)); ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900"><?php echo $trx->material_name; ?></div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $trx->campus_name; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <?php
                            $typeClasses = [
                                'purchase' => 'bg-green-100 text-green-800',
                                'sale' => 'bg-blue-100 text-blue-800',
                                'transfer' => 'bg-orange-100 text-orange-800',
                                'adjustment' => 'bg-gray-100 text-gray-800'
                            ];
                            $typeLabels = [
                                'purchase' => 'Procurement',
                                'sale' => 'Distribution',
                                'transfer' => 'Transfer',
                                'adjustment' => 'Adjustment'
                            ];
                            $typeClass = $typeClasses[$trx->type] ?? 'bg-gray-100 text-gray-800';
                            $typeLabel = $typeLabels[$trx->type] ?? ucfirst($trx->type);
                            ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $typeClass; ?>">
                                <?php echo $typeLabel; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold <?php echo $trx->quantity > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                            <?php echo $trx->quantity > 0 ? '+' : ''; ?><?php echo $trx->quantity; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo $trx->reference; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
