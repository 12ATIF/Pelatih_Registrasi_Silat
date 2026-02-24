<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Portal Pelatih Pencak Silat</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    
    <style>
        /* === Layout Utama: Sidebar tetap, konten scroll sendiri === */
        html, body {
            height: 100vh;
            margin: 0;
            overflow: hidden; /* Mencegah seluruh halaman scroll */
        }
        body {
            display: flex;
            flex-direction: column;
        }

        /* Wrapper utama: sidebar + content berdampingan */
        .app-wrapper {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* === Sidebar: Fixed dengan scroll sendiri === */
        .sidebar {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 100;
            background-color: #212529;
            color: #f8f9fa;
            width: 250px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            height: 100%; /* Penuh dari app-wrapper */
            overflow-y: auto; /* Sidebar punya scroll sendiri */
        }
        .sidebar .nav-link {
            color: #f8f9fa;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0.2rem 0;
        }
        .sidebar .nav-link:hover {
            background-color: #495057;
            color: #ffffff;
        }
        .sidebar .nav-link.active {
            background-color: #FFD700;
            color: #212529;
        }
        .sidebar .text-muted {
            color: #adb5bd !important;
        }
        .sidebar hr {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        .sidebar .brand-text-orange {
            color: #F97A16 !important;
        }
        .sidebar .dropdown-menu {
            background-color: #343a40;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }
        .sidebar .dropdown-item {
            color: #f8f9fa;
        }
        .sidebar .dropdown-item:hover {
            background-color: #495057;
            color: #ffffff;
        }
        .sidebar .dropdown-divider {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* === Content Area: Navbar + Body + Footer === */
        .content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Mencegah content area scroll keseluruhan */
            min-width: 0; /* Mencegah overflow horizontal */
        }

        /* Navbar tetap di atas content */
        .content > .navbar {
            flex-shrink: 0;
        }

        /* Hanya bagian body ini yang bisa scroll */
        .content-wrapper {
            flex: 1;
            overflow-y: auto; /* Scroll utama hanya di sini */
            overflow-x: hidden;
        }

        .main-content {
            padding-top: 20px;
        }

        /* Footer tetap di bawah */
        .footer-wrapper {
            flex-shrink: 0;
        }

        /* === Responsive: Sembunyikan sidebar di mobile === */
        @media (max-width: 767.98px) {
            .sidebar {
                display: none !important; /* Gunakan offcanvas di mobile */
            }
            .main-content {
                padding-top: 10px;
            }
        }
        
        /* Pastikan navbar tetap memiliki tinggi yang cukup */
        .navbar.sticky-top {
            min-height: 56px;
        }

        /* Styling untuk footer */
        .footer-dark {
            background-color: #212529; /* Bootstrap's bg-dark */
            color: #adb5bd; /* Warna muted untuk teks footer */
        }
        .footer-dark p {
            margin-bottom: 0;
        }

        .border-left-primary { border-left: 4px solid #FF8C00; } /* Oranye menggantikan primary */
        .border-left-success { border-left: 4px solid #FFD700; } /* Kuning menggantikan success */
        .border-left-warning { border-left: 4px solid #F97A16; } /* Oranye lebih pekat untuk warning */
        .border-left-danger { border-left: 4px solid #e74a3b; } /* Merah tetap untuk danger */
        .border-left-info { border-left: 4px solid #36b9cc; } /* Biru muda tetap untuk info atau bisa diganti */

    </style>
    
    @stack('styles')
</head>
<body>
    <div class="app-wrapper">
        @include('layouts.partials.pelatih.sidebar')
        
        <div class="content">
            @include('layouts.partials.pelatih.navbar')
            
            {{-- Hanya bagian ini yang bisa di-scroll --}}
            <div class="content-wrapper">
                <div class="container-fluid py-4 px-3 px-md-4 main-content">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="mb-0">@yield('title')</h4>
                            
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Dashboard</a></li>
                                    @yield('breadcrumb')
                                </ol>
                            </nav>
                        </div>
                        
                        <div>
                            @yield('action-buttons')
                        </div>
                    </div>
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
            
            <div class="footer-wrapper">
                @include('layouts.partials.pelatih.footer')
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    
    <script>
        $.extend(true, $.fn.dataTable.defaults, {
            language: {
                processing: 'Sedang memproses...',
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data yang tersedia',
                infoFiltered: '(difilter dari _MAX_ total data)',
                loadingRecords: 'Memuat...',
                zeroRecords: 'Tidak ditemukan data yang sesuai',
                emptyTable: 'Tidak ada data yang tersedia',
                paginate: {
                    first: 'Pertama',
                    previous: 'Sebelumnya',
                    next: 'Selanjutnya',
                    last: 'Terakhir'
                }
            },
            responsive: true,
            autoWidth: false,
        });
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>