<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Staff Invitations</h2>
            <p class="text-gray-500 mt-1">Track and manage sent school staff invitations</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?php echo URL_ROOT; ?>/users/invite" class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition shadow-lg flex items-center">
                <i class="fas fa-paper-plane mr-2"></i>
                Send New Invite
            </a>
            <a href="<?php echo URL_ROOT; ?>/users" class="bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-lg hover:bg-gray-50 transition shadow-sm flex items-center">
                <i class="fas fa-users mr-2"></i>
                Back to Users
            </a>
        </div>
    </div>
</div>

<!-- Invitations Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <?php if (count($data['invitations']) > 0): ?>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Recipient Email</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Role & Campus</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sent Date</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Due Date (Expires)</th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php foreach ($data['invitations'] as $invite): ?>
                <?php 
                    $isExpired = strtotime($invite->expires_at) < time();
                    $statusClass = 'bg-yellow-100 text-yellow-800 border-yellow-200';
                    $statusIcon = 'fa-clock';
                    $statusText = 'Pending';

                    if ($invite->status == 'used') {
                        $statusClass = 'bg-green-100 text-green-800 border-green-200';
                        $statusIcon = 'fa-check-circle';
                        $statusText = 'Accepted';
                    } elseif ($invite->status == 'expired' || ($invite->status == 'pending' && $isExpired)) {
                        $statusClass = 'bg-red-100 text-red-800 border-red-200';
                        $statusIcon = 'fa-exclamation-circle';
                        $statusText = 'Expired';
                    }
                ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo $invite->email; ?></div>
                        <div class="text-xs text-gray-500">By: <?php echo $invite->creator_name; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 font-semibold"><?php echo ucfirst(str_replace('_', ' ', $invite->role)); ?></div>
                        <div class="text-xs text-gray-500"><?php echo $invite->campus_name ?? 'All Campuses'; ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full border <?php echo $statusClass; ?>">
                            <i class="fas <?php echo $statusIcon; ?> mr-1"></i>
                            <?php echo $statusText; ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <?php echo date('M d, Y h:i A', strtotime($invite->created_at)); ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span class="<?php echo $isExpired && $invite->status == 'pending' ? 'text-red-600 font-bold' : 'text-gray-900'; ?>">
                            <?php echo date('M d, Y h:i A', strtotime($invite->expires_at)); ?>
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <?php if ($invite->status == 'pending' && !$isExpired): ?>
                            <div class="flex items-center justify-center space-x-2">
                                <button onclick="copyToClipboard('<?php echo URL_ROOT; ?>/auth/accept_invite/<?php echo $invite->token; ?>')" 
                                        class="text-indigo-600 hover:text-indigo-900 hover:bg-indigo-50 p-2 rounded-lg transition" title="Copy Link">
                                    <i class="fas fa-copy"></i>
                                </button>
                                <form method="POST" action="<?php echo URL_ROOT; ?>/users/deleteInvitation/<?php echo $invite->id; ?>" 
                                      class="inline" onsubmit="return confirm('Revoke this invitation? The link will no longer work.');">
                                    <button type="submit" class="text-red-600 hover:text-red-900 hover:bg-red-50 p-2 rounded-lg transition" title="Revoke">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        <?php else: ?>
                            <form method="POST" action="<?php echo URL_ROOT; ?>/users/deleteInvitation/<?php echo $invite->id; ?>" 
                                  class="inline" onsubmit="return confirm('Remove this record?');">
                                <button type="submit" class="text-gray-400 hover:text-red-600 p-2 rounded-lg transition" title="Remove Record">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <div class="text-center py-16">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-inbox text-4xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Invitations Found</h3>
        <p class="text-gray-500 mb-6">You haven't sent any invitations yet.</p>
        <a href="<?php echo URL_ROOT; ?>/users/invite" class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-lg">
            <i class="fas fa-paper-plane mr-2"></i>
            Send First Invite
        </a>
    </div>
    <?php endif; ?>
</div>

<script>
function copyToClipboard(text) {
    var dummy = document.createElement("textarea");
    document.body.appendChild(dummy);
    dummy.value = text;
    dummy.select();
    document.execCommand("copy");
    document.body.removeChild(dummy);
    alert("Invitation link copied!");
}
</script>
