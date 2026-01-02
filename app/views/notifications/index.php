<!-- Header Section -->
<div class="mb-8">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Notifications</h2>
            <p class="text-gray-500 mt-1">Stay updated with your latest activities and alerts</p>
        </div>
        <div class="flex space-x-3">
            <button onclick="markAllRead()" class="px-4 py-2 bg-indigo-100 text-indigo-700 rounded-lg hover:bg-indigo-200 transition font-medium flex items-center">
                <i class="fas fa-check-double mr-2"></i>
                Mark All Read
            </button>
            <button onclick="clearReadNotifications()" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium flex items-center">
                <i class="fas fa-trash-alt mr-2"></i>
                Clear Read
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<?php
$totalNotifications = count($data['notifications']);
$unreadCount = count(array_filter($data['notifications'], function($n) { return !$n->is_read; }));
$readCount = count(array_filter($data['notifications'], function($n) { return $n->is_read; }));
$todayCount = count(array_filter($data['notifications'], function($n) { 
    return date('Y-m-d', strtotime($n->created_at)) == date('Y-m-d'); 
}));
?>

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Notifications -->
    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-bell text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $totalNotifications; ?></h3>
        <p class="text-indigo-100 text-sm">Total Notifications</p>
    </div>

    <!-- Unread -->
    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-envelope text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $unreadCount; ?></h3>
        <p class="text-red-100 text-sm">Unread</p>
    </div>

    <!-- Read -->
    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-envelope-open text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $readCount; ?></h3>
        <p class="text-green-100 text-sm">Read</p>
    </div>

    <!-- Today -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg hover:shadow-xl transition transform hover:-translate-y-1">
        <div class="flex items-center justify-between mb-4">
            <div class="bg-white bg-opacity-20 rounded-lg p-3">
                <i class="fas fa-calendar-day text-2xl"></i>
            </div>
        </div>
        <h3 class="text-3xl font-bold mb-1"><?php echo $todayCount; ?></h3>
        <p class="text-blue-100 text-sm">Today</p>
    </div>
</div>

<!-- Notifications List -->
<?php if (empty($data['notifications'])): ?>
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-16 text-center">
        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-bell-slash text-5xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">No Notifications</h3>
        <p class="text-gray-500">You're all caught up! Check back later for updates.</p>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($data['notifications'] as $notif): ?>
            <?php
            $typeConfig = [
                'success' => ['bg' => 'bg-green-50', 'text' => 'text-green-600', 'icon' => 'fa-check-circle', 'border' => 'border-green-200'],
                'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'icon' => 'fa-exclamation-circle', 'border' => 'border-red-200'],
                'warning' => ['bg' => 'bg-yellow-50', 'text' => 'text-yellow-600', 'icon' => 'fa-exclamation-triangle', 'border' => 'border-yellow-200'],
                'info' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'icon' => 'fa-info-circle', 'border' => 'border-blue-200']
            ];
            $config = $typeConfig[$notif->type] ?? $typeConfig['info'];
            $readClass = $notif->is_read ? 'opacity-70 bg-gray-50' : 'bg-white border-l-4 border-indigo-500 shadow-sm';
            ?>
            <div class="notification-card group relative rounded-xl border border-gray-200 hover:shadow-md transition <?php echo $readClass; ?>">
                <div class="flex items-start p-6 space-x-4">
                    <!-- Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center <?php echo $config['bg'] . ' ' . $config['text']; ?> border-2 <?php echo $config['border']; ?>">
                        <i class="fas <?php echo $config['icon']; ?> text-lg"></i>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between mb-2">
                            <h4 class="text-base font-bold text-gray-900 pr-4">
                                <?php echo $notif->title; ?>
                                <?php if (!$notif->is_read): ?>
                                <span class="ml-2 inline-block w-2 h-2 bg-indigo-600 rounded-full animate-pulse"></span>
                                <?php endif; ?>
                            </h4>
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-500 whitespace-nowrap flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <?php echo date('M j, g:i A', strtotime($notif->created_at)); ?>
                                </span>
                                <button onclick="deleteNotification(<?php echo $notif->id; ?>)" 
                                        class="opacity-0 group-hover:opacity-100 p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-lg transition">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 mb-3 leading-relaxed"><?php echo $notif->message; ?></p>
                        
                        <div class="flex items-center justify-between">
                            <?php if ($notif->link): ?>
                                <a href="<?php echo URL_ROOT . $notif->link; ?>" 
                                   class="inline-flex items-center text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 px-3 py-1 rounded-lg transition">
                                    <i class="fas fa-external-link-alt mr-2"></i>
                                    View Details
                                </a>
                            <?php else: ?>
                                <div></div>
                            <?php endif; ?>
                            
                            <?php if ($notif->is_read): ?>
                                <span class="text-xs text-gray-400 flex items-center">
                                    <i class="fas fa-check mr-1"></i>
                                    Read
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
/* Smooth transition classes */
.notification-card {
    transition: all 0.5s ease-in-out;
}
.slide-out-right {
    transform: translateX(100%);
    opacity: 0;
    height: 0;
    margin: 0;
    padding: 0;
    border: none;
}
.faded-read {
    opacity: 0.7;
    background-color: #f9fafb; /* bg-gray-50 */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Override the global functions for this specific view
    // We wrap in DOMContentLoaded to ensure these run AFTER main.php's script tags (which are at the end of body)
    // Wait, main.php scripts run after the content is rendered, so simple sequence might overwrite this if `main.php` is after.
    // Actually, `main.php` scripts are at the bottom. `content` is injected before them.
    // So `main.php` definitions run LAST. 
    // So we MUST use a slight delay or window.onload to override them, OR rely on `var` hoisting vs function declarations.
    // Function declarations in main.php will overwrite window properties set here.
    // The safest way is to overwrite them *after* main.php runs. 
    // Since main.php runs at the end of body, we can't easily append after it from here.
    // But we can assign them in a `setTimeout` or `window.onload`.
    
    // Let's use a zero-delay timeout which usually pushes execution to end of stack (after synchronous scripts)
    setTimeout(() => {
        window.markAllRead = function() {
            // Optimistic UI Update
            const cards = document.querySelectorAll('.notification-card');
            let unreadCount = 0;

            cards.forEach(card => {
                // Find unread indicators
                const pulse = card.querySelector('.animate-pulse');
                if (pulse) {
                    unreadCount++;
                    pulse.remove(); // Remove blue dot
                    
                    // Update styles to "read" state
                    card.className = card.className.replace(/bg-white|border-l-4|border-indigo-500|shadow-sm/g, '');
                    card.classList.add('opacity-70', 'bg-gray-50', 'border', 'border-gray-200');
                    
                    // Add read text if not present
                    const footer = card.querySelector('.flex.items-center.justify-between');
                    if (footer && !footer.innerText.includes('Read')) {
                         const readSpan = document.createElement('span');
                         readSpan.className = 'text-xs text-gray-400 flex items-center';
                         readSpan.innerHTML = '<i class="fas fa-check mr-1"></i> Read';
                         footer.appendChild(readSpan);
                    }
                }
            });

            // Update Stats
            updateStats('read', unreadCount); // Move unread to read

            // Optimistically clear badge
            if (typeof updateNotificationBadge === 'function') {
                updateNotificationBadge(0);
            }

            fetch('<?php echo URL_ROOT; ?>/notifications/markAllRead', {
                method: 'POST'
            }).then(response => response.json())
              .then(data => {
                  if(data.success) {
                       showToast('success', 'All notifications marked as read');
                       if (typeof loadNotifications === 'function') {
                           loadNotifications(); // Sync dropdown
                       }
                  }
              });
        };

        window.clearReadNotifications = function() {
            if (confirm('Clear all read notifications?')) {
                const readCards = document.querySelectorAll('.notification-card.opacity-70, .notification-card.bg-gray-50, .faded-read');
                let clearedCount = 0;

                readCards.forEach((card, index) => {
                    clearedCount++;
                    // Stagger animation slightly
                    setTimeout(() => {
                        card.classList.add('slide-out-right');
                        setTimeout(() => card.remove(), 500); // Remove after animation
                    }, index * 50); 
                });

                // Update Stats
                updateStats('clear', clearedCount);

                fetch('<?php echo URL_ROOT; ?>/notifications/clearRead', {
                    method: 'POST'
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          showToast('success', 'Read notifications cleared');
                          if (typeof loadNotifications === 'function') {
                               loadNotifications(); // Sync dropdown
                          }
                          // Check if empty
                          setTimeout(() => {
                              const container = document.querySelector('.space-y-4');
                              if (container && container.children.length === 0) {
                                  location.reload(); // Reload to show "No Notifications" state easily
                              }
                          }, 600 + (readCards.length * 50));
                      }
                  });
            }
        };

        window.deleteNotification = function(id) {
            if (confirm('Delete this notification?')) {
                // Find card safely
                const card = document.querySelector(`button[onclick="deleteNotification(${id})"]`).closest('.notification-card');
                
                if (card) {
                    card.classList.add('slide-out-right');
                    setTimeout(() => card.remove(), 500);
                }

                fetch(`<?php echo URL_ROOT; ?>/notifications/delete/${id}`, {
                    method: 'POST'
                }).then(response => response.json())
                  .then(data => {
                       if(data.success) {
                           showToast('success', 'Notification deleted');
                           if (typeof loadNotifications === 'function') {
                               loadNotifications(); // Sync dropdown
                           }
                           // Simple stat update
                           const totalEl = document.querySelector('h3.text-3xl');
                           if(totalEl) totalEl.innerText = Math.max(0, parseInt(totalEl.innerText) - 1);
                       }
                  });
            }
        };
    }, 100); // Delay ensures this runs after main.php scripts
});

function updateStats(action, count) {
    // Order: Total (0), Unread (1), Read (2), Today (3)
    const stats = document.querySelectorAll('.grid.grid-cols-1 > div h3');
    
    if (stats.length === 4) {
        const total = stats[0];
        const unread = stats[1];
        const read = stats[2];
        
        let totalVal = parseInt(total.innerText);
        let unreadVal = parseInt(unread.innerText);
        let readVal = parseInt(read.innerText);

        if (action === 'read') {
            unread.innerText = 0;
            read.innerText = readVal + count; // Move unread count to read
        } else if (action === 'clear') {
            total.innerText = Math.max(0, totalVal - count);
            read.innerText = Math.max(0, readVal - count);
        }
    }
}
</script>
