<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">System Information</h2>
                <p class="text-gray-600">Server and application details</p>
            </div>
            <a href="<?php echo URL_ROOT; ?>/admin" class="text-indigo-600 hover:text-indigo-800">
                <i class="fas fa-arrow-left mr-2"></i>Back to Admin
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- PHP Information -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-500 rounded-full p-3 mr-4">
                        <i class="fab fa-php text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">PHP Information</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Version:</span>
                        <span class="font-semibold text-gray-800"><?php echo $php_version; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Memory Limit:</span>
                        <span class="font-semibold text-gray-800"><?php echo $memory_limit; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Upload Max:</span>
                        <span class="font-semibold text-gray-800"><?php echo $upload_max_size; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Post Max:</span>
                        <span class="font-semibold text-gray-800"><?php echo $post_max_size; ?></span>
                    </div>
                </div>
            </div>

            <!-- Server Information -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-green-500 rounded-full p-3 mr-4">
                        <i class="fas fa-server text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Server Information</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Software:</span>
                        <span class="font-semibold text-gray-800 text-sm"><?php echo $server_software; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Database:</span>
                        <span class="font-semibold text-gray-800"><?php echo $database_version; ?></span>
                    </div>
                </div>
            </div>

            <!-- Application Information -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-500 rounded-full p-3 mr-4">
                        <i class="fas fa-boxes text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">Application</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Name:</span>
                        <span class="font-semibold text-gray-800"><?php echo SITENAME; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">URL Root:</span>
                        <span class="font-semibold text-gray-800 text-sm"><?php echo URL_ROOT; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Database:</span>
                        <span class="font-semibold text-gray-800"><?php echo DB_NAME; ?></span>
                    </div>
                </div>
            </div>

            <!-- System Status -->
            <div class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="bg-orange-500 rounded-full p-3 mr-4">
                        <i class="fas fa-check-circle text-white text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">System Status</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Database Connection:</span>
                        <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">
                            <i class="fas fa-check mr-1"></i>Active
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-gray-600">Session Handler:</span>
                        <span class="px-3 py-1 bg-green-500 text-white rounded-full text-xs font-semibold">
                            <i class="fas fa-check mr-1"></i>Working
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-2">
                <i class="fas fa-info-circle text-blue-500 mr-2"></i>Additional Details
            </h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Server Name:</span>
                    <span class="font-medium text-gray-800"><?php echo $_SERVER['SERVER_NAME']; ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Document Root:</span>
                    <span class="font-medium text-gray-800 text-xs"><?php echo $_SERVER['DOCUMENT_ROOT']; ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
