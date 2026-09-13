<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Dashboard - Project Manager</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="{{ asset("frontend/assets/css/style.css") }}" rel="stylesheet">
</head>
<body>

@include('layouts.sidebar')
@if(session('success'))   
        <script>
          alert(@json(session('success'))); 
        </script>     
@endif
@yield('content')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset("frontend/assets/js/main.js") }}"></script>
@stack('scripts')
@auth
<script>
  (() => {
    const list = document.getElementById('notification-list');
    const count = document.getElementById('notification-count');
    if (!list || !count) return;

    const refreshNotifications = () => fetch('{{ route('notifications.index') }}', {
      headers: { 'Accept': 'application/json' }
    }).then(response => response.json()).then(payload => {
      count.textContent = payload.unread_count;
      count.classList.toggle('d-none', payload.unread_count === 0);
      list.replaceChildren();
      if (!payload.notifications.length) {
        const empty = document.createElement('span');
        empty.className = 'dropdown-item text-muted';
        empty.textContent = 'No notifications yet.';
        list.append(empty);
        return;
      }
      payload.notifications.forEach(notification => {
        const item = document.createElement('a');
        item.href = notification.url || '#';
        item.className = `dropdown-item text-wrap ${notification.read ? '' : 'fw-semibold'}`;
        item.dataset.notificationId = notification.id;
        item.append(document.createTextNode(notification.message));
        const time = document.createElement('small');
        time.className = 'd-block text-muted';
        time.textContent = notification.created_at || '';
        item.append(time);
        list.append(item);
      });
    });

    list.addEventListener('click', event => {
      const item = event.target.closest('[data-notification-id]');
      if (!item) return;
      fetch('{{ url('/admin/notifications') }}/' + item.dataset.notificationId + '/read', {
        method: 'PATCH',
        headers: {
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
          'Accept': 'application/json'
        }
      });
    });

    window.setInterval(refreshNotifications, 30000);
  })();
</script>
@endauth
</body>
</html>
