<div class="bg-white rounded-xl shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Campuses</h2>
            <p class="text-gray-600">Manage all school campuses across institutions</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/campuses/add" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition shadow-lg flex items-center transform hover:scale-105">
            <i class="fas fa-plus mr-2"></i>Add Campus
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Campus Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">School</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(empty($campuses)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500 py-12">
                            <div class="flex flex-col items-center">
                                <i class="fas fa-school text-4xl text-gray-300 mb-2"></i>
                                <p>No campuses found. Click "Add Campus" to create one.</p>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach($campuses as $campus): ?>
                    <tr class="hover:bg-gray-50 transition border-transparent hover:border-indigo-100 border-l-4">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-landmark text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900"><?php echo $campus->name; ?></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-purple-100 text-purple-800">
                                <?php echo $campus->school_name; ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <i class="fas fa-map-pin mr-1 text-gray-400"></i> <?php echo $campus->location ?? 'N/A'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <i class="fas fa-phone-alt mr-1 text-gray-400"></i> <?php echo $campus->phone ?? 'N/A'; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center space-x-3">
                                <a href="<?php echo URL_ROOT; ?>/campuses/edit/<?php echo $campus->id; ?>" 
                                   class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition" title="Edit Campus">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="<?php echo URL_ROOT; ?>/campuses/delete/<?php echo $campus->id; ?>" method="post" class="inline" onsubmit="return confirm('Are you sure you want to delete this campus?');">
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition" title="Delete Campus">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
