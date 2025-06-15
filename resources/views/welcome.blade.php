<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }} - Hệ thống Quản lý Chuỗi Cung ứng</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Heroicons CDN for modern SVG icons -->
    <script src="https://unpkg.com/@heroicons/vue@2.0.18/dist/heroicons.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .fade-in {
            animation: fadeInUp 1s cubic-bezier(.39,.575,.565,1.000) both;
        }
        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .btn-glow {
            box-shadow: 0 0 16px 0 rgba(99,102,241,0.3), 0 4px 24px 0 rgba(139,92,246,0.15);
            transition: box-shadow 0.3s, transform 0.2s;
        }
        .btn-glow:hover {
            box-shadow: 0 0 32px 0 rgba(99,102,241,0.5), 0 8px 32px 0 rgba(139,92,246,0.25);
            transform: translateY(-2px) scale(1.03);
        }
        .glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(16px) saturate(180%);
            border: 1.5px solid rgba(99,102,241,0.08);
        }
        .gradient-anim {
            background: linear-gradient(270deg, #a5b4fc, #f3e8ff, #c7d2fe, #f3e8ff, #a5b4fc);
            background-size: 1200% 1200%;
            animation: gradientMove 12s ease infinite;
        }
        @keyframes gradientMove {
            0% {background-position:0% 50%}
            50% {background-position:100% 50%}
            100% {background-position:0% 50%}
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col justify-center items-center gradient-anim">
    <div class="relative w-full max-w-full lg:max-w-7xl mx-auto px-6 lg:px-8 py-12 fade-in">
        <div class="flex flex-col items-center justify-center min-h-[calc(100vh-6rem)] py-12 px-4 sm:px-6 lg:px-8 glass shadow-2xl rounded-3xl border">
            <div class="text-center">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Laravel.svg/1200px-Laravel.svg.png"
                     alt="Laravel Logo"
                     class="mx-auto h-28 w-auto mb-8 drop-shadow-lg animate-bounce-slow"
                     style="animation: bounce 2.5s infinite;">
                <h1 class="text-5xl font-extrabold text-indigo-800 mb-4 tracking-tight drop-shadow fade-in" style="animation-delay:0.2s;">
                    Chào mừng đến với Hệ thống SCM
                </h1>
                <p class="mt-3 text-xl text-gray-700 leading-relaxed max-w-2xl mx-auto fade-in" style="animation-delay:0.4s;">
                    Giải pháp toàn diện giúp bạn quản lý hiệu quả chuỗi cung ứng: từ tồn kho, đơn hàng, đến vận chuyển và báo cáo.<br>
                    <span class="text-indigo-600 font-semibold">Tối ưu hóa hoạt động, nâng cao năng suất!</span>
                </p>
            </div>
            <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6 fade-in" style="animation-delay:0.6s;">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-10 py-5 border border-transparent text-xl font-semibold rounded-xl btn-glow text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out group">
                    <!-- Heroicon: Arrow Right On Rectangle -->
                    <svg class="w-7 h-7 mr-3 -ml-1 text-white group-hover:scale-110 transition"
                         fill="none" stroke="currentColor" stroke-width="2"
                         viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3-3l-3-3m3 3H9"></path>
                    </svg>
                    Đăng nhập ngay
                </a>
                @if (Route::has('register'))
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center px-10 py-5 border-2 border-indigo-600 text-xl font-semibold rounded-xl btn-glow text-indigo-700 bg-white hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out group">
                        <!-- Heroicon: User Plus -->
                        <svg class="w-7 h-7 mr-3 -ml-1 text-indigo-600 group-hover:scale-110 transition"
                             fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7m14 0a7 7 0 00-7-7m6-3h6m-3-3v6"></path>
                        </svg>
                        Đăng ký tài khoản
                    </a>
                @endif
            </div>
        </div>
    </div>
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0);}
            50% { transform: translateY(-18px);}
        }
        .animate-bounce-slow {
            animation: bounce 2.5s infinite;
        }
    </style>
</body>
</html>
