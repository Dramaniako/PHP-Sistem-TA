<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        html, body {
            height: 100%;
        }

        body {
            background: linear-gradient(135deg, #d9d9d9, #8c8c8c);
            margin: 0;
        }

        .login-wrapper {
            height: 100vh; /* ⬅️ layar penuh */
            display: flex;
            justify-content: center;
            align-items: stretch;
            padding: 40px;
        }

        .login-card {
            width: 100%;
            max-width: 1200px;
            height: 100%; /* ⬅️ penting */
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 25px 60px rgba(0,0,0,.35);
        }

        .login-row {
            height: 100%; /* ⬅️ ini kuncinya */
        }

        .left-panel {
            background: #1f1f1f;
            color: white;
        }

        .logo-box {
            width: 170px;
            height: 170px;
            background: #2f2f2f;
            border-radius: 26px;
            margin-bottom: 30px;
        }

        .form-control {
            height: 54px;
            border-radius: 12px;
            font-size: 16px;
        }

        .btn-login {
            height: 54px;
            border-radius: 12px;
            font-size: 16px;
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="card login-card">
        <div class="row g-0 login-row">

            <!-- LEFT FULL HEIGHT -->
            <div class="col-md-6 left-panel d-flex justify-content-center align-items-center">
                <div class="text-center p-5">
                    <h2 class="fw-bold mb-3">WELCOME BACK!</h2>
                    <div class="logo-box mx-auto"></div>
                    <p class="fs-5 mb-0">
                        FMIPA
                    </p>
                    <p class="fs-2 mb-0">
                        Universitas Udayana
                    </p>
                    
                </div>
            </div>

            <!-- RIGHT FULL HEIGHT -->
            <div class="col-md-6 bg-light d-flex justify-content-center align-items-center">
                <div class="w-100" style="max-width: 400px;">

                    <h4 class="text-center fw-semibold mb-4">
                        LOGIN ACCOUNT
                    </h4>

                    <form method="POST" action="/login">
                        @csrf

                        <div class="mb-3">
                            <input type="text" name="nim" class="form-control" placeholder="NIM" required>
                        </div>

                        <div class="mb-4">
                            <input type="password" name="password" class="form-control" placeholder="Password" required>
                        </div>

                        @error('nim')
                            <div class="text-danger text-center mb-3">
                                {{ $message }}
                            </div>
                        @enderror

                        <div class="d-grid">
                            <button type="submit" class="btn btn-dark btn-login">
                                Log In
                            </button>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
