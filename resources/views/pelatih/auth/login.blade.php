<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login Pelatih - Sistem Pendaftaran Pencak Silat</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Color scheme based on the new example */
        :root {
            --primary-yellow: #FFC107; /* Vibrant yellow from belt */
            --primary-orange: #FF9800; /* Tiger orange */
            --primary-black: #111111; /* Deep black from uniform */
            --primary-white: #FFFFFF; /* White from tiger fur */
            --accent-green: #4CAF50; /* Green from tiger eyes */
            --light-gray: #f5f5f5;
        }
        
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--light-gray) 0%, #e0e0e0 100%);
            padding: 20px;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden; /* Prevent horizontal scroll on small screens due to large container */
        }
        
        .login-container {
            width: 100%;
            max-width: 1100px; /* Max width of the login box */
            min-height: 600px; /* Min height to ensure layout consistency */
            margin: auto;
            display: flex;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            position: relative;
        }
        
        /* Left side - Graphic Side */
        .graphic-side {
            flex: 1.2;
            background: var(--primary-black);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 40px;
            color: white;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }
        
        .pattern-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: repeating-linear-gradient(
                45deg,
                rgba(0,0,0,0.1),
                rgba(0,0,0,0.1) 10px,
                rgba(0,0,0,0.2) 10px,
                rgba(0,0,0,0.2) 20px
            );
            opacity: 0.3;
            z-index: 0;
        }
        
        .login-side {
            flex: 0.8;
            background-color: white;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }
        
        /* Brand Logo */
        .brand-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 40px;
            position: relative;
            z-index: 10;
        }
        
        .brand-logo-icon {
            font-size: 2rem;
            color: var(--primary-yellow);
        }
        
        .brand-text {
            font-weight: 800;
            font-size: 1.6rem;
            color: var(--primary-yellow);
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        
        /* Tiger Mascot */
        .tiger-container {
            position: absolute;
            bottom: -30px;
            left: 50%;
            transform: translateX(-50%);
            width: 420px;
            height: 420px;
            display: flex;
            justify-content: center;
            align-items: flex-end;
            z-index: 5;
        }
        
        .tiger-image {
            width: 100%;
            max-width: 380px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.5));
            transform-origin: bottom center;
        }
        
        /* Glow Effects */
        .yellow-glow {
            position: absolute;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,193,7,0.4) 0%, rgba(255,193,7,0) 70%);
            top: 65%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 2;
            animation: pulse 4s infinite ease-in-out;
        }
        
        .belt-highlight {
            position: absolute;
            width: 180px;
            height: 50px;
            background-color: rgba(255,193,7,0.3);
            border-radius: 10px;
            top: 58%; 
            left: 50%;
            transform: translateX(-50%);
            z-index: 2;
            animation: beltGlow 3s infinite ease-in-out;
        }
        
        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.2; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 0.4; }
            100% { transform: translate(-50%, -50%) scale(0.9); opacity: 0.2; }
        }
        
        @keyframes beltGlow {
            0% { box-shadow: 0 0 10px 3px rgba(255,193,7,0.3); }
            50% { box-shadow: 0 0 25px 8px rgba(255,193,7,0.6); }
            100% { box-shadow: 0 0 10px 3px rgba(255,193,7,0.3); }
        }
        
        /* Particle Effect */
        .particles-container {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 2;
        }
        
        .particle {
            position: absolute;
            background-color: var(--primary-yellow);
            opacity: 0.3;
            border-radius: 50%;
            animation: float 15s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translateY(0) rotate(0deg); opacity: 0; }
            10% { opacity: 0.3; }
            90% { opacity: 0.3; }
            100% { transform: translateY(-500px) rotate(360deg); opacity: 0; }
        }
        
        /* Tiger Animation */
        @keyframes breathing {
            0% { transform: translateX(-50%) scale(1); }
            50% { transform: translateX(-50%) scale(1.03); }
            100% { transform: translateX(-50%) scale(1); }
        }
        
        .breathing {
            animation: breathing 4s ease-in-out infinite;
        }
        
        /* Martial Arts Icons */
        .martial-arts-icon {
            position: absolute;
            color: var(--primary-yellow);
            opacity: 0.1;
            z-index: 1;
        }
        
        .icon-1 { top: 15%; left: 10%; transform: rotate(-15deg); font-size: 2.2rem; }
        .icon-2 { bottom: 35%; right: 15%; transform: rotate(15deg); font-size: 2rem; }
        .icon-3 { top: 30%; right: 10%; transform: rotate(25deg); font-size: 1.8rem; }
        
        /* Decoration Elements */
        .decoration-circle { position: absolute; border-radius: 50%; z-index: 1; }
        .circle-1 { width: 300px; height: 300px; background-color: rgba(255, 193, 7, 0.03); top: -150px; right: -150px; }
        .circle-2 { width: 200px; height: 200px; background-color: rgba(0, 0, 0, 0.05); bottom: -100px; right: 50px; }
        
        /* Login Side Elements */
        .event-title { position: relative; z-index: 10; text-align: center; margin-bottom: 30px; } /* Reduced margin-bottom */
        .event-title h1 { font-size: 2.5rem; font-weight: 800; margin: 0; line-height: 1; letter-spacing: 2px; }
        .event-title h1 .yellow-text { color: var(--primary-yellow); text-shadow: 2px 2px 0px rgba(0, 0, 0, 0.1); }
        .event-title h1 .black-text { color: var(--primary-black); }
        .event-title p { font-size: 1rem; color: #555; font-weight: 500; margin-top: 5px; }
        
        /* Form Elements (Bootstrap's .form-control will be primarily used) */
        .form-control { /* Customizations on top of Bootstrap */
            height: 50px; /* Adjusted height */
            border-radius: 8px;
            border: 2px solid #eaeaea;
            padding: 0 15px; /* Adjusted padding */
            font-size: 0.95rem; /* Adjusted font size */
            transition: all 0.3s ease;
            background-color: #f9f9f9;
        }
        
        .form-control:focus {
            border-color: var(--primary-yellow);
            box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.2);
            background-color: white;
        }
        
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem; /* Adjusted font size */
            display: flex;
            align-items: center;
        }
        
        .form-label i {
            color: var(--primary-yellow);
            margin-right: 8px;
            font-size: 0.9em; /* Relative to label font size */
        }
        
        .form-check-input:checked {
            background-color: var(--primary-yellow);
            border-color: var(--primary-yellow);
        }
        
        /* Login Button */
        .btn-silat {
            background-color: var(--primary-black);
            border: 2px solid var(--primary-yellow);
            color: white;
            padding: 12px; /* Adjusted padding */
            border-radius: 8px;
            font-weight: 600;
            font-size: 1rem; /* Adjusted font size */
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }
        
        .btn-silat::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,193,7,0.3), transparent);
            transition: all 0.6s ease;
        }
        
        .btn-silat:hover {
            transform: translateY(-3px);
            background-color: var(--primary-yellow);
            color: var(--primary-black);
            font-weight: 700;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }
        
        .btn-silat:hover::before { left: 100%; }
        
        /* Footer and Links */
        .footer-text { font-size: 0.75rem; color: #888; margin-top: auto; text-align: center; }
        .register-link a { color: var(--primary-orange); text-decoration: none; font-weight: 600; }
        .register-link a:hover { color: var(--primary-yellow); text-decoration: underline; }

        /* Background Decoration for login side */
        .login-decoration { position: absolute; z-index: 0; }
        .login-decoration-1 { width: 250px; height: 250px; border-radius: 50%; background: linear-gradient(135deg, rgba(33, 33, 33, 0.03) 0%, rgba(33, 33, 33, 0) 100%); top: -125px; left: -125px; }
        .login-decoration-2 { width: 200px; height: 200px; border-radius: 50%; background: linear-gradient(135deg, rgba(255, 193, 7, 0.05) 0%, rgba(255, 193, 7, 0) 100%); bottom: -100px; right: -100px; }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            .login-container { flex-direction: column; height: auto; max-width: 500px; /* Adjusted for single column */ }
            .graphic-side { min-height: 300px; padding: 30px; } /* Adjusted padding */
            .tiger-container { position: relative; height: 220px; bottom: auto; margin-top: 20px; transform: translateX(-50%) scale(0.9); /* Adjust scale and position */ left:50%;}
            .tiger-image { max-width: 200px; }
            .belt-highlight { top: 52%; width: 100px; height: 25px; } /* Adjusted */
            .yellow-glow { top: 58%; width: 220px; height: 220px; } /* Adjusted */
            .event-title h1 { font-size: 2rem; }
            .event-title p { font-size: 0.9rem; }
            .login-side { padding: 40px 30px; } /* Adjusted padding */
        }
        
        @media (max-width: 576px) {
            body { padding: 10px; }
            .login-container { border-radius: 16px; min-height: auto; }
            .graphic-side { padding: 20px 15px; min-height: 220px; } /* Further adjust padding */
            .brand-logo-icon { font-size: 1.5rem; }
            .brand-text { font-size: 1.2rem; }
            .tiger-container { height: 160px; }
            .tiger-image { max-width: 150px; }
            .belt-highlight { top: 50%; width: 70px; height: 18px; }
            .yellow-glow { top: 55%; width: 180px; height: 180px; }
            .login-side { padding: 30px 20px; }
            .event-title h1 { font-size: 1.6rem; }
            .event-title p { font-size: 0.8rem; }
            .form-control { height: 45px; font-size: 0.9rem; }
            .form-label { font-size: 0.85rem; }
            .btn-silat { padding: 10px; font-size: 0.95rem; }
            .footer-text { font-size: 0.7rem; }
        }
    </style>
</head>
<body>
    <div class="login-container">
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
                <img src="{{ asset('app/img/MASKOT.png') }}" alt="Maskot Harimau Silat" class="tiger-image" 
                     onerror="this.onerror=null; this.src='https://placehold.co/380x380/111111/FFC107?text=Maskot+Harimau';">
            </div>
        </div>
        
        <div class="login-side">
            <div class="login-decoration login-decoration-1"></div>
            <div class="login-decoration login-decoration-2"></div>
            
            <div class="event-title">
                <h1><span class="yellow-text">LOGIN</span> <span class="black-text">PELATIH</span></h1>
                <p>Sistem Pendaftaran Pencak Silat</p>
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

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <ul class="mb-0 ps-3"> @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            <form method="POST" action="{{ route('pelatih.login') }}" class="mt-2"> @csrf
                
                <div class="mb-3">
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope"></i>Email Pelatih
                    </label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                           id="email" name="email" placeholder="nama@email.com" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <div class="invalid-feedback d-block"> {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="mb-3"> <label for="password" class="form-label">
                        <i class="fas fa-lock"></i>Password
                    </label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                           id="password" name="password" placeholder="Password" required>
                    @error('password')
                        <div class="invalid-feedback d-block"> {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label" for="remember" style="font-size: 0.9rem; color: #555;">
                            Ingat saya
                        </label>
                    </div>
                    {{-- <a href="#" class="forgot-password">Lupa Password?</a> --}}
                </div>
                
                <button class="btn btn-silat w-100 py-2 mb-3" type="submit"> <i class="fas fa-sign-in-alt me-2"></i>LOGIN
                </button>

                <div class="text-center register-link mt-2"> <p class="mb-0" style="font-size: 0.9rem; color: #555;">Belum punya akun? 
                        <a href="{{ route('pelatih.register.form') }}">Daftar di sini</a>
                    </p>
                </div>
            </form>
            
            <div class="footer-text mt-4"> <p class="mb-0">Panduan Penggunaan | Kebijakan Privasi</p>
                <p class="mb-0">© {{ date('Y') }} Sistem Pendaftaran Pencak Silat. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const particlesContainer = document.getElementById('particles');
            if (particlesContainer) { // Check if element exists
                const numParticles = 25;
                for (let i = 0; i < numParticles; i++) {
                    createParticle(particlesContainer);
                }
            }
        });

        function createParticle(container) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            
            const size = Math.random() * 5 + 3;
            particle.style.width = `${size}px`;
            particle.style.height = `${size}px`;
            
            particle.style.left = `${Math.random() * 100}%`;
            particle.style.top = `${Math.random() * 100}%`; // Start particles from random top positions
            
            const duration = Math.random() * 10 + 10; // 10 to 20 seconds
            particle.style.animationDuration = `${duration}s`;
            particle.style.animationDelay = `${Math.random() * duration}s`; // Delay up to its own duration for staggered start
            
            container.appendChild(particle);
            
            // More robust particle recreation: recreate when animation ends
            particle.addEventListener('animationiteration', () => {
                 // Reset properties for a new animation cycle if needed, or simply let it loop
                 // For true "recreation", you might remove and add a new one:
                 // particle.remove();
                 // createParticle(container);
            });
             // Fallback: if animationiteration is not reliable or for single-run animations
            setTimeout(() => {
                if (particle.parentElement === container) { // Check if still part of DOM
                    // particle.remove(); // Optional: remove and recreate for variety
                    // createParticle(container);
                }
            }, duration * 1000 + parseFloat(particle.style.animationDelay || "0s") * 1000);
        }

        // Auto-dismiss alerts
        const alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (bootstrap && bootstrap.Alert) { // Check if Bootstrap JS is loaded
                    const alertInstance = bootstrap.Alert.getInstance(alert);
                    if (alertInstance) {
                        alertInstance.close();
                    } else { // Fallback if instance not found but Bootstrap is there
                        new bootstrap.Alert(alert).close();
                    }
                } else { // Fallback if Bootstrap JS is not available
                    alert.style.display = 'none';
                }
            }, 7000); // Dismiss after 7 seconds
        });
    </script>
</body>
</html>
