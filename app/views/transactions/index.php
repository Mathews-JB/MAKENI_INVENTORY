<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Stock History & Movements</h2>
            <p class="text-gray-500 mt-1">Track all material movements and inventory changes across campuses</p>
        </div>
        <button onclick="window.print()" class="px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition flex items-center shadow-sm">
            <i class="fas fa-print mr-2"></i>
            Print Report
        </button>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalTransactions = count($transactions);
$purchaseCount = count(array_filter($transactions, function($t) { return $t->type == 'purchase'; }));
$saleCount = count(array_filter($transactions, function($t) { return $t->type == 'sale'; }));
$transferCount = count(array_filter($transactions, function($t) { return $t->type == 'transfer'; }));
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Transactions -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-exchange-alt text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalTransactions; ?></h3>
        <p class="text-indigo-100 text-sm">Total Movements</p>
    </div>

    <!-- Purchases -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-file-invoice text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $purchaseCount; ?></h3>
        <p class="text-green-100 text-sm">Procurements (Stock In)</p>
    </div>

    <!-- Sales / Distributions -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-shipping-fast text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $saleCount; ?></h3>
        <p class="text-blue-100 text-sm">Distributions (Allocations)</p>
    </div>

    <!-- Transfers -->
    <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-random text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $transferCount; ?></h3>
        <p class="text-yellow-100 text-sm">Campus Transfers</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="<?php echo URL_ROOT; ?>/transactions" method="get">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-university mr-2 text-indigo-500"></i>School
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
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-green-500"></i>Start Date
                </label>
                <input type="date" name="start_date" value="<?php echo $start_date; ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-check mr-2 text-blue-500"></i>End Date
                </label>
                <input type="date" name="end_date" value="<?php echo $end_date; ?>" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg font-semibold">
                    <i class="fas fa-filter mr-2"></i>Apply Filter
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(!empty($transactions)): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date & Time</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Material</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Campus</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Reference</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Quantity</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">By Staff</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($transactions as $trx): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            <span class="text-gray-900 font-medium"><?php echo date('M j, Y', strtotime($trx->created_at)); ?></span>
                            <span class="text-gray-500 ml-2"><?php echo date('H:i', strtotime($trx->created_at)); ?></span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php
                        $typeConfig = [
                            'purchase' => ['label' => 'Procurement', 'bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200', 'icon' => 'fa-file-invoice'],
                            'sale' => ['label' => 'Distribution', 'bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'icon' => 'fa-shipping-fast'],
                            'transfer' => ['label' => 'Transfer', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200', 'icon' => 'fa-random'],
                            'adjustment' => ['label' => 'Adjustment', 'bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'border' => 'border-purple-200', 'icon' => 'fa-sliders-h']
                        ];
                        $config = $typeConfig[$trx->type] ?? ['label' => ucfirst($trx->type), 'bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'border' => 'border-gray-200', 'icon' => 'fa-circle'];
                        ?>
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full <?php echo $config['bg'] . ' ' . $config['text']; ?> border <?php echo $config['border']; ?>">
                            <i class="fas <?php echo $config['icon']; ?> mr-1"></i>
                            <?php echo $config['label']; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-book text-white"></i>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $trx->material_name; ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                            <?php echo $trx->campus_name; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm font-mono text-gray-600 bg-gray-100 px-2 py-1 rounded">
                            <?php echo $trx->reference; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end">
                            <span class="text-lg font-bold <?php echo $trx->quantity > 0 ? 'text-green-600' : 'text-red-600'; ?>">
                                <?php echo $trx->quantity > 0 ? '+' . $trx->quantity : $trx->quantity; ?>
                            </span>
                            <?php if($trx->quantity > 0): ?>
                                <i class="fas fa-arrow-up text-green-500 ml-2 text-xs"></i>
                            <?php else: ?>
                                <i class="fas fa-arrow-down text-red-500 ml-2 text-xs"></i>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-xs mr-2">
                                <?php echo strtoupper(substr($trx->username, 0, 1)); ?>
                            </div>
                            <span class="text-sm text-gray-900"><?php echo $trx->username; ?></span>
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
            <i class="fas fa-exchange-alt text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Movements Found</h3>
        <p class="text-gray-500 mb-6">No stock movements match your current filter criteria</p>
        <a href="<?php echo URL_ROOT; ?>/transactions" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-redo mr-2"></i>
            Reset Filters
        </a>
    </div>
    <?php endif; ?>
</div>
