<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blog Management</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Scripts and Styles -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    @vite(['resources/js/app.js'])
    @inertiaHead
</head>

<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/images/logo.svg" alt="Logo" width="30" height="30"
                    class="d-inline-block align-text-top me-2">
                Blog Management
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">


                <InertiaLink href="/auth/login">Login</InertiaLink>
                <InertiaLink href="/auth/register">Login</InertiaLink>


                <span class="navbar-toggler-icon">

                </span>
            </button>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="#">Create Post</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Create Tags</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container">
        @inertia

    </div>

    <footer class="footer mt-auto py-3 bg-dark">
        <div class="container text-center text-white">
            <span>&copy; {{ date('Y') }} Blog Management. All rights reserved.</span>
        </div>
    </footer>
</body>

</html>