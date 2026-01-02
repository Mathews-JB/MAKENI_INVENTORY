<div class="max-w-4xl mx-auto px-4 py-8">
    <?php if (isset($data['success']) && $data['success']): ?>
        <!-- Success State -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in-up">
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-center">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center mx-auto mb-6 animate-bounce">
                    <i class="fas fa-paper-plane text-4xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">Invitation Sent Successfully!</h2>
                <p class="text-green-50 text-lg">Your school staff member is one step away from joining.</p>
            </div>
            
            <div class="p-8 md:p-12">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-xl p-6 border border-gray-100">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">Invitation Details</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-4">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Sent to</p>
                                        <p class="font-medium text-gray-900"><?php echo $data['email'] ?? 'undefined'; ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 mr-4">
                                        <i class="fas fa-user-tag"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Role Assigned</p>
                                        <p class="font-medium text-gray-900"><?php echo ucfirst(str_replace('_', ' ', $data['role'] ?? 'teacher')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="bg-indigo-50 rounded-xl p-6 border border-indigo-100">
                            <label class="block text-xs font-bold text-indigo-900 uppercase tracking-wider mb-3">
                                <i class="fas fa-link mr-2"></i>Registration Link
                            </label>
                            <p class="text-sm text-indigo-600 mb-4 leading-relaxed">
                                Since you are on a local server, emails may not be delivered. Share this link directly with the user:
                            </p>
                            <div class="relative group">
                                <input type="text" id="inviteLink" readonly 
                                       class="block w-full text-sm text-gray-600 bg-white border border-indigo-200 rounded-lg p-4 pr-12 focus:ring-2 focus:ring-indigo-500 focus:border-transparent font-mono shadow-sm" 
                                       value="<?php echo $data['invite_link']; ?>">
                                <button onclick="copyLink()" class="absolute right-2 top-2 p-2 bg-indigo-100 text-indigo-600 rounded-md hover:bg-indigo-600 hover:text-white transition-colors" title="Copy to Clipboard">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <a href="<?php echo URL_ROOT; ?>/users" class="text-gray-500 hover:text-gray-900 font-medium transition flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i> Back to User Management
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function copyLink() {
            var copyText = document.getElementById("inviteLink");
            copyText.select();
            copyText.setSelectionRange(0, 99999); 
            document.execCommand("copy");
            
            // Visual feedback
            const btn = document.querySelector('button[title="Copy to Clipboard"]');
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.add('bg-green-100', 'text-green-600');
            setTimeout(() => {
                btn.innerHTML = originalIcon;
                btn.classList.remove('bg-green-100', 'text-green-600');
            }, 2000);
        }
        </script>

    <?php else: ?>
        <!-- Invitation Form -->
        <div class="flex flex-col md:flex-row gap-8">
            <!-- Left Info Panel -->
            <div class="md:w-1/3">
                <div class="bg-gradient-to-br from-indigo-800 to-purple-900 rounded-2xl p-8 text-white shadow-xl h-full relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-purple-500/20 rounded-full blur-3xl"></div>
                    
                    <h2 class="text-3xl font-bold mb-4 relative z-10">Invite School Staff</h2>
                    <p class="text-indigo-100 mb-8 relative z-10 leading-relaxed">
                        Expand your school administration and faculty by sending secure invitations. New users will receive a link to set up their own credentials.
                    </p>

                    <div class="space-y-6 relative z-10">
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mt-1 mr-4 shrink-0">
                                <i class="fas fa-shield-alt text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Secure Access</h3>
                                <p class="text-xs text-indigo-200 mt-1">Unique 48-hour expiration tokens for secure account creation.</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center mt-1 mr-4 shrink-0">
                                <i class="fas fa-school text-sm"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-white">Campus Focused</h3>
                                <p class="text-xs text-indigo-200 mt-1">Pre-assign roles and campuses to ensure proper resource management.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Panel -->
            <div class="md:w-2/3">
                <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-8">
                    <form action="<?php echo URL_ROOT; ?>/users/invite" method="POST">
                        
                        <!-- Email Input -->
                        <div class="mb-8 group">
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-2 group-focus-within:text-indigo-600 transition-colors">
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 group-focus-within:text-indigo-500 transition-colors"></i>
                                </div>
                                <input type="email" name="email" id="email" required
                                       class="block w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all <?php echo (!empty($data['email_err'])) ? 'border-red-500 bg-red-50' : ''; ?>"
                                       value="<?php echo $data['email']; ?>" placeholder="staff@school.edu">
                            </div>
                            <?php if (!empty($data['email_err'])): ?>
                                <p class="mt-2 text-sm text-red-600 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i> <?php echo $data['email_err']; ?></p>
                            <?php endif; ?>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-8">
                            <label for="role" class="block text-sm font-bold text-gray-700 mb-2">Assign Role <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-user-tag text-gray-400"></i>
                                </div>
                                <select id="role" name="role" class="appearance-none block w-full pl-11 pr-10 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all cursor-pointer">
                                    <option value="teacher" <?php echo ($data['role'] == 'teacher') ? 'selected' : ''; ?>>Teacher / Faculty (Resource Requests)</option>
                                    <option value="store keeper" <?php echo ($data['role'] == 'store keeper') ? 'selected' : ''; ?>>Storekeeper (Stock Management)</option>
                                    <option value="procurement officer" <?php echo ($data['role'] == 'procurement officer') ? 'selected' : ''; ?>>Procurement Officer (Buying & Vendors)</option>
                                    <option value="accountant" <?php echo ($data['role'] == 'accountant') ? 'selected' : ''; ?>>Accountant (Finance & Audits)</option>
                                    <option value="administrator" <?php echo ($data['role'] == 'administrator') ? 'selected' : ''; ?>>Administrator (Full Campus Access)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-xs"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Organization Context -->
                        <div class="p-6 bg-gray-50 rounded-xl border border-gray-200 mb-8">
                            <h3 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-4">School Context</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="school_id" class="block text-sm font-medium text-gray-700 mb-2">School</label>
                                    <select id="school_id" name="school_id" class="block w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Select School</option>
                                        <?php foreach($data['schools'] as $school): ?>
                                            <option value="<?php echo $school->id; ?>"><?php echo $school->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label for="campus_id" class="block text-sm font-medium text-gray-700 mb-2">Campus</label>
                                    <select id="campus_id" name="campus_id" class="block w-full border-gray-300 rounded-lg text-sm focus:ring-indigo-500 focus:border-indigo-500 <?php echo (!empty($data['campus_err'])) ? 'border-red-500' : ''; ?>">
                                        <option value="">Select Campus</option>
                                    </select>
                                    <?php if (!empty($data['campus_err'])): ?>
                                        <p class="mt-1 text-xs text-red-600"><?php echo $data['campus_err']; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-between pt-2">
                            <a href="<?php echo URL_ROOT; ?>/users" class="text-gray-500 hover:text-gray-800 font-medium transition px-4 py-2 rounded-lg hover:bg-gray-100">
                                Cancel
                            </a>
                            <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-3 rounded-xl font-bold shadow-lg hover:shadow-indigo-500/30 hover:from-indigo-700 hover:to-purple-700 transition transform hover:-translate-y-0.5 active:translate-y-0">
                                Send Invitation <i class="fas fa-arrow-right ml-2 opacity-80"></i>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <style>
            @keyframes fadeInUp {
                from { opacity: 0; transform: translateY(20px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .animate-fade-in-up {
                animation: fadeInUp 0.5s ease-out;
            }
        </style>
    <?php endif; ?>
</div>

<script>
document.getElementById('school_id').addEventListener('change', function() {
    var schoolId = this.value;
    var campusSelect = document.getElementById('campus_id');
    
    // Clear current options
    campusSelect.innerHTML = '<option value="">Loading...</option>';
    
    if(schoolId) {
        fetch('<?php echo URL_ROOT; ?>/users/getCampuses/' + schoolId)
            .then(response => response.json())
            .then(data => {
                campusSelect.innerHTML = '<option value="">Select Campus</option>';
                data.forEach(campus => {
                    var option = document.createElement('option');
                    option.value = campus.id;
                    option.text = campus.name;
                    campusSelect.appendChild(option);
                });
            });
    } else {
        campusSelect.innerHTML = '<option value="">Select Campus</option>';
    }
});
</script>
