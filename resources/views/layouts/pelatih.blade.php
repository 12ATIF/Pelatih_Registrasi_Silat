<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Portal Pelatih Pencak Silat</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.1/css/responsive.bootstrap5.min.css">
    
    <!-- Custom CSS -->
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .sidebar {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            z-index: 100;
        }
        .sidebar .nav-link {
            color: #333;
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            margin: 0.2rem 0;
        }
        .sidebar .nav-link:hover {
            background-color: #f0f0f0;
        }
        .sidebar .nav-link.active {
            background-color: #198754;
            color: white;
        }
        .content {
            flex: 1;
        }
        .main-content {
            padding-top: 56px;
        }
        
        @media (min-width: 768px) {
            body {
                flex-direction: row;
            }
            .sidebar {
                width: 250px;
                min-height: 100vh;
            }
            .content {
                min-height: 100vh;
            }
            .navbar-top {
                height: 56px;
                position: relative;
            }
            .main-content {
                padding-top: 20px;
            }
        }
        
        .border-left-primary { border-left: 4px solid #4e73df; }
        .border-left-success { border-left: 4px solid #1cc88a; }
        .border-left-warning { border-left: 4px solid #f6c23e; }
        .border-left-danger { border-left: 4px solid #e74a3b; }
        .border-left-info { border-left: 4px solid #36b9cc; }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('layouts.partials.pelatih.sidebar')
    
    <div class="content">
        <!-- Navbar -->
        @include('layouts.partials.pelatih.navbar')
        
        <!-- Content Wrapper -->
        <div class="container-fluid py-4 px-3 px-md-4 main-content">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-0">@yield('title')</h4>
                    
                    <!-- Breadcrumb -->
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item"><a href="{{ route('pelatih.dashboard') }}">Dashboard</a></li>
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                
                <!-- Action Buttons -->
                <div>
                    @yield('action-buttons')
                </div>
            </div>
            
            <!-- Alert Messages -->
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
            
            <!-- Main Content -->
            @yield('content')
        </div>
        
        <!-- Footer -->
        @include('layouts.partials.pelatih.footer')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    
    <!-- Global Scripts -->
    <script>
        // Set default configuration for DataTables
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
        
        // CSRF Token setup
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert-dismissible').alert('close');
        }, 5000);
    </script>
    
    @stack('scripts')
</body>
</html>