<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-semibold text-gray-900">
                        Notifikasi
                    </h2>
                    <div>
                        <button id="markAllRead"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Tandai Semua Dibaca
                        </button>
                    </div>
                </div>

                <div id="notificationList" class="space-y-4">
                    <!-- Notifications will be loaded here -->
                </div>

                <template id="notificationTemplate">
                    <div class="notification-item p-4 rounded-lg border transition-colors duration-200" data-id="">
                        <div class="flex items-start justify-between">
                            <div class="flex space-x-3">
                                <div
                                    class="notification-icon flex-shrink-0 w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                    <span class="text-xl">🔔</span>
                                </div>
                                <div>
                                    <h3 class="notification-title text-sm font-medium text-gray-900"></h3>
                                    <p class="notification-message mt-1 text-sm text-gray-500"></p>
                                    <p class="notification-time mt-1 text-xs text-gray-400"></p>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0 flex">
                                <button
                                    class="mark-read bg-white rounded-md text-sm font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none">
                                    Tandai Dibaca
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const notificationList = document.getElementById('notificationList');
                const template = document.getElementById('notificationTemplate');
                let notifications = [];

                function loadNotifications() {
                    fetch('/api/admin/notifications')
                        .then(response => response.json())
                        .then(data => {
                            notifications = data;
                            renderNotifications();
                        })
                        .catch(error => console.error('Error loading notifications:', error));
                }

                function renderNotifications() {
                    notificationList.innerHTML = '';
                    notifications.forEach(notification => {
                        const clone = template.content.cloneNode(true);
                        const item = clone.querySelector('.notification-item');

                        item.dataset.id = notification.id;
                        if (notification.read) {
                            item.classList.add('bg-gray-50');
                        } else {
                            item.classList.add('bg-white');
                        }

                        const icon = notification.icon || '🔔';
                        item.querySelector('.notification-icon span').textContent = icon;
                        item.querySelector('.notification-title').textContent = notification.title;
                        item.querySelector('.notification-message').textContent = notification.message;

                        const timeAgo = new Date(notification.created_at).toLocaleString();
                        item.querySelector('.notification-time').textContent = timeAgo;

                        if (notification.url) {
                            item.style.cursor = 'pointer';
                            item.addEventListener('click', (e) => {
                                if (!e.target.closest('.mark-read')) {
                                    window.location.href = notification.url;
                                }
                            });
                        }

                        const markReadBtn = item.querySelector('.mark-read');
                        if (notification.read) {
                            markReadBtn.style.display = 'none';
                        } else {
                            markReadBtn.addEventListener('click', (e) => {
                                e.stopPropagation();
                                markAsRead(notification.id);
                            });
                        }

                        notificationList.appendChild(clone);
                    });
                }

                function markAsRead(id) {
                    fetch(`/api/admin/notifications/${id}/read`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(() => loadNotifications())
                        .catch(error => console.error('Error marking notification as read:', error));
                }

                function markAllAsRead() {
                    fetch('/api/admin/notifications/mark-all-read', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(() => loadNotifications())
                        .catch(error => console.error('Error marking all notifications as read:', error));
                }

                document.getElementById('markAllRead').addEventListener('click', markAllAsRead);

                // Load notifications initially and refresh every 30 seconds
                loadNotifications();
                setInterval(loadNotifications, 30000);
            });
        </script>
    @endpush
</x-app-layout>