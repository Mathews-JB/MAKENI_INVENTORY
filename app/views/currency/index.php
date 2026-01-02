<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Currency Exchange Rates</h2>
            <p class="text-gray-500 mt-1">Manage currency conversion rates to Zambian Kwacha (ZMW)</p>
        </div>
        <form action="<?php echo URL_ROOT; ?>/currency/update" method="post">
            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center transform hover:scale-105">
                <i class="fas fa-sync-alt mr-2"></i>
                Update Rates from API
            </button>
        </form>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalCurrencies = count($rates);
$lastUpdated = !empty($rates) ? max(array_map(function($r) { return strtotime($r->last_updated); }, $rates)) : time();
$baseCurrency = array_filter($rates, function($r) { return $r->currency_code == 'ZMW'; });
$foreignCurrencies = count(array_filter($rates, function($r) { return $r->currency_code != 'ZMW'; }));
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Currencies -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-coins text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalCurrencies; ?></h3>
        <p class="text-indigo-100 text-sm">Total Currencies</p>
    </div>

    <!-- Foreign Currencies -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-globe text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $foreignCurrencies; ?></h3>
        <p class="text-blue-100 text-sm">Foreign Currencies</p>
    </div>

    <!-- Base Currency -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-money-bill-wave text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1">ZMW</h3>
        <p class="text-green-100 text-sm">Base Currency</p>
    </div>

    <!-- Last Updated -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-clock text-2xl"></i>
            </div>
        </div>
        <h3 class="text-xl font-bold mb-1"><?php echo date('M j', $lastUpdated); ?></h3>
        <p class="text-purple-100 text-sm">Last Updated</p>
    </div>
</div>

<!-- Currency Converter Widget -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
    <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-exchange-alt mr-2 text-indigo-600"></i>
            Quick Currency Converter
        </h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calculator mr-2 text-blue-500"></i>Amount
                </label>
                <input type="number" id="convertAmount" step="0.01" value="100" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-flag mr-2 text-green-500"></i>From Currency
                </label>
                <select id="convertFrom" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <?php foreach($rates as $rate): ?>
                        <?php if($rate->currency_code != 'ZMW'): ?>
                            <option value="<?php echo $rate->currency_code; ?>"><?php echo $rate->currency_code; ?> - <?php echo $rate->currency_name ?? ''; ?></option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="convertCurrency()" class="w-full px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg font-semibold">
                    <i class="fas fa-arrow-right mr-2"></i>Convert
                </button>
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>Result (ZMW)
                </label>
                <div id="convertResult" class="w-full border-2 border-green-300 bg-green-50 rounded-lg px-4 py-2 text-lg font-bold text-green-700 flex items-center justify-center">
                    K 0.00
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Exchange Rates Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Currency</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Rate to ZMW</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Last Updated</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Example</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach($rates as $rate): ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-coins text-white"></i>
                            </div>
                            <div class="ml-3">
                                <span class="text-sm font-bold text-gray-900"><?php echo $rate->currency_code; ?></span>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-sm text-gray-900"><?php echo $rate->currency_name ?? 'N/A'; ?></span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <div class="flex items-center justify-end">
                            <span class="text-lg font-bold text-gray-900"><?php echo number_format($rate->rate_to_zmw, 6); ?></span>
                            <i class="fas fa-chart-line text-green-500 ml-2 text-sm"></i>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center text-sm text-gray-600">
                            <i class="fas fa-clock mr-2 text-gray-400"></i>
                            <?php echo date('M j, Y g:i A', strtotime($rate->last_updated)); ?>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        <span class="text-sm font-mono text-gray-700 bg-gray-100 px-3 py-1 rounded">
                            1 <?php echo $rate->currency_code; ?> = K <?php echo number_format($rate->rate_to_zmw, 2); ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Info Note -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <i class="fas fa-info-circle text-blue-600 text-2xl"></i>
        </div>
        <div class="ml-4">
            <h4 class="text-sm font-bold text-blue-900 mb-1">Automatic Updates</h4>
            <p class="text-sm text-blue-800">
                Exchange rates are automatically updated every 24 hours from a live API. 
                You can manually update them anytime by clicking the "Update Rates from API" button above.
            </p>
        </div>
    </div>
</div>

<script>
function convertCurrency() {
    const amount = document.getElementById('convertAmount').value;
    const from = document.getElementById('convertFrom').value;
    
    fetch(`<?php echo URL_ROOT; ?>/currency/convert?amount=${amount}&from=${from}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('convertResult').textContent = 'K ' + data.converted_amount.toFixed(2);
            } else {
                document.getElementById('convertResult').textContent = 'Error';
            }
        })
        .catch(error => {
            document.getElementById('convertResult').textContent = 'Error';
        });
}

// Auto-convert on input change
document.getElementById('convertAmount').addEventListener('input', convertCurrency);
document.getElementById('convertFrom').addEventListener('change', convertCurrency);

// Initial conversion
convertCurrency();
</script>
