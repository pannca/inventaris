<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventaris System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f7fe;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            border-radius: 16px;
        }
        .btn-login {
            background-color: #2b43cc;
        }
        .btn-login:hover {
            background-color: #2338b0;
        }
    </style>
</head>
<body>
    <div class="container px-3">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <div class="text-center mb-4">
                        <h4 class="fw-bold" style="color:#2b43cc;">SMK WIKRAMA</h4>
                        <p class="text-muted small mb-0">Sistem Inventaris Sekolah</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger py-2 small">{{ $errors->first() }}</div>
                    @endif

                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email') }}" placeholder="contoh@email.com" required autofocus>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Password</label>
                            <input type="password" name="password" class="form-control"
                                placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-login text-white w-100 fw-semibold">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
