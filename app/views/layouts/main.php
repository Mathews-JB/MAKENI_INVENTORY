<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> - <?php echo $data['title'] ?? 'Dashboard'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo URL_ROOT; ?>/css/style.css">
</head>
<body class="bg-white text-gray-800">
    <div class="flex h-screen overflow-hidden relative">
        <!-- Mobile Sidebar Overlay -->
        <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden transition-opacity duration-300"></div>

        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar w-64 flex-shrink-0 fixed inset-y-0 left-0 transform -translate-x-full md:relative md:translate-x-0 flex flex-col transition-all duration-300 z-50 shadow-2xl md:shadow-none">
            <div id="sidebarLogo" class="p-8 transition-all duration-300">
                <div class="flex items-center space-x-3 transition-all duration-300" id="logoContainer">
                    <div class="flex-shrink-0 bg-white p-1 rounded-lg">
                        <img src="<?php echo URL_ROOT; ?>/img/logo.png" alt="Logo" class="w-10 h-10 object-contain">
                    </div>
                    <div id="logoText">
                        <h1 class="text-white font-bold text-xl tracking-wide whitespace-nowrap">School IVM</h1>
                        <p class="text-gray-500 text-xs whitespace-nowrap">Inventory Management</p>
                    </div>
                </div>
            </div>

            <nav class="flex-1 px-4 overflow-y-auto space-y-2">
                <a href="<?php echo URL_ROOT; ?>/dashboard" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg <?php echo (!isset($data['active_tab']) || $data['active_tab'] == 'dashboard') ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
                <a href="<?php echo URL_ROOT; ?>/materials" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-book"></i>
                    <span>Materials</span>
                </a>
                <a href="<?php echo URL_ROOT; ?>/materials/stock" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-cubes"></i>
                    <span>Stock Levels</span>
                </a>
                <a href="<?php echo URL_ROOT; ?>/distributions" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-shipping-fast"></i>
                    <span>Distributions</span>
                </a>
                
                <?php if (Session::get('role') != 'teacher'): ?>
                <a href="<?php echo URL_ROOT; ?>/procurements" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-truck-loading"></i>
                    <span>Procurements</span>
                </a>
                <a href="<?php echo URL_ROOT; ?>/transactions" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-history"></i>
                    <span>Transactions</span>
                </a>
                <a href="<?php echo URL_ROOT; ?>/schools" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-school"></i>
                    <span>Schools</span>
                </a>
                <a href="<?php echo URL_ROOT; ?>/reports" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-chart-line"></i>
                    <span>Reports</span>
                </a>
                <?php endif; ?>
                <a href="<?php echo URL_ROOT; ?>/notifications" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg relative <?php echo (isset($data['active_tab']) && $data['active_tab'] == 'notifications') ? 'active' : ''; ?>">
                    <i class="fas fa-bell"></i>
                    <span>Notifications</span>
                    <span id="sidebarNotificationBadge" class="hidden absolute top-3 left-8 bg-red-500 rounded-full w-2 h-2"></span>
                </a>
                <?php if (Session::get('role') == 'administrator'): ?>
                <a href="<?php echo URL_ROOT; ?>/settings" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <?php endif; ?>
                <a href="<?php echo URL_ROOT; ?>/auth/logout" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg mt-8">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Logout</span>
                </a>

                <!-- Admin Section -->
                <?php if (Session::get('role') == 'administrator'): ?>
                <div class="pt-8 mt-4 border-t border-gray-800">
                    <p class="text-gray-600 text-xs uppercase px-4 mb-2">Administration</p>
                    <a href="<?php echo URL_ROOT; ?>/users" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                        <i class="fas fa-users-cog"></i>
                        <span>User Management</span>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/users/directory" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                        <i class="fas fa-address-book"></i>
                        <span>Campus Directory</span>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/campuses" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                        <i class="fas fa-map-marker-alt"></i>
                        <span>Campus Management</span>
                    </a>
                    <a href="<?php echo URL_ROOT; ?>/currency" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>Currency Settings</span>
                    </a>
                </div>
                <?php endif; ?>
            </nav>

            <div class="p-6">
                <a href="<?php echo URL_ROOT; ?>/profile" class="sidebar-link flex items-center space-x-4 px-4 py-3 rounded-lg">
                    <i class="fas fa-user-circle"></i>
                    <span>My Profile</span>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
            <!-- Top Navigation Bar -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-40 shadow-sm">
                <div class="px-6 py-3">
                    <div class="flex items-center justify-between">
                        <!-- Left: Page Title -->
                        <div class="flex items-center space-x-4">
                            <!-- Sidebar Toggle Button (Desktop) -->
                            <button id="sidebarToggle" class="hidden md:block text-gray-600 hover:text-gray-900 p-2 rounded-lg hover:bg-gray-100 transition shadow-sm border border-gray-100">
                                <i class="fas fa-bars text-xl"></i>
                            </button>
                            
                            <!-- Mobile Menu Toggle (Small Screens) -->
                            <button id="mobileMenuToggle" class="md:hidden text-indigo-600 hover:text-indigo-700 p-2 bg-indigo-50 rounded-lg transition shadow-sm border border-indigo-100">
                                <i class="fas fa-indent text-xl"></i>
                            </button>
                            
                            <div>
                                <h1 class="text-xl font-bold text-gray-900"><?php echo $data['title'] ?? 'Dashboard'; ?></h1>
                                <div class="flex items-center text-xs text-gray-500 mt-0.5">
                                    <i class="fas fa-home mr-1"></i>
                                    <span>Home</span>
                                    <i class="fas fa-chevron-right mx-1.5 text-[10px]"></i>
                                    <span class="text-gray-700 font-medium"><?php echo $data['title'] ?? 'Dashboard'; ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Search, Notifications, Profile -->
                        <div class="flex items-center space-x-3">
                            <!-- Search Bar -->
                            <div class="relative hidden lg:block">
                                <input type="text" placeholder="Search..." 
                                       class="w-64 pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 text-gray-900 placeholder-gray-400 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent focus:bg-white transition">
                                <i class="fas fa-search absolute left-3 top-2.5 text-gray-400"></i>
                            </div>

                            <!-- Notifications -->
                            <div class="relative">
                                <button onclick="toggleNotifications()" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition">
                                    <i class="fas fa-bell text-lg"></i>
                                    <span id="topNotificationBadge" class="hidden absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full min-w-[20px] h-5 flex items-center justify-center px-1.5 shadow-lg"></span>
                                </button>
                            </div>

                            <!-- User Profile Dropdown -->
                            <div class="relative">
                                <?php 
                                $userModel = new User();
                                $currentUser = $userModel->getUserById(Session::get('user_id'));
                                ?>
                                <button onclick="toggleProfileMenu()" class="flex items-center space-x-2 px-3 py-2 hover:bg-gray-100 rounded-lg transition">
                                    <?php if ($currentUser && $currentUser->profile_picture): ?>
                                        <img src="<?php echo URL_ROOT; ?>/<?php echo $currentUser->profile_picture; ?>" 
                                             alt="Profile" class="w-9 h-9 rounded-full object-cover border-2 border-gray-200">
                                    <?php else: ?>
                                        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full w-9 h-9 flex items-center justify-center text-white font-semibold text-sm shadow-sm">
                                            <?php echo strtoupper(substr(Session::get('full_name'), 0, 1)); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="hidden md:block text-left">
                                        <p class="text-sm font-semibold text-gray-900 leading-tight"><?php echo Session::get('full_name'); ?></p>
                                        <p class="text-xs text-gray-500"><?php echo ucwords(Session::get('role')); ?></p>
                                    </div>
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </button>

                                <!-- Profile Dropdown Menu -->
                                <div id="profileMenu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-200 py-2 overflow-hidden">
                                    <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-purple-50 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-900"><?php echo Session::get('full_name'); ?></p>
                                        <p class="text-xs text-gray-600"><?php echo Session::get('email'); ?></p>
                                    </div>
                                    <a href="<?php echo URL_ROOT; ?>/profile" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-user-circle mr-3 text-indigo-500 w-4"></i>
                                        My Profile
                                    </a>
                                    <a href="<?php echo URL_ROOT; ?>/settings" class="flex items-center px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fas fa-cog mr-3 text-gray-500 w-4"></i>
                                        Settings
                                    </a>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <a href="<?php echo URL_ROOT; ?>/auth/logout" class="flex items-center px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                        <i class="fas fa-sign-out-alt mr-3 w-4"></i>
                                        Logout
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto px-4 md:px-8 pb-8">
                <!-- Notifications Menu (Hidden by default) -->
                <div id="notificationsMenu" class="hidden absolute right-8 top-24 w-96 bg-white rounded-xl shadow-2xl border border-gray-100 z-50">
                    <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                        <h3 class="font-bold text-gray-800">Notifications</h3>
                        <button onclick="markAllRead()" class="text-xs text-blue-600 font-medium">Mark all read</button>
                    </div>
                    <div id="notificationsList" class="max-h-96 overflow-y-auto"></div>
                </div>

                <?php 
                $successMessage = Session::flash('success');
                $errorMessage = Session::flash('error', '', 'alert alert-danger');
                ?>
                
                <?php echo isset($content) ? $content : ''; ?>
            </main>
        </div>
    </div>

    <!-- Professional Toast Notification -->
    <div id="toastNotification" class="fixed top-24 right-8 transform translate-x-full transition-all duration-500 z-50" style="min-width: 400px;">
        <div class="bg-white rounded-2xl shadow-2xl border-l-4 overflow-hidden" id="toastCard">
            <div class="p-6 flex items-start gap-4">
                <!-- Icon -->
                <div id="toastIcon" class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center">
                    <!-- Success Icon -->
                    <svg id="successIcon" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <!-- Error Icon -->
                    <svg id="errorIcon" class="w-6 h-6 text-white hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                
                <!-- Content -->
                <div class="flex-1">
                    <h3 id="toastTitle" class="text-lg font-bold text-gray-900 mb-1"></h3>
                    <p id="toastMessage" class="text-sm text-gray-600"></p>
                </div>
                
                <!-- Close Button -->
                <button onclick="closeToast()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="h-1 bg-gray-100">
                <div id="toastProgress" class="h-full transition-all duration-3000 ease-linear" style="width: 100%"></div>
            </div>
        </div>
    </div>

    <style>
    @keyframes slideIn {
        from { transform: translateX(100%); }
        to { transform: translateX(0); }
    }
    
    @keyframes slideOut {
        from { transform: translateX(0); }
        to { transform: translateX(100%); }
    }
    
    .duration-3000 {
        transition-duration: 3000ms;
    }

    @media (max-width: 768px) {
        .sidebar-open #sidebar {
            transform: translateX(0);
        }
        .sidebar-open #sidebarOverlay {
            display: block;
        }
        #toastNotification {
            left: 1rem;
            right: 1rem;
            min-width: auto !important;
        }
    }
    </style>

    <script>
    let toastTimeout;
    
    function showToast(type, message) {
        const toast = document.getElementById('toastNotification');
        const card = document.getElementById('toastCard');
        const icon = document.getElementById('toastIcon');
        const title = document.getElementById('toastTitle');
        const messageEl = document.getElementById('toastMessage');
        const progress = document.getElementById('toastProgress');
        const successIcon = document.getElementById('successIcon');
        const errorIcon = document.getElementById('errorIcon');
        
        // Clear any existing timeout
        if (toastTimeout) clearTimeout(toastTimeout);
        
        // Configure based on type
        if (type === 'success') {
            icon.className = 'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-gradient-to-br from-green-400 to-emerald-600';
            card.className = 'bg-white rounded-2xl shadow-2xl border-l-4 border-green-500 overflow-hidden';
            progress.className = 'h-full bg-gradient-to-r from-green-400 to-emerald-600 transition-all duration-3000 ease-linear';
            title.textContent = 'Success!';
            successIcon.classList.remove('hidden');
            errorIcon.classList.add('hidden');
        } else {
            icon.className = 'flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-gradient-to-br from-red-400 to-rose-600';
            card.className = 'bg-white rounded-2xl shadow-2xl border-l-4 border-red-500 overflow-hidden';
            progress.className = 'h-full bg-gradient-to-r from-red-400 to-rose-600 transition-all duration-3000 ease-linear';
            title.textContent = 'Error!';
            successIcon.classList.add('hidden');
            errorIcon.classList.remove('hidden');
        }
        
        messageEl.innerHTML = message;
        
        // Show toast
        toast.style.transform = 'translateX(0)';
        
        // Start progress bar
        setTimeout(() => {
            progress.style.width = '0%';
        }, 100);
        
        // Auto dismiss after 7 seconds
        toastTimeout = setTimeout(() => {
            closeToast();
        }, 7000);
    }
    
    function closeToast() {
        const toast = document.getElementById('toastNotification');
        const progress = document.getElementById('toastProgress');
        
        toast.style.transform = 'translateX(100%)';
        
        // Reset progress bar after animation
        setTimeout(() => {
            progress.style.width = '100%';
        }, 500);
    }
    
    // Check for flash messages on page load
    <?php if ($successMessage): ?>
        setTimeout(() => showToast('success', '<?php echo addslashes($successMessage); ?>'), 100);
    <?php endif; ?>
    
    <?php if ($errorMessage): ?>
        setTimeout(() => showToast('error', '<?php echo addslashes($errorMessage); ?>'), 100);
    <?php endif; ?>
    </script>

    <script>
    // Notifications System
    let notificationsOpen = false;

    function toggleNotifications() {
        const menu = document.getElementById('notificationsMenu');
        notificationsOpen = !notificationsOpen;
        
        if (notificationsOpen) {
            menu.classList.remove('hidden');
            loadNotifications();
        } else {
            menu.classList.add('hidden');
        }
    }

    function loadNotifications() {
        fetch('<?php echo URL_ROOT; ?>/notifications/get')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    updateNotificationBadge(data.unread_count);
                    renderNotifications(data.notifications);
                }
            })
            .catch(error => console.error('Error loading notifications:', error));
    }

    function updateNotificationBadge(count) {
        const topBadge = document.getElementById('topNotificationBadge');
        const sidebarBadge = document.getElementById('sidebarNotificationBadge');
        
        if (count > 0) {
            // Top bar badge - show count
            topBadge.textContent = count > 99 ? '99+' : count;
            topBadge.classList.remove('hidden');
            
            // Sidebar badge - show dot
            if (sidebarBadge) {
                sidebarBadge.classList.remove('hidden');
            }
        } else {
            topBadge.classList.add('hidden');
            if (sidebarBadge) {
                sidebarBadge.classList.add('hidden');
            }
        }
    }

    function renderNotifications(notifications) {
        const list = document.getElementById('notificationsList');
        
        if (notifications.length === 0) {
            list.innerHTML = `
                <div class="p-8 text-center text-gray-500">
                    <i class="fas fa-bell-slash text-4xl mb-2 text-gray-300"></i>
                    <p>No notifications</p>
                </div>
            `;
            return;
        }

        list.innerHTML = notifications.map(notif => {
            const typeColors = {
                'success': 'bg-green-50 border-green-200 text-green-800',
                'error': 'bg-red-50 border-red-200 text-red-800',
                'warning': 'bg-yellow-50 border-yellow-200 text-yellow-800',
                'info': 'bg-blue-50 border-blue-200 text-blue-800'
            };
            const typeIcons = {
                'success': 'fa-check-circle text-green-500',
                'error': 'fa-exclamation-circle text-red-500',
                'warning': 'fa-exclamation-triangle text-yellow-500',
                'info': 'fa-info-circle text-blue-500'
            };
            const colorClass = typeColors[notif.type] || typeColors['info'];
            const iconClass = typeIcons[notif.type] || typeIcons['info'];
            const readClass = notif.is_read == 1 ? 'opacity-60' : '';
            
            return `
                <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition ${readClass}" onclick="handleNotificationClick(${notif.id}, '${notif.link || ''}')">
                    <div class="flex items-start space-x-3">
                        <i class="fas ${iconClass} text-lg mt-1"></i>
                        <div class="flex-1">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold text-sm text-gray-900">${notif.title}</h4>
                                <button onclick="event.stopPropagation(); deleteNotification(${notif.id})" class="text-gray-400 hover:text-red-500">
                                    <i class="fas fa-times text-xs"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-600 mt-1">${notif.message}</p>
                            <p class="text-xs text-gray-400 mt-1">${timeAgo(notif.created_at)}</p>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    function handleNotificationClick(id, link) {
        markNotificationRead(id);
        if (link && link !== 'null') {
            window.location.href = '<?php echo URL_ROOT; ?>' + link;
        }
    }

    function markNotificationRead(id) {
        fetch(`<?php echo URL_ROOT; ?>/notifications/markRead/${id}`, {
            method: 'POST'
        }).then(() => loadNotifications());
    }

    function markAllRead() {
        // Optimistic UI update
        updateNotificationBadge(0);
        
        // Mark visually as read in the dropdown
        const list = document.getElementById('notificationsList');
        const unreadItems = list.querySelectorAll('.bg-blue-50, .bg-green-50, .bg-yellow-50, .bg-red-50'); // All colored backgrounds
        unreadItems.forEach(item => {
            // Remove specific background classes and add opacity to indicate read
            item.classList.remove('bg-blue-50', 'bg-green-50', 'bg-yellow-50', 'bg-red-50', 'border-blue-200', 'border-green-200', 'border-yellow-200', 'border-red-200');
            item.classList.add('opacity-60', 'bg-white'); // Reset to white/read style
        });

        // Backend call
        fetch('<?php echo URL_ROOT; ?>/notifications/markAllRead', {
            method: 'POST'
        }).then(() => {
            // Reload to ensure sync, but the immediate feedback is already done
            loadNotifications();
            showToast('success', 'All notifications marked as read');
        });
    }

    function deleteNotification(id) {
        fetch(`<?php echo URL_ROOT; ?>/notifications/delete/${id}`, {
            method: 'POST'
        }).then(() => loadNotifications());
    }

    function clearReadNotifications() {
        if (confirm('Clear all read notifications?')) {
            fetch('<?php echo URL_ROOT; ?>/notifications/clearRead', {
                method: 'POST'
            }).then(() => loadNotifications());
        }
    }

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const seconds = Math.floor((new Date() - date) / 1000);
        
        if (seconds < 60) return 'Just now';
        if (seconds < 3600) return Math.floor(seconds / 60) + 'm ago';
        if (seconds < 86400) return Math.floor(seconds / 3600) + 'h ago';
        if (seconds < 604800) return Math.floor(seconds / 86400) + 'd ago';
        return date.toLocaleDateString();
    }


    // Profile Menu Toggle
    function toggleProfileMenu() {
        const menu = document.getElementById('profileMenu');
        menu.classList.toggle('hidden');
    }

    // Close menus when clicking outside
    document.addEventListener('click', function(event) {
        const notifMenu = document.getElementById('notificationsMenu');
        const profileMenu = document.getElementById('profileMenu');
        const notifButton = event.target.closest('button[onclick="toggleNotifications()"]');
        const profileButton = event.target.closest('button[onclick="toggleProfileMenu()"]');
        
        if (notificationsOpen && !notifMenu.contains(event.target) && !notifButton) {
            toggleNotifications();
        }
        
        if (!profileMenu.classList.contains('hidden') && !profileMenu.contains(event.target) && !profileButton) {
            profileMenu.classList.add('hidden');
        }
    });

    // Load notifications on page load and refresh every 30 seconds
    document.addEventListener('DOMContentLoaded', function() {
        loadNotifications();
        setInterval(loadNotifications, 30000);
    });

    // Sidebar Toggle Functionality
    // Sidebar Toggle Functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarTexts = sidebar.querySelectorAll('span');
    const sidebarLogo = document.getElementById('sidebarLogo'); // ID added
    const logoText = document.getElementById('logoText');       // ID added
    const logoContainer = document.getElementById('logoContainer');
    let sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';

    // Apply saved state on load
    if (sidebarCollapsed) {
        collapseSidebar();
    }

    sidebarToggle.addEventListener('click', function() {
        if (sidebarCollapsed) {
            expandSidebar();
        } else {
            collapseSidebar();
        }
    });

    function collapseSidebar() {
        sidebar.classList.remove('w-64');
        sidebar.classList.add('w-20');
        
        // Hide Text Spans
        sidebarTexts.forEach(text => {
            text.classList.add('hidden');
        });
        
        // Handle Logo Area
        logoText.classList.add('hidden');
        sidebarLogo.classList.remove('p-8');
        sidebarLogo.classList.add('p-4', 'flex', 'justify-center');
        logoContainer.classList.remove('space-x-3');
        
        // Rotate icon
        sidebarToggle.querySelector('i').style.transform = 'rotate(90deg)';
        sidebarCollapsed = true;
        localStorage.setItem('sidebarCollapsed', 'true');
    }

    function expandSidebar() {
        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-64');
        
        // Show Text Spans
        sidebarTexts.forEach(text => {
            text.classList.remove('hidden');
        });

        // Handle Logo Area
        logoText.classList.remove('hidden');
        sidebarLogo.classList.remove('p-4', 'flex', 'justify-center');
        sidebarLogo.classList.add('p-8');
        logoContainer.classList.add('space-x-3');

        // Reset icon rotation
        sidebarToggle.querySelector('i').style.transform = 'rotate(0deg)';
        sidebarCollapsed = false;
        localStorage.setItem('sidebarCollapsed', 'false');
    }

    // Mobile Sidebar Toggle
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const body = document.body;

    function toggleMobileMenu() {
        body.classList.toggle('sidebar-open');
        sidebarOverlay.classList.toggle('hidden');
        
        // Change icon based on state
        const icon = mobileMenuToggle.querySelector('i');
        if (body.classList.contains('sidebar-open')) {
            icon.classList.remove('fa-indent');
            icon.classList.add('fa-times');
        } else {
            icon.classList.remove('fa-times');
            icon.classList.add('fa-indent');
        }
    }

    mobileMenuToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMobileMenu();
    });

    sidebarOverlay.addEventListener('click', function() {
        if (body.classList.contains('sidebar-open')) {
            toggleMobileMenu();
        }
    });

    // Close mobile menu on Esc
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' && body.classList.contains('sidebar-open')) {
            toggleMobileMenu();
        }
    });

    // Close mobile menu when clicking nav links on mobile
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 768 && body.classList.contains('sidebar-open')) {
                toggleMobileMenu();
            }
        });
    });
    </script>
</body>
</html>
