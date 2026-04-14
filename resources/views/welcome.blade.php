<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventaris SMK Wikrama</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            color: #334155;
        }

        :root {
            --primary-color: #2b43cc;
            --primary-hover: #1e32a1;
            --primary-light: #e8edff;
        }

        .btn-theme {
            background-color: var(--primary-color) !important;
            color: white !important;
            border: none !important;
            transition: all 0.3s ease;
        }

        .btn-theme:hover {
            background-color: var(--primary-hover) !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(43, 67, 204, 0.2);
        }

        .text-theme {
            color: var(--primary-color) !important;
        }

        .hero-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 80vh;
            text-align: center;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-theme" href="#">SMK Wikrama</a>
            <div class="d-flex ms-auto">
                <button type="button" class="btn btn-theme px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#loginModal">
                    Login
                </button>
            </div>
        </div>
    </nav>

    <div class="container hero-section">
        <h1 class="fw-bold mb-3">Inventory Management of SMK Wikrama</h1> <p class="text-muted fs-5 mb-5">Management of incoming and outgoing items at SMK Wikrama Bogor</p> </div>

    <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header text-center">
                    <h5 class="modal-title w-100 fw-bold" id="loginModalLabel">Login</h5>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-secondary w-50" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-theme w-50" data-bs-dismiss="modal">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
