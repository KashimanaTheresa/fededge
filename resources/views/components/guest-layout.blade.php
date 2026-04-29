<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Fededge - Authentication</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        :root {
            --fededge-red: #d32f2f;
            --fededge-dark-red: #b71c1c;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(211, 47, 47, 0.9) 0%, rgba(183, 28, 28, 0.9) 100%);
            z-index: -1;
        }

        .container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border-top: 4px solid var(--fededge-red);
        }

        .card-body {
            padding: 2.5rem;
        }

        .form-control {
            border: none;
            border-bottom: 2px solid #e0e0e0;
            border-radius: 0;
            padding: 10px 0;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-bottom-color: var(--fededge-red);
            box-shadow: none;
            background: transparent;
        }

        .form-label {
            color: #333;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        textarea.form-control {
            border-bottom: 2px solid #e0e0e0;
            resize: vertical;
        }

        textarea.form-control:focus {
            border-bottom-color: var(--fededge-red);
        }

        .btn-danger {
            background: linear-gradient(90deg, var(--fededge-dark-red) 0%, var(--fededge-red) 100%);
            border: none;
            font-weight: 600;
            padding: 12px;
            border-radius: 6px;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background: linear-gradient(90deg, var(--fededge-red) 0%, #ef5350 100%);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.3);
        }

        .btn-danger:active {
            transform: translateY(0);
        }

        .form-check-input {
            border-color: #e0e0e0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: var(--fededge-red);
            border-color: var(--fededge-red);
        }

        .form-check-input:focus {
            border-color: var(--fededge-red);
            box-shadow: 0 0 0 0.25rem rgba(211, 47, 47, 0.25);
        }

        a {
            color: var(--fededge-red);
            text-decoration: none;
            transition: all 0.3s ease;
        }

        a:hover {
            color: var(--fededge-dark-red);
        }

        .text-danger {
            color: var(--fededge-red) !important;
        }

        .alert {
            border: none;
            border-radius: 6px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .text-center h2 {
            color: var(--fededge-dark-red);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        .text-center .text-muted {
            font-size: 0.95rem;
        }

        small.text-muted {
            font-size: 0.85rem;
        }

        .mb-3 {
            margin-bottom: 1.5rem;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .card-body {
                padding: 1.5rem;
            }

            .container {
                max-width: 100%;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        {{ $slot }}
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
