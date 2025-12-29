<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'Dashboard')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #eef1f4;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        position: fixed;
        left: 0; top: 0;
        width: 230px;
        height: 100vh;
        background: #222b36;
        color: #fff;
        display: flex;
        flex-direction: column;
    }
    .sidebar-header {
        text-align: center;
        padding: 25px 15px;
        font-size: 20px;
        font-weight: bold;
        border-bottom: 1px solid #3a434d;
    }
    .menu {
        margin-top: 15px;
    }
    .menu a {
        display: block;
        padding: 12px 20px;
        text-decoration: none;
        font-size: 14px;
        color: #b9c0c7;
        border-left: 3px solid transparent;
        transition: 0.25s;
    }
    .menu a:hover,
    .menu a.active {
        background: #1a222b;
        color: #fff;
        border-left: 3px solid #0dd3ba;
    }

    /* ===== TOPBAR ===== */
    .topbar {
        margin-left: 230px;
        height: 60px;
        background: #ffffff;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 0 25px;
        border-bottom: 1px solid #dfe3e8;
        font-size: 14px;
    }

    /* ===== CONTENT ===== */
    .content {
        margin-left: 230px;
        padding: 25px;
    }

    /* ===== CARD ===== */
    .card {
        background: #fff;
        padding: 20px;
        border: 1px solid #dfe3e8;
        border-radius: 6px;
        margin-bottom: 20px;
    }
    .card h3 {
        margin-bottom: 10px;
        font-size: 18px;
        color: #333;
    }
</style>

</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">DASHBOARD</div>

    <div class="menu">
        <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">Dashboard</a>
        <a href="{{ route('profile.show') }}" class="{{ request()->is('profile*') ? 'active' : '' }}">Profile</a>
        <a href="/mahasiswa">Data Mahasiswa</a>
        <a href="/logout" style="color:#ff6b6b;">Logout</a>
    </div>
</div>

<!-- TOP NAV -->
<div class="topbar" style="display:flex; justify-content:space-between; align-items:center; padding:15px 25px;">
    
    {{-- KIRI HEADER --}}
    <div style="line-height:1.3;">
        <div style="font-size:20px; font-weight:bold; color:#222;">Fakultas Matematika dan Ilmu Pengetahuan Alam</div>
        <div style="font-size:14px; color:#555;">Universitas Udayana</div>
        
        <div style="margin-top:8px; display:inline-block; padding:6px 16px; background:rgba(255,255,255,0.4); 
            backdrop-filter: blur(6px); border:1px solid #ddd; border-radius:6px; font-size:14px;">
            Dashboard
        </div>
    </div>

    {{-- KANAN HEADER (Final Order Correct) --}}
<a href="{{ route('profile.show') }}" 
   style="text-decoration:none; color:inherit;">

    <div style="display:flex; align-items:center; gap:12px; cursor:pointer;">
        
        <!-- Nama dan role dulu -->
        <div style="display:flex; flex-direction:column; line-height:1.2; text-align:right;">
            <strong style="font-size:14px;">
                {{ auth()->user()->name ?? 'User' }}
            </strong>
            <span style="font-size:12px; color:#555;">
                {{ auth()->user()->role ?? 'Mahasiswa' }}
            </span>
        </div>

        <!-- Foto profil setelahnya -->
        <img src="/img/profile-default.png"
            style="width:45px; height:45px; border-radius:50%;
            object-fit:cover; border:2px solid #ddd;">
    </div>
</a>

</div>


<!-- PAGE CONTENT -->
<div class="content">
    @yield('content')
</div>

</body>
</html>
