<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">My Profile</h2>
            <p class="text-gray-500 mt-1">Manage your personal information and settings</p>
        </div>
        <a href="<?php echo URL_ROOT; ?>/profile/edit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition shadow-lg flex items-center transform hover:scale-105">
            <i class="fas fa-edit mr-2"></i>
            Edit Profile
        </a>
    </div>
</div>

<div class="max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header with Gradient -->
                <div class="h-32 bg-gradient-to-r from-indigo-600 to-purple-700"></div>
                
                <!-- Profile Picture -->
                <div class="relative px-6 pb-6">
                    <div class="flex justify-center -mt-16 mb-4">
                        <?php if ($data['user']->profile_picture): ?>
                            <img src="<?php echo URL_ROOT; ?>/<?php echo $data['user']->profile_picture; ?>" 
                                 alt="Profile" 
                                 class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-xl">
                        <?php else: ?>
                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white text-5xl font-bold border-4 border-white shadow-xl">
                                <?php echo strtoupper(substr($data['user']->full_name, 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- User Info -->
                    <div class="text-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 mb-1"><?php echo $data['user']->full_name; ?></h3>
                        <p class="text-gray-500 text-sm mb-3">@<?php echo $data['user']->username; ?></p>
                        
                        <?php
                        $roleConfig = [
                            'administrator' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-crown'],
                            'procurement officer' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-file-invoice'],
                            'store keeper' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'icon' => 'fa-warehouse'],
                            'teacher' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-800', 'icon' => 'fa-chalkboard-teacher'],
                            'accountant' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'icon' => 'fa-calculator'],
                            // Legacy roles
                            'super_admin' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'icon' => 'fa-crown'],
                            'admin' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-800', 'icon' => 'fa-user-shield'],
                            'manager' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'icon' => 'fa-user-tie']
                        ];
                        $config = $roleConfig[$data['user']->role] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-800', 'icon' => 'fa-user'];
                        ?>
                        <span class="inline-flex items-center px-4 py-2 <?php echo $config['bg'] . ' ' . $config['text']; ?> rounded-full text-sm font-bold">
                            <i class="fas <?php echo $config['icon']; ?> mr-2"></i>
                            <?php echo ucfirst(str_replace('_', ' ', $data['user']->role)); ?>
                        </span>
                    </div>

                    <!-- Upload Picture Form -->
                    <form action="<?php echo URL_ROOT; ?>/profile/uploadPicture" method="post" enctype="multipart/form-data" class="mb-6">
                        <label class="block cursor-pointer">
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center hover:border-indigo-500 hover:bg-indigo-50 transition">
                                <i class="fas fa-camera text-gray-400 text-2xl mb-2"></i>
                                <p class="text-sm text-gray-600 font-medium">Change Profile Picture</p>
                                <p class="text-xs text-gray-400 mt-1">Click to upload</p>
                            </div>
                            <input type="file" name="profile_picture" accept="image/*" class="hidden" onchange="this.form.submit()">
                        </label>
                    </form>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-indigo-600">
                                <?php echo date('d', strtotime($data['user']->created_at)); ?>
                            </div>
                            <div class="text-xs text-gray-500">Days Active</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">
                                <?php echo date('M', strtotime($data['user']->created_at)); ?>
                            </div>
                            <div class="text-xs text-gray-500">Joined Month</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Personal Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-indigo-600"></i>
                        Personal Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Full Name</p>
                                <p class="text-gray-900 font-semibold"><?php echo $data['user']->full_name; ?></p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-at text-green-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Username</p>
                                <p class="text-gray-900 font-semibold">@<?php echo $data['user']->username; ?></p>
                            </div>
                        </div>

                        <?php if ($data['user']->email): ?>
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-envelope text-blue-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Email</p>
                                <p class="text-gray-900 font-semibold"><?php echo $data['user']->email; ?></p>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($data['user']->phone): ?>
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-phone text-purple-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Phone</p>
                                <p class="text-gray-900 font-semibold"><?php echo $data['user']->phone; ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- School Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-cyan-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-university mr-2 text-blue-600"></i>
                        Institutional Assignment
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-map-marker-alt text-orange-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Assigned Campus</p>
                                <p class="text-gray-900 font-semibold"><?php echo $data['user']->campus_name ?? 'Not Assigned'; ?></p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-school text-red-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">School</p>
                                <p class="text-gray-900 font-semibold"><?php echo $data['user']->school_name ?? 'N/A'; ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Information Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-green-600"></i>
                        Activity Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar text-gray-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Member Since</p>
                                <p class="text-gray-900 font-semibold"><?php echo date('F j, Y', strtotime($data['user']->created_at)); ?></p>
                            </div>
                        </div>

                        <?php if ($data['user']->last_login): ?>
                        <div class="flex items-center p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="flex-shrink-0 w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-teal-600 text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-xs text-gray-500 font-medium">Last Login</p>
                                <p class="text-gray-900 font-semibold"><?php echo date('M j, Y g:i A', strtotime($data['user']->last_login)); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
