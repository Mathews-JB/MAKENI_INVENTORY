<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">School System Reports</h2>
            <p class="text-gray-500 mt-1">Generate and view comprehensive inventory reports for your institution</p>
        </div>
        <button class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center transform hover:scale-105">
            <i class="fas fa-download mr-2"></i>
            Export All
        </button>
    </div>
</div>

<!-- Statistics Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-book text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $total_materials; ?></h3>
        <p class="text-blue-100 text-sm">Total Materials</p>
    </div>

    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-exclamation-triangle text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $low_stock_count; ?></h3>
        <p class="text-red-100 text-sm">Low Stock Items</p>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-university text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo count($schools); ?></h3>
        <p class="text-green-100 text-sm">Schools</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>
            Stock by School
        </h3>
        <canvas id="stockBySchoolChart"></canvas>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-pie mr-2 text-purple-600"></i>
            Materials by Category
        </h3>
        <canvas id="materialCategoryChart"></canvas>
    </div>
</div>

<!-- Report Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Stock Report Card -->
    <a href="<?php echo URL_ROOT; ?>/reports/stock" class="block group">
        <div class="bg-white rounded-xl shadow-sm border-2 border-blue-200 p-6 hover:shadow-lg hover:border-blue-400 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-boxes text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-right text-blue-400 group-hover:text-blue-600 group-hover:translate-x-1 transition transform"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Inventory Stock</h3>
            <p class="text-sm text-gray-600"><?php echo Session::get('role') == 'administrator' ? 'Current stock levels across all campuses and schools' : 'Current stock levels for your campus'; ?></p>
        </div>
    </a>

    <!-- Low Stock Report Card -->
    <a href="<?php echo URL_ROOT; ?>/reports/lowStock" class="block group">
        <div class="bg-white rounded-xl shadow-sm border-2 border-red-200 p-6 hover:shadow-lg hover:border-red-400 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-right text-red-400 group-hover:text-red-600 group-hover:translate-x-1 transition transform"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Low Stock Alerts</h3>
            <p class="text-sm text-gray-600">Items below reorder level that need replenishment</p>
        </div>
    </a>

    <!-- Transactions Report Card -->
    <a href="<?php echo URL_ROOT; ?>/reports/transactions" class="block group">
        <div class="bg-white rounded-xl shadow-sm border-2 border-green-200 p-6 hover:shadow-lg hover:border-green-400 transition">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-md">
                    <i class="fas fa-exchange-alt text-white text-2xl"></i>
                </div>
                <i class="fas fa-arrow-right text-green-400 group-hover:text-green-600 group-hover:translate-x-1 transition transform"></i>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Stock Movements</h3>
            <p class="text-sm text-gray-600">History of all stock movements, distributions, and procurements</p>
        </div>
    </a>
</div>

<!-- Custom Report Generator -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <h3 class="text-lg font-bold text-gray-900 flex items-center">
            <i class="fas fa-cogs mr-2 text-indigo-600"></i>
            Generate Custom Report
        </h3>
    </div>
    <div class="p-6">
        <form action="<?php echo URL_ROOT; ?>/reports/generate" method="post" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file-alt mr-2 text-indigo-500"></i>Report Type
                </label>
                <select name="report_type" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="stock">Stock Levels</option>
                    <option value="low_stock">Low Stock</option>
                    <option value="transactions">Stock Movements</option>
                </select>
            </div>
            <?php if(Session::get('role') == 'administrator'): ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-university mr-2 text-green-500"></i>School
                </label>
                <select name="school_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="">All Schools</option>
                    <?php foreach($schools as $school): ?>
                        <option value="<?php echo $school->id; ?>"><?php echo $school->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>Date Range
                </label>
                <select name="date_range" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    <option value="last_30_days">Last 30 Days</option>
                    <option value="this_month">This Month</option>
                    <option value="last_month">Last Month</option>
                    <option value="this_year">This Year</option>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full px-6 py-2 bg-gradient-to-r from-gray-800 to-gray-900 text-white rounded-lg hover:from-gray-900 hover:to-black transition shadow-lg font-semibold">
                    <i class="fas fa-play mr-2"></i>Generate
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
// Prepare data from PHP
const stockBySchool = <?php echo json_encode($chart_data['stock_by_school']); ?>;
const materialCategories = <?php echo json_encode($chart_data['material_categories']); ?>;

// Stock by School Chart
const stockCtx = document.getElementById('stockBySchoolChart').getContext('2d');
new Chart(stockCtx, {
    type: 'bar',
    data: {
        labels: Object.keys(stockBySchool),
        datasets: [{
            label: 'Total Stock',
            data: Object.values(stockBySchool),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)'
            ],
            borderColor: [
                'rgb(59, 130, 246)',
                'rgb(16, 185, 129)',
                'rgb(245, 158, 11)',
                'rgb(239, 68, 68)',
                'rgb(139, 92, 246)'
            ],
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Material Category Chart
const categoryCtx = document.getElementById('materialCategoryChart').getContext('2d');
new Chart(categoryCtx, {
    type: 'doughnut',
    data: {
        labels: Object.keys(materialCategories),
        datasets: [{
            data: Object.values(materialCategories),
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)'
            ],
            borderColor: '#fff',
            borderWidth: 3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});
</script>
