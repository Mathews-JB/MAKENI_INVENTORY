<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New School</h2>
        
        <form action="<?php echo URL_ROOT; ?>/schools/add" method="post">
            <div class="space-y-6">
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">
                        School Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="<?php echo $name; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 <?php echo (!empty($name_err)) ? 'border-red-500' : ''; ?>" placeholder="Enter school name">
                    <span class="text-red-500 text-sm"><?php echo $name_err; ?></span>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">School Email</label>
                    <input type="email" name="email" value="<?php echo $email; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="school@example.com">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">School Phone</label>
                    <input type="text" name="phone" value="<?php echo $phone; ?>" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="+260 XXX XXX XXX">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2">School Address</label>
                    <textarea name="address" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600" placeholder="School physical address"><?php echo $address; ?></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 mt-8">
                <a href="<?php echo URL_ROOT; ?>/schools" class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                    Cancel
                </a>
                <button type="submit" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition transform hover:scale-105">
                    <i class="fas fa-save mr-2"></i>Save School
                </button>
            </div>
        </form>
    </div>
</div>
