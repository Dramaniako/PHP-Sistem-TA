<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem TA')</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
        }

        .header {
            background: #000;
            color: #fff;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-left {
            font-size: 18px;
            font-weight: bold;
        }

        .header-right {
            font-size: 14px;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: #fff;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

<div class="header">
    <div class="header-left">
        Sistem Tugas Akhir
    </div>

    <div class="header-right">
        {{ auth()->user()->role ?? 'Guest' }} |
        {{ auth()->user()->name ?? 'User' }}

        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit"
                style="
                    margin-left: 10px;
                    background: red;
                    color: white;
                    border: none;
                    padding: 5px 10px;
                    cursor: pointer;
                    border-radius: 4px;
                ">
                Logout
            </button>
        </form>
    </div>
</div>

<div class="container">
    <div class="card">
        @yield('content')
    </div>
</div>

</body>
</html>
