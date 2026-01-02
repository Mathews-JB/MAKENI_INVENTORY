<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New school User</h2>
        
        <form action="<?php echo URL_ROOT; ?>/users/add" method="post">
            <div class="grid grid-cols-1 gap-6">
                <!-- Full Name -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Full Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="full_name" value="<?php echo $data['full_name']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['full_name_err'])) ? 'border-red-500' : ''; ?>" placeholder="Enter full name">
                    <span class="text-red-500 text-sm"><?php echo $data['full_name_err']; ?></span>
                </div>

                <!-- Username -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Username <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="username" value="<?php echo $data['username']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['username_err'])) ? 'border-red-500' : ''; ?>" placeholder="Choose username">
                    <span class="text-red-500 text-sm"><?php echo $data['username_err']; ?></span>
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" value="<?php echo $data['password']; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['password_err'])) ? 'border-red-500' : ''; ?>" placeholder="At least 6 characters">
                    <span class="text-red-500 text-sm"><?php echo $data['password_err']; ?></span>
                </div>

                <!-- Role -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Role</label>
                    <select name="role" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="teacher" <?php echo ($data['role'] == 'teacher') ? 'selected' : ''; ?>>Teacher / Faculty</option>
                        <option value="procurement officer" <?php echo ($data['role'] == 'procurement officer') ? 'selected' : ''; ?>>Procurement Officer</option>
                        <option value="store keeper" <?php echo ($data['role'] == 'store keeper') ? 'selected' : ''; ?>>Storekeeper</option>
                        <option value="accountant" <?php echo ($data['role'] == 'accountant') ? 'selected' : ''; ?>>Accountant</option>
                        
                        <?php if (Session::get('role') == 'administrator'): ?>
                            <option value="administrator" <?php echo ($data['role'] == 'administrator') ? 'selected' : ''; ?>>Administrator</option>
                        <?php endif; ?>
                    </select>
                </div>

                <?php if (Session::get('role') == 'administrator'): ?>
                <!-- School -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">School (Required for Campus)</label>
                    <select name="school_id" id="school_select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600">
                        <option value="">Select School</option>
                        <?php foreach ($data['schools'] as $school): ?>
                        <option value="<?php echo $school->id; ?>" <?php echo (isset($_POST['school_id']) && $_POST['school_id'] == $school->id) ? 'selected' : ''; ?>><?php echo $school->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Campus -->
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">Campus</label>
                    <select name="campus_id" id="campus_select" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($data['campus_err'])) ? 'border-red-500' : ''; ?>">
                        <option value="">Select Campus</option>
                    </select>
                    <span class="text-red-500 text-sm"><?php echo $data['campus_err'] ?? ''; ?></span>
                </div>
                <?php else: ?>
                    <!-- Hidden fields for non-administrator (auto-assigned in backend) -->
                    <input type="hidden" name="campus_id" value="<?php echo Session::get('campus_id'); ?>">
                    <div class="p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-lock mr-2 text-gray-400"></i>
                            User will be assigned to your campus automatically.
                        </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8">
                <a href="<?php echo URL_ROOT; ?>/users" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Save User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Load campuses function
function loadCampuses(schoolId, selectedCampusId = null) {
    const campusSelect = document.getElementById('campus_select');
    campusSelect.innerHTML = '<option value="">Loading...</option>';

    if (schoolId) {
        fetch('<?php echo URL_ROOT; ?>/users/getCampuses/' + schoolId)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Server returned invalid JSON:', text);
                        throw new Error('Invalid JSON response');
                    }
                });
            })
            .then(data => {
                campusSelect.innerHTML = '<option value="">Select Campus</option>';
                if (Array.isArray(data)) {
                    data.forEach(campus => {
                        const option = document.createElement('option');
                        option.value = campus.id;
                        option.textContent = campus.name;
                        if (selectedCampusId && campus.id == selectedCampusId) {
                            option.selected = true;
                        }
                        campusSelect.appendChild(option);
                    });
                } else {
                    console.error('Expected array but got:', data);
                }
            })
            .catch(err => {
                console.error('Error fetching campuses:', err);
                campusSelect.innerHTML = '<option value="">Error loading campuses (Check Console)</option>';
            });
    } else {
        campusSelect.innerHTML = '<option value="">Select Campus</option>';
    }
}

// Event listener
document.getElementById('school_select').addEventListener('change', function() {
    loadCampuses(this.value);
});

// Check on load (for validation errors)
document.addEventListener('DOMContentLoaded', function() {
    const schoolSelect = document.getElementById('school_select');
    const selectedCampus = '<?php echo $_POST['campus_id'] ?? ''; ?>';
    if (schoolSelect && schoolSelect.value) {
        loadCampuses(schoolSelect.value, selectedCampus);
    }
});
</script>
