<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand fw-semibold">
            Universitas Udayana
        </span>

        <span class="text-white">
            {{ auth()->user()->name ?? 'Mahasiswa' }}
        </span>
    </div>
</nav>
