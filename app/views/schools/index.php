<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Schools</h2>
            <p class="text-gray-500 mt-1">Manage all school institutions and educational partners</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/schools/add" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>
            Add New School
        </a>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalSchools = count($schools);
$withEmail = count(array_filter($schools, function($s) { return !empty($s->email); }));
$withPhone = count(array_filter($schools, function($s) { return !empty($s->phone); }));
$withAddress = count(array_filter($schools, function($s) { return !empty($s->address); }));
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Schools -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-school text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalSchools; ?></h3>
        <p class="text-indigo-100 text-sm">Total Schools</p>
    </div>

    <!-- With Email -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-envelope text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $withEmail; ?></h3>
        <p class="text-blue-100 text-sm">With Email</p>
    </div>

    <!-- With Phone -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-phone text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $withPhone; ?></h3>
        <p class="text-green-100 text-sm">With Phone</p>
    </div>

    <!-- With Address -->
    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-map-marker-alt text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $withAddress; ?></h3>
        <p class="text-purple-100 text-sm">With Address</p>
    </div>
</div>

<!-- Search Bar -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <div class="relative">
        <input type="text" id="searchSchools" placeholder="Search by school name, email, or phone..." 
               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
        <i class="fas fa-search absolute left-3 top-4 text-gray-400"></i>
    </div>
</div>

<!-- Schools Grid/Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if(!empty($schools)): ?>
    <!-- Desktop Table -->
    <div class="hidden md:block overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">School Name</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Address</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="schoolsTableBody">
                <?php foreach($schools as $school): ?>
                <tr class="hover:bg-gray-50 transition school-row" 
                    data-search="<?php echo strtolower($school->name . ' ' . ($school->email ?? '') . ' ' . ($school->phone ?? '')); ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center shadow-md">
                                <i class="fas fa-school text-white"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-semibold text-gray-900"><?php echo $school->name; ?></div>
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-graduation-cap mr-1"></i>Education
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo $school->email ?: '<span class="text-gray-400">N/A</span>'; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900"><?php echo $school->phone ?: '<span class="text-gray-400">N/A</span>'; ?></div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900 truncate max-w-xs"><?php echo $school->address ?: '<span class="text-gray-400">N/A</span>'; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex items-center justify-center space-x-3">
                            <a href="<?php echo URL_ROOT; ?>/schools/edit/<?php echo $school->id; ?>" 
                               class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-2 rounded-lg transition" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="<?php echo URL_ROOT; ?>/schools/delete/<?php echo $school->id; ?>" 
                                  method="post" class="inline" onsubmit="return confirm('Delete this school?');">
                                <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Mobile Cards -->
    <div class="md:hidden divide-y divide-gray-100" id="schoolsMobileCards">
        <?php foreach($schools as $school): ?>
        <div class="p-4 hover:bg-gray-50 transition school-card" 
             data-search="<?php echo strtolower($school->name . ' ' . ($school->email ?? '') . ' ' . ($school->phone ?? '')); ?>">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center">
                    <div class="h-10 w-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm flex-shrink-0">
                        <i class="fas fa-school text-sm"></i>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-bold text-gray-900"><?php echo $school->name; ?></div>
                        <div class="text-xs text-gray-500">Educational Institution</div>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <a href="<?php echo URL_ROOT; ?>/schools/edit/<?php echo $school->id; ?>" 
                       class="p-2 bg-indigo-50 text-indigo-600 rounded-lg">
                        <i class="fas fa-edit text-xs"></i>
                    </a>
                    <form action="<?php echo URL_ROOT; ?>/schools/delete/<?php echo $school->id; ?>" method="post" class="inline" onsubmit="return confirm('Delete?');">
                        <button type="submit" class="p-2 bg-red-50 text-red-600 rounded-lg">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="grid grid-cols-1 gap-2">
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-envelope w-5 text-gray-400"></i>
                    <span><?php echo $school->email ?: 'No email'; ?></span>
                </div>
                <div class="flex items-center text-xs text-gray-600">
                    <i class="fas fa-phone w-5 text-gray-400"></i>
                    <span><?php echo $school->phone ?: 'No phone'; ?></span>
                </div>
                <div class="flex items-start text-xs text-gray-600">
                    <i class="fas fa-map-marker-alt w-5 text-gray-400 mt-0.5"></i>
                    <span class="line-clamp-1"><?php echo $school->address ?: 'No address'; ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-school text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Schools Found</h3>
        <p class="text-gray-500 mb-6">Get started by adding your first school</p>
        <a href="<?php echo URL_ROOT; ?>/schools/add" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg">
            <i class="fas fa-plus mr-2"></i>
            Add First School
        </a>
    </div>
    <?php endif; ?>
</div>

<!-- Search Script -->
<script>
document.getElementById('searchSchools').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    
    // Rows (Desktop)
    document.querySelectorAll('.school-row').forEach(row => {
        const searchData = row.getAttribute('data-search');
        row.style.display = searchData.includes(searchTerm) ? '' : 'none';
    });
    
    // Cards (Mobile)
    document.querySelectorAll('.school-card').forEach(card => {
        const searchData = card.getAttribute('data-search');
        card.style.display = searchData.includes(searchTerm) ? '' : 'none';
    });
});
</script>
