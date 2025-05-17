<div class="sidebar bg-light d-none d-md-flex flex-column flex-shrink-0 p-3">
    <a href="{{ route('pelatih.dashboard') }}" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-decoration-none">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" height="40" class="me-2">
        <span class="fs-5 fw-semibold">Pencak Silat</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('pelatih.dashboard') }}" class="nav-link {{ request()->routeIs('pelatih.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Manajemen</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('pelatih.kontingen.index') }}" class="nav-link {{ request()->routeIs('pelatih.kontingen.*') ? 'active' : '' }}">
                <i class="fas fa-users me-2"></i> Kontingen
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('pelatih.peserta.index') }}" class="nav-link {{ request()->routeIs('pelatih.peserta.*') ? 'active' : '' }}">
                <i class="fas fa-user-graduate me-2"></i> Peserta
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Keuangan & Dokumen</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('pelatih.pembayaran.index') }}" class="nav-link {{ request()->routeIs('pelatih.pembayaran.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
            </a>
        </li>
        
        <li class="nav-item mt-2">
            <div class="text-muted small text-uppercase px-3 mb-1">Pertandingan</div>
        </li>
        <li class="nav-item">
            <a href="{{ route('pelatih.jadwal.index') }}" class="nav-link {{ request()->routeIs('pelatih.jadwal.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt me-2"></i> Jadwal
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="bg-success text-white rounded-circle p-2 me-2" style="width: 38px; height: 38px; text-align: center;">
                <i class="fas fa-user"></i>
            </div>
            <div>
                <strong>{{ Auth::guard('pelatih')->user()->nama }}</strong>
                <div class="small text-muted">{{ Auth::guard('pelatih')->user()->perguruan }}</div>
            </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="dropdownUser">
            <li><a class="dropdown-item" href="#"><i class="fas fa-user-cog me-2"></i> Profil</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('pelatih.logout') }}" id="logout-form">
                    @csrf
                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                    </a>
                </form>
            </li>
        </ul>
    </div>
</div>

<!-- Offcanvas Mobile Menu -->
<div class="offcanvas offcanvas-start d-md-none" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarMenuLabel">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" height="30" class="me-2">
            Pencak Silat
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('pelatih.dashboard') }}" class="nav-link {{ request()->routeIs('pelatih.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item mt-2">
                <div class="text-muted small text-uppercase px-3 mb-1">Manajemen</div>
            </li>
            <li class="nav-item">
                <a href="{{ route('pelatih.kontingen.index') }}" class="nav-link {{ request()->routeIs('pelatih.kontingen.*') ? 'active' : '' }}">
                    <i class="fas fa-users me-2"></i> Kontingen
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('pelatih.peserta.index') }}" class="nav-link {{ request()->routeIs('pelatih.peserta.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate me-2"></i> Peserta
                </a>
            </li>
            
            <li class="nav-item mt-2">
                <div class="text-muted small text-uppercase px-3 mb-1">Keuangan & Dokumen</div>
            </li>
            <li class="nav-item">
                <a href="{{ route('pelatih.pembayaran.index') }}" class="nav-link {{ request()->routeIs('pelatih.pembayaran.*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave me-2"></i> Pembayaran
                </a>
            </li>
            
            <li class="nav-item mt-2">
                <div class="text-muted small text-uppercase px-3 mb-1">Pertandingan</div>
            </li>
            <li class="nav-item">
                <a href="{{ route('pelatih.jadwal.index') }}" class="nav-link {{ request()->routeIs('pelatih.jadwal.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt me-2"></i> Jadwal
                </a>
            </li>
        </ul>
    </div>
</div>