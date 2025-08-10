<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title> {{ $general->sitename }} - {{ __(@$page_title) }} </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(180deg, #6c5ce7, #173875);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .glass-card {
            background: rgb(255 255 255 / 31%);;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            width: 100%;
            max-width: 400px;
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
        }

        .form-control:focus {
            background-color: rgba(255, 255, 255, 0.3);
            color: white;
            border: none;
            box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.2);
        }

        .social-btn img {
            width: 24px;
            height: 24px;
        }

        a {
            color: #cbd5e0;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    @yield('content')
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>