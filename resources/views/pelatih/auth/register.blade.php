<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrasi Pelatih - Sistem Pendaftaran Pencak Silat</title>
    
    <script src="https://cdn.tailwindcss.com"></script> <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-yellow: #FFC107;
            --primary-orange: #FF9800;
            --primary-black: #111111;
            --secondary-black: #1C1C1C;
            --primary-white: #FFFFFF;
            --accent-green: #4CAF50; 
            --light-gray: #f0f0f0; 
            --dark-gray-text: #333333;
            --medium-gray-text: #555555;
            --light-gray-text: #777777;
            --danger-red: #DC3545;
            --success-green: #198754;
        }
        
        body {
            min-height: 100vh; 
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--light-gray) 0%, #dcdcdc 100%); 
            padding: 20px;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }
        
        .form-page-container { /* Mengganti nama dari .login-container */
            width: 100%;
            max-width: 1100px;
            min-height: 600px; 
            margin: auto;
            display: flex;
            border-radius: 28px; 
            overflow: hidden;
            box-shadow: 0 25px 70px rgba(0,0,0,0.35); 
            position: relative;
        }
        
        .graphic-side {
            flex: 1.2;
            background: var(--secondary-black); 
            display: flex;
            flex-direction: column;
            justify-content: center; 
            align-items: center; 
            padding: 40px;
            color: var(--primary-white);
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .pattern-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 20% 20%, rgba(255,193,7,0.05) 0%, transparent 30%),
                radial-gradient(circle at 80% 70%, rgba(255,152,0,0.05) 0%, transparent 30%),
                repeating-linear-gradient(
                    45deg,
                    rgba(255,255,255,0.02), rgba(255,255,255,0.02) 8px,
                    rgba(255,255,255,0.03) 8px, rgba(255,255,255,0.03) 16px
                );
            opacity: 0.5; 
            z-index: 0;
        }
        
        .form-content-side { /* Mengganti nama dari .login-side */
            flex: 0.8;
            background-color: var(--primary-white);
            padding: 40px; /* Sedikit dikurangi untuk form yang lebih panjang */
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-y: auto; /* Memungkinkan scroll jika kontennya panjang */
        }
        
        .brand-logo {
            position: absolute; /* Diposisikan absolut di graphic-side */
            top: 40px;
            left: 40px;
            display: flex; align-items: center; gap: 15px;
            z-index: 10;
        }
        .brand-logo-icon { font-size: 2.2rem; color: var(--primary-yellow); filter: drop-shadow(0 0 10px var(--primary-yellow));}
        .brand-text { font-weight: 800; font-size: 1.7rem; color: var(--primary-yellow); letter-spacing: 1.5px; text-transform: uppercase; text-shadow: 1px 1px 2px rgba(0,0,0,0.5); }
        
        .tiger-container {
            width: 450px; height: 450px; 
            display: flex; justify-content: center; align-items: center; 
            z-index: 5;
            margin-top: 60px; /* Beri ruang dari brand logo */
        }
        .tiger-image {
            width: 100%; max-width: 400px; 
            filter: drop-shadow(0 15px 30px rgba(0,0,0,0.6));
            transform-origin: center center; /* Disesuaikan untuk animasi nafas */
        }
        
        .yellow-glow {
            position: absolute; width: 380px; height: 380px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,193,7,0.3) 0%, rgba(255,193,7,0) 65%); 
            top: 50%; left: 50%; /* Dipusatkan dengan graphic-side */
            transform: translate(-50%, -50%); z-index: 2;
            animation: pulse 4s infinite ease-in-out;
        }
        .belt-highlight {
            position: absolute; width: 190px; height: 55px;
            background-color: rgba(255,193,7,0.2); 
            border-radius: 12px;
            top: 45%; left: 50%; /* Disesuaikan relatif ke tiger */
            transform: translate(-50%, -50%); /* Disesuaikan untuk centering */
            z-index: 3; /* Di atas yellow-glow jika tumpang tindih */
            animation: beltGlow 3s infinite ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(0.95); opacity: 0.25; }
            50% { transform: translate(-50%, -50%) scale(1.05); opacity: 0.4; }
            100% { transform: translate(-50%, -50%) scale(0.95); opacity: 0.25; }
        }
        @keyframes beltGlow {
            0% { box-shadow: 0 0 12px 4px rgba(255,193,7,0.25); }
            50% { box-shadow: 0 0 30px 10px rgba(255,193,7,0.5); }
            100% { box-shadow: 0 0 12px 4px rgba(255,193,7,0.25); }
        }
        
        .particles-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: 2; }
        .particle {
            position: absolute; background-color: var(--primary-yellow);
            opacity: 0; border-radius: 50%;
            animation: float 20s infinite linear; 
        }
        @keyframes float { 
            0% { transform: translateY(100vh) translateX(var(--x-start)) rotate(0deg); opacity: 0; }
            20% { opacity: 0.4; } 
            80% { opacity: 0.4; } 
            100% { transform: translateY(-100px) translateX(var(--x-end)) rotate(720deg); opacity: 0; }
        }
        
        @keyframes breathing { /* Disederhanakan karena tiger-image transform-origin sudah center */
            0% { transform: scale(1); }
            50% { transform: scale(1.02); } 
            100% { transform: scale(1); }
        }
        .breathing { animation: breathing 5s ease-in-out infinite; } 
        
        .martial-arts-icon { position: absolute; color: var(--primary-yellow); opacity: 0.07; z-index: 1; } 
        .icon-1 { top: 12%; left: 8%; transform: rotate(-20deg); font-size: 2.5rem; }
        .icon-2 { bottom: 10%; right: 12%; transform: rotate(20deg) scaleX(-1); font-size: 2.3rem; } /* Diputar agar menghadap ke dalam */
        .icon-3 { top: 75%; left: 10%; transform: rotate(30deg); font-size: 2rem; } /* Dipindah dan disesuaikan */

        .decoration-circle { position: absolute; border-radius: 50%; z-index: 0;  }
        .circle-1 { width: 350px; height: 350px; background: radial-gradient(circle, rgba(255,193,7,0.04) 0%, transparent 70%); top: -180px; right: -180px; }
        .circle-2 { width: 250px; height: 250px; background: radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 70%); bottom: -120px; left: -120px; } 

        .page-title-section { /* Mengganti .event-title */
             position: relative; z-index: 10; text-align: center; margin-bottom: 25px; /* Margin dikurangi */
        }
        .page-title-section h1 { font-size: 2.2rem; font-weight: 800; margin: 0; line-height: 1.1; letter-spacing: 1px; }
        .page-title-section h1 .yellow-text { color: var(--primary-yellow); text-shadow: 1px 1px 0px var(--primary-black), 2px 2px 3px rgba(0,0,0,0.2); }
        .page-title-section h1 .black-text { color: var(--primary-black); text-shadow: 1px 1px 1px rgba(255,193,7,0.2); }
        .page-title-section p { font-size: 1rem; color: var(--medium-gray-text); font-weight: 500; margin-top: 8px; }
        
        .form-control {
            height: 48px; border-radius: 10px; 
            border: 1px solid #ddd; 
            padding: 0 18px; font-size: 0.9rem; /* Padding disesuaikan */
            transition: all 0.3s ease; background-color: #fdfdfd; 
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.05);
        }
        .form-control::placeholder { color: #aaa; }
        .form-control:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 4px rgba(255,193,7,0.25), inset 0 1px 3px rgba(0,0,0,0.05);
            background-color: var(--primary-white);
        }
        .form-label {
            font-weight: 600; color: var(--dark-gray-text);
            margin-bottom: 8px; font-size: 0.85rem; /* Font label sedikit dikecilkan */
            display: flex; align-items: center;
        }
        .form-label i { color: var(--primary-orange); margin-right: 8px; font-size: 0.9em; }
        .form-label .text-danger { font-size: 0.9em; margin-left: 4px; }
        
        .btn-silat-submit { /* Mengganti nama dari .btn-silat */
            background: linear-gradient(135deg, var(--primary-orange), var(--primary-yellow));
            border: none; 
            color: var(--primary-black); 
            padding: 12px; border-radius: 10px; /* Padding disesuaikan */
            font-weight: 700; font-size: 1rem; letter-spacing: 0.8px;
            transition: all 0.35s cubic-bezier(0.25, 0.8, 0.25, 1); 
            box-shadow: 0 6px 20px rgba(255,152,0,0.3), 0 2px 5px rgba(0,0,0,0.1);
            position: relative; overflow: hidden; text-transform: uppercase;
        }
        .btn-silat-submit i { transition: transform 0.3s ease; }
        .btn-silat-submit:hover {
            transform: translateY(-4px) scale(1.02); 
            background: linear-gradient(135deg, var(--primary-yellow), var(--primary-orange));
            color: var(--primary-black);
            box-shadow: 0 10px 25px rgba(255,193,7,0.4), 0 4px 8px rgba(0,0,0,0.15);
        }
        .btn-silat-submit:hover i { transform: translateX(5px); }
        .btn-silat-submit:active { transform: translateY(-1px) scale(0.98); box-shadow: 0 4px 15px rgba(255,152,0,0.3); }

        .footer-text { font-size: 0.8rem; color: var(--light-gray-text); text-align: center; }
        .login-link a { color: var(--primary-orange); text-decoration: none; font-weight: 600; }
        .login-link a:hover { color: var(--primary-yellow); text-decoration: underline; }

        .alert { border-radius: 10px; font-size: 0.85rem; padding: 0.8rem 1rem; border-width: 0px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .alert-danger { background-color: rgba(220, 53, 69, 0.1); color: var(--danger-red); border-left: 5px solid var(--danger-red); }
        .alert ul { padding-left: 1.2rem; margin-bottom: 0; }
        .alert .btn-close { filter: grayscale(1) brightness(1.5); } 
        
        .form-content-side .row .col-md-6:first-child { padding-right: 0.75rem; }
        .form-content-side .row .col-md-6:last-child { padding-left: 0.75rem; }


        /* Responsive Design */
        @media (max-width: 992px) {
            .form-page-container { flex-direction: column; height: auto; max-width: 550px; }
            .graphic-side { 
                min-height: 300px; /* Disesuaikan agar tidak terlalu besar saat maskot di tengah */
                padding: 30px 25px; 
                justify-content: center; /* Memastikan maskot tetap tengah */
            }
            .brand-logo { position: relative; top: auto; left: auto; margin-bottom: 20px; justify-content: center;}
            .tiger-container { position: relative; height: auto; margin: 10px auto; transform: scale(0.85); } /* Disesuaikan ukurannya */
            .tiger-image { max-width: 280px; }
            .belt-highlight, .yellow-glow { transform: translate(-50%, -50%) scale(0.85); } /* Ikut scale down */
            .belt-highlight { top: 48%;}
            .yellow-glow { top: 50%;}

            .martial-arts-icon { display: none; } /* Sembunyikan ikon kecil agar tidak ramai */

            .form-content-side { padding: 30px 25px; overflow-y: visible; } /* Padding disesuaikan */
            .page-title-section h1 { font-size: 1.8rem; }
            .page-title-section p { font-size: 0.9rem; }
        }
        
        @media (max-width: 576px) {
            body { padding: 15px; }
            .form-page-container { border-radius: 20px; max-width: 100%;}
            .graphic-side { padding: 25px 20px; min-height: 280px; }
            .brand-logo-icon { font-size: 1.8rem; }
            .brand-text { font-size: 1.4rem; }
            .tiger-container { transform: scale(0.75); margin-top: 0;}
            .tiger-image { max-width: 250px; }
            .belt-highlight, .yellow-glow { display:none; } /* Sembunyikan glow di layar sangat kecil */


            .form-content-side { padding: 25px 20px; }
            .page-title-section { margin-bottom: 20px; }
            .page-title-section h1 { font-size: 1.6rem; }
            .page-title-section p { font-size: 0.85rem; }
            .form-control { height: 45px; font-size: 0.85rem; padding: 0 15px; }
            .form-label { font-size: 0.8rem; margin-bottom: 6px;}
            .form-label i { margin-right: 6px; }
            .btn-silat-submit { padding: 11px; font-size: 0.95rem; }
            .footer-text { font-size: 0.75rem; }
            .alert { font-size: 0.8rem; padding: 0.7rem 0.9rem;}
            .form-content-side .row .col-md-6 { padding-left: 0.5rem; padding-right: 0.5rem; }
            .form-content-side .row .col-md-6:not(:last-child) { margin-bottom: 0.75rem; } /* Spasi antar field di mobile */
        }
    </style>
</head>
<body>
    <div class="form-page-container">
        <div class="graphic-side">
            <div class="pattern-overlay"></div>
            <div class="brand-logo">
                <i class="fas fa-fist-raised brand-logo-icon"></i>
                <span class="brand-text">Pencak Silat</span>
            </div>
            
            <i class="fas fa-fist-raised martial-arts-icon icon-1"></i>
            <i class="fas fa-khanda martial-arts-icon icon-2"></i>
            <i class="fas fa-yin-yang martial-arts-icon icon-3"></i>
            
            <div class="particles-container" id="particles"></div>
            
            <div class="yellow-glow"></div>
            <div class="belt-highlight"></div>
            
            <div class="decoration-circle circle-1"></div>
            <div class="decoration-circle circle-2"></div>
            
            <div class="tiger-container breathing">
                <img src="{{ asset('app/img/MASKOT.png') }}" alt="Maskot Harimau Silat Keren" class="tiger-image" 
                     onerror="this.onerror=null; this.src='https://placehold.co/400x400/1C1C1C/FFC107?text=Maskot+Silat&font=poppins';">
            </div>
        </div>
        
        <div class="form-content-side">
            <div class="login-decoration login-decoration-1" style="background: linear-gradient(135deg, rgba(var(--primary-black-rgb, 33,33,33), 0.02) 0%, rgba(var(--primary-black-rgb, 33,33,33), 0) 100%);"></div>
            <div class="login-decoration login-decoration-2" style="background: linear-gradient(135deg, rgba(var(--primary-yellow-rgb,255,193,7), 0.03) 0%, rgba(var(--primary-yellow-rgb,255,193,7), 0) 100%);"></div>
            
            <div class="page-title-section">
                <h1><span class="yellow-text">REGISTRASI</span> <span class="black-text">PELATIH</span></h1>
                <p>Buat Akun Pelatih Baru</p>
            </div>
            
            @if(session('success')) <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error')) <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong class="d-block mb-1"><i class="fas fa-times-circle me-2"></i>Harap perbaiki kesalahan berikut:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form method="POST" action="{{ route('pelatih.register') }}" class="mt-1">
                @csrf
                
                <div class="mb-3">
                    <label for="nama" class="form-label"><i class="fas fa-user"></i>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" value="{{ old('nama') }}" required>
                    @error('nama')
                        <div class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="perguruan" class="form-label"><i class="fas fa-shield-alt"></i>Nama Perguruan <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('perguruan') is-invalid @enderror" id="perguruan" name="perguruan" placeholder="Masukkan nama perguruan Anda" value="{{ old('perguruan') }}" required>
                    @error('perguruan')
                        <div class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-2"> <div class="col-md-6 mb-3 mb-md-0">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i>Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="cth: email@anda.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="no_hp" class="form-label"><i class="fas fa-phone"></i>Nomor HP <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('no_hp') is-invalid @enderror" id="no_hp" name="no_hp" placeholder="cth: 08123456789" value="{{ old('no_hp') }}" required>
                        @error('no_hp')
                            <div class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i>Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Minimal 8 karakter" required>
                        @error('password')
                            <div class="invalid-feedback d-block mt-1" style="font-size: 0.8rem;">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-redo-alt"></i>Konfirmasi Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password" required>
                    </div>
                </div>
                
                <div class="d-grid gap-2 mt-4"> <button class="btn btn-silat-submit py-2" type="submit"> <i class="fas fa-user-plus me-2"></i>DAFTAR AKUN
                    </button>
                </div>
                
                <div class="mt-4 text-center login-link"> <p style="font-size: 0.9rem; color: var(--medium-gray-text);">Sudah punya akun Pelatih? <a href="{{ route('pelatih.login.form') }}">Login di sini</a></p>
                </div>
            </form>
            
            <div class="footer-text mt-auto pt-3"> <p class="mb-1"><a href="#" class="text-decoration-none" style="color: var(--light-gray-text);">Panduan Penggunaan</a> | <a href="#" class="text-decoration-none" style="color: var(--light-gray-text);">Kebijakan Privasi</a></p>
                <p class="mb-0">© {{ date('Y') }} Sistem Pendaftaran Pencak Silat UNPER OPEN. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            if (particlesContainer) {
                const numParticles = 25; // Jumlah partikel disesuaikan
                for (let i = 0; i < numParticles; i++) {
                    createParticle(particlesContainer);
                }
            }
        });

        function createParticle(container) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            const size = Math.random() * 4 + 2; 
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            particle.style.setProperty('--x-start', `${Math.random() * 100 - 50}vw`); 
            particle.style.setProperty('--x-end', `${Math.random() * 100 - 50}vw`);   

            particle.style.left = `${Math.random() * 100}%`;
            particle.style.bottom = `-${size}px`; 
            particle.style.animationDuration = `${Math.random() * 15 + 10}s`; 
            particle.style.animationDelay = `${Math.random() * 15}s`;
            
            container.appendChild(particle);

            particle.addEventListener('animationend', function() {
                this.remove();
                if (document.getElementById('particles')) { // Cek jika container masih ada
                   createParticle(document.getElementById('particles')); 
                }
            });
        }

        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                const alertInstance = bootstrap.Alert.getInstance(alert);
                if (alertInstance) {
                    alertInstance.close();
                } else if (alert.offsetParent !== null) { 
                     new bootstrap.Alert(alert).close();
                }
            }, 7000); 
        });
    </script>
</body>
</html>