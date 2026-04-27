<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>SpendWise | Track Your Expenses, Control Your Money</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans bg-white text-navy-950 selection:bg-gold-500/30">
        
        <!-- Navbar -->
        <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-md border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex items-center space-x-2 lg:space-x-3">
                        <img src="/logo-icon.png" alt="SpendWise Icon" class="h-10 lg:h-14 w-auto object-contain">
                        <span class="text-xl lg:text-2xl font-bold tracking-tight text-navy-950 font-outfit uppercase">Spend<span class="text-gold-600">Wise</span></span>
                    </div>

                    <div class="flex items-center space-x-2 lg:space-x-4">
                        @if (Route::has('login'))
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-4 lg:px-6 py-2.5 text-xs lg:text-sm font-bold text-navy-950 hover:text-gold-600 transition duration-200">Dashboard</a>
                            @else
                                <a href="{{ route('login') }}" class="px-4 lg:px-6 py-2.5 text-xs lg:text-sm font-bold text-navy-950 hover:text-gold-600 transition duration-200">Login</a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-4 lg:px-6 py-2.5 bg-navy-950 text-white rounded-xl text-xs lg:text-sm font-bold hover:bg-navy-900 transition duration-200 shadow-lg shadow-navy-900/20">Get Started</a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 pointer-events-none">
                <div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-gold-500/10 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[-10%] w-[40%] h-[40%] bg-navy-500/5 rounded-full blur-[120px]"></div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="inline-flex items-center px-4 py-2 rounded-full bg-gold-50 border border-gold-100 text-gold-700 text-xs font-extrabold tracking-widest uppercase mb-8 animate-fade-in">
                    <span class="flex size-2 rounded-full bg-gold-600 mr-2 animate-pulse"></span>
                    Master Your Finances
                </div>
                
                <h1 class="text-4xl lg:text-7xl font-semibold font-outfit text-navy-950 tracking-tight leading-tight lg:leading-[1.1] mb-6 lg:mb-8 max-w-4xl mx-auto">
                    Track your expenses. <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-gold-600 to-gold-400">Control your money.</span>
                </h1>

                <p class="text-lg lg:text-xl text-navy-600 max-w-2xl mx-auto mb-10 lg:mb-12 leading-relaxed px-4 lg:px-0">
                    The modern, intuitive dashboard for personal finance. Get insights into your spending habits and reach your financial goals with SpendWise.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-10 py-4 bg-navy-950 text-white rounded-2xl font-bold text-lg hover:bg-navy-900 transition duration-200 shadow-2xl shadow-navy-900/30 flex items-center justify-center group">
                        Start for Free
                        <svg class="ml-2 size-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                        </svg>
                    </a>
                    <button onclick="openDemoModal()" class="w-full sm:w-auto px-10 py-4 bg-white text-navy-950 border-2 border-navy-950/10 rounded-2xl font-bold text-lg hover:border-navy-950/20 transition duration-200 flex items-center justify-center gap-2 group">
                        <svg class="size-5 text-gold-600 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                        View Demo
                    </button>
                </div>

                <!-- Dashboard Preview / Demo Video -->
                <div id="demo-section" class="mt-12 lg:mt-20 relative max-w-5xl mx-auto px-4 lg:px-0">
                    <div class="absolute -inset-1 bg-gradient-to-r from-gold-500 to-navy-500 rounded-3xl blur opacity-20"></div>
                    <div class="relative bg-navy-950 rounded-2xl shadow-2xl border border-navy-800 overflow-hidden cursor-pointer group" onclick="openDemoModal()">
                        <!-- Video thumbnail / muted preview -->
                        <video id="preview-video" class="w-full h-auto opacity-70 group-hover:opacity-90 transition duration-300" muted playsinline>
                            <source src="/demo_video.mp4" type="video/mp4">
                        </video>
                        <!-- Play overlay -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-navy-950/40 group-hover:bg-navy-950/30 transition duration-300">
                            <div class="size-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-full flex items-center justify-center shadow-2xl group-hover:scale-110 transition-transform duration-300">
                                <svg class="size-9 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                            </div>
                            <p class="mt-4 text-white font-bold text-sm uppercase tracking-widest opacity-80">Watch Demo</p>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/60 via-transparent to-transparent pointer-events-none"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div id="features" class="py-16 lg:py-24 bg-navy-50/30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12 sm:mb-20">
                    <h2 class="text-3xl lg:text-5xl font-extrabold font-outfit text-navy-950 mb-4">Powerful Features</h2>
                    <p class="text-base sm:text-lg text-navy-600 max-w-2xl mx-auto">Everything you need to manage your personal finances like a pro.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div class="bg-white p-10 rounded-[2rem] shadow-xl border border-white hover:border-gold-200 transition-all duration-300 group">
                        <div class="size-16 bg-gold-100 rounded-2xl flex items-center justify-center text-gold-700 mb-8 group-hover:bg-gold-700 group-hover:text-white transition-colors duration-300">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-navy-950 mb-4">Track Expenses</h3>
                        <p class="text-navy-600 leading-relaxed">Quickly record your daily spending and categorize transactions with ease. Stay aware of where every cent goes.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="bg-white p-10 rounded-[2rem] shadow-xl border border-white hover:border-gold-200 transition-all duration-300 group">
                        <div class="size-16 bg-navy-100 rounded-2xl flex items-center justify-center text-navy-900 mb-8 group-hover:bg-navy-900 group-hover:text-white transition-colors duration-300">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-navy-950 mb-4">Budget Control</h3>
                        <p class="text-navy-600 leading-relaxed">Set monthly limits for different categories and get notified when you're close to exceeding them.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="bg-white p-10 rounded-[2rem] shadow-xl border border-white hover:border-gold-200 transition-all duration-300 group">
                        <div class="size-16 bg-gold-100 rounded-2xl flex items-center justify-center text-gold-700 mb-8 group-hover:bg-gold-700 group-hover:text-white transition-colors duration-300">
                            <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-navy-950 mb-4">Detailed Reports</h3>
                        <p class="text-navy-600 leading-relaxed">Visualize your financial health with beautiful charts and downloadable summaries of your income vs. expenses.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-12 lg:py-20 bg-white">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-navy-950 rounded-3xl p-8 lg:p-14 text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-gold-500 opacity-10 rounded-full blur-3xl -mr-32 -mt-32"></div>
                    <div class="relative z-10">
                        <div class="text-gold-500 font-extrabold tracking-widest uppercase text-sm mb-4">Spend<span class="text-white">Wise</span></div>
                        <h2 class="text-2xl lg:text-4xl font-semibold text-white font-outfit mb-6 tracking-tight leading-tight">Ready to take control of your financial future?</h2>
                        <a href="{{ route('register') }}" class="inline-flex px-8 lg:px-10 py-3 lg:py-4 bg-gold-700 text-white rounded-xl font-bold text-base lg:text-lg hover:bg-gold-800 transition duration-200 shadow-lg uppercase tracking-widest">
                            Create Free Account
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="py-12 border-t border-gray-100 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-3 mb-6 md:mb-0">
                    <span class="text-xl font-bold tracking-tight text-navy-950 font-outfit uppercase grayscale opacity-80">Spend<span class="text-gold-600">Wise</span></span>
                </div>
                <div class="text-navy-400 text-sm font-medium">
                    &copy; {{ date('Y') }} SpendWise. All rights reserved.
                </div>
            </div>
        </footer>

        <!-- Demo Video Modal/Lightbox -->
        <div id="demo-modal" class="fixed inset-0 z-[999] hidden items-center justify-center p-4 bg-navy-950/90 backdrop-blur-md" onclick="closeDemoModal(event)">
            <div class="relative w-full max-w-5xl rounded-3xl overflow-hidden shadow-2xl border border-white/10" onclick="event.stopPropagation()">
                <!-- Close button -->
                <button onclick="closeDemoModal()" class="absolute top-4 right-4 z-10 size-10 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white transition">
                    <svg class="size-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <!-- Header bar -->
                <div class="bg-navy-900 px-6 py-3 flex items-center gap-2">
                    <span class="size-3 rounded-full bg-red-400"></span>
                    <span class="size-3 rounded-full bg-gold-400"></span>
                    <span class="size-3 rounded-full bg-green-400"></span>
                    <span class="ml-4 text-xs text-navy-400 font-mono">SpendWise — Live Demo</span>
                </div>
                <!-- The full-quality video -->
                <video id="modal-video" class="w-full h-auto bg-black" controls playsinline>
                    <source src="/demo_video.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>

        <style>
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-10px); }
                100% { transform: translateY(0px); }
            }
            .animate-float { animation: float 6s ease-in-out infinite; }
            .font-outfit { font-family: 'Outfit', sans-serif; }
            #demo-modal.open { display: flex; }
        </style>

        <script>
            function openDemoModal() {
                const modal = document.getElementById('demo-modal');
                const video = document.getElementById('modal-video');
                modal.classList.remove('hidden');
                modal.classList.add('open');
                document.body.style.overflow = 'hidden';
                video.currentTime = 0;
                video.play();
            }

            function closeDemoModal(e) {
                const modal = document.getElementById('demo-modal');
                const video = document.getElementById('modal-video');
                modal.classList.add('hidden');
                modal.classList.remove('open');
                document.body.style.overflow = '';
                video.pause();
            }

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeDemoModal();
            });
        </script>
    </body>
</html>
