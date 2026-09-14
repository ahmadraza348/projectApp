console.log('Notification.js loaded');


/*
|--------------------------------------------------------------------------
| Existing unread notifications
|--------------------------------------------------------------------------
*/

let unreadCount = Number(
    window.initialUnreadNotificationCount || 0
);


/*
|--------------------------------------------------------------------------
| Update badge
|--------------------------------------------------------------------------
*/

function updateNotificationBadge() {

    const badge = document.getElementById('notificationBadge');

    if (!badge) {
        return;
    }

    if (unreadCount > 0) {

        badge.textContent = unreadCount > 99
            ? '99+'
            : unreadCount;

        badge.classList.remove('d-none');

    } else {

        badge.textContent = '0';
        badge.classList.add('d-none');

    }
}


/*
|--------------------------------------------------------------------------
| Add real-time notification
|--------------------------------------------------------------------------
*/

function addNotification(notification) {

    const list = document.getElementById('notificationList');

    if (!list) {
        return;
    }


    // Remove "No notifications"
    const emptyMessage = list.querySelector('.notification-empty');

    if (emptyMessage) {
        emptyMessage.remove();
    }


    const item = document.createElement('a');

    item.href = notification.task_link || '#';

    item.className = `
        dropdown-item
        px-3
        py-3
        border-bottom
        notification-item
        bg-light
        text-decoration-none
    `;

    item.innerHTML = `
        <div class="d-flex gap-3">

            <div class="text-primary pt-1">
                <i class="bi bi-check-circle-fill"></i>
            </div>

            <div>

                <div class="fw-semibold text-dark">
                    ${notification.message ?? 'You have a new notification.'}
                </div>

                <small class="text-muted">
                    Assigned by ${notification.assigned_by ?? 'System'}
                </small>

            </div>

        </div>
    `;


    list.prepend(item);
}


/*
|--------------------------------------------------------------------------
| Initial badge
|--------------------------------------------------------------------------
*/

updateNotificationBadge();


/*
|--------------------------------------------------------------------------
| Listen for real-time notifications
|--------------------------------------------------------------------------
*/

if (window.authUserId && window.Echo) {

    const channelName =
        `App.Models.User.${window.authUserId}`;

    console.log(
        '🔌 Listening on:',
        channelName
    );


    window.Echo
        .private(channelName)
        .notification((notification) => {

            console.log(
                '🔥 REAL-TIME NOTIFICATION'
            );

            console.log(notification);


            unreadCount++;

            updateNotificationBadge();

            addNotification(notification);

        });

} else {

    console.error(
        '❌ Echo or authUserId is missing'
    );

}
