<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Project Manager</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">
</head>

<body>

  <div class="auth-wrapper">
    <div class="card auth-card shadow">
      <div class="card-body p-4 p-md-5">
        <div class="auth-logo"><i class="bi bi-kanban"></i></div>
        <h4 class="text-center fw-bold mb-1">Welcome back</h4>
        <p class="text-center text-muted mb-4">Sign in to continue to your projects</p>

        @if($errors->any())
        <div class="alert alert-danger">
          {{ $errors->first() }}
        </div>
        @endif

        <!-- action="/login" method="POST" in Laravel Blade -->
        <form action="{{ route('Savelogin') }}" method="POST">
          @csrf
          <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="you@company.com" required>
          </div>

          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
          </div>

          <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="remember" name="remember">
              <label class="form-check-label small" for="remember">Remember me</label>
            </div>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2">Sign in</button>
        </form>

        <div class="alert alert-light border mt-4 mb-0 small">
          <strong>Demo accounts</strong>
          <div class="mt-1">admin@demo.com — admin123</div>
          <div>manager@demo.com — manager123</div>
          <div>member@demo.com — member123</div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>