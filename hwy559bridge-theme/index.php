<?php
/**
 * HWY 559 Bridge Theme - Main Index Template
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HWY 559 Bridge | Growth Platform & Dealership OS</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome 6 CSS Fix -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Overpass:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Overpass', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            orange: '#EF8522',
                            black: '#000000',
                            white: '#FFFFFF',
                            gray: '#F7F6F3'
                        }
                    },
                    animation: {
                        'fade-up': 'fadeUp 0.8s ease both',
                        'draw-line': 'drawLine 1.4s ease forwards',
                        'float': 'float 4s ease-in-out infinite',
                        'pulse-glow': 'pulseGlow 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    keyframes: {
                        fadeUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        drawLine: {
                            'to': { strokeDashoffset: '0' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-6px)' },
                        },
                        pulseGlow: {
                            '0%, 100%': { opacity: '1', filter: 'drop-shadow(0 0 8px rgba(239, 133, 34, 0.6))' },
                            '50%': { opacity: '0.6', filter: 'drop-shadow(0 0 2px rgba(239, 133, 34, 0.2))' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #F7F6F3; }
        ::-webkit-scrollbar-thumb { background: #ccc; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #EF8522; }

        html { scroll-behavior: smooth; }
        
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .delay-300 { animation-delay: 300ms; }

        .svg-line-1 { stroke-dasharray: 505; stroke-dashoffset: 505; animation-delay: 0.10s; }
        .svg-line-2 { stroke-dasharray: 397; stroke-dashoffset: 397; animation-delay: 0.22s; }
        .svg-line-3 { stroke-dasharray: 330; stroke-dashoffset: 330; animation-delay: 0.34s; }
        .svg-line-4 { stroke-dasharray: 330; stroke-dashoffset: 330; animation-delay: 0.34s; }
        .svg-line-5 { stroke-dasharray: 397; stroke-dashoffset: 397; animation-delay: 0.22s; }
        .svg-line-6 { stroke-dasharray: 505; stroke-dashoffset: 505; animation-delay: 0.10s; }

        .icon-box {
            background-color: #FFFFFF;
            color: #EF8522;
            box-shadow: 0 4px 12px rgba(0,0,0,0.06);
            border: 1px solid rgba(239, 133, 34, 0.1);
        }

        .channel-node {
            cursor: pointer;
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        .channel-node:hover {
            transform: scale(1.1);
            filter: drop-shadow(0 0 12px rgba(239, 133, 34, 0.8));
        }
    </style>
    <?php wp_head(); ?>
</head>
<body <?php body_class('font-sans antialiased text-brand-black bg-white selection:bg-brand-orange selection:text-white'); ?>>

    <!-- Header -->
    <header class="sticky top-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-100 w-full transition-all duration-300" id="navbar">
        <div class="max-w-[1360px] mx-auto px-6 lg:px-10 h-20 flex items-center justify-between gap-6">
            <!-- Logo -->
            <a href="<?php echo home_url('/'); ?>" class="flex items-center flex-shrink-0">
                <img src="<?php echo content_url('/plugins/bridge-plugin/assets/logos/hwy559bridge_logo_final.png'); ?>" alt="HWY 559 Bridge" class="h-10 w-auto" onerror="this.onerror=null; this.src='https://placehold.co/400x100/FFFFFF/EF8522?text=HWY+559+BRIDGE&font=oswald'">
            </a>
            
            <!-- Desktop Nav -->
            <nav class="hidden md:flex items-center gap-8">
                <a href="#" class="text-brand-orange text-[14.5px] font-bold transition-colors">Home</a>
                <a href="#solutions" class="text-brand-black hover:text-brand-orange text-[14.5px] font-bold transition-colors">Solutions</a>
                <a href="#industries" class="text-brand-black hover:text-brand-orange text-[14.5px] font-bold transition-colors">Industries</a>
                <a href="#proof" class="text-brand-black hover:text-brand-orange text-[14.5px] font-bold transition-colors">Success Stories</a>
                <a href="#comparison" class="text-brand-black hover:text-brand-orange text-[14.5px] font-bold transition-colors">Why Bridge</a>
            </nav>

            <!-- CTA Buttons -->
            <div class="hidden sm:flex items-center gap-5 flex-shrink-0">
                <a href="#contact" class="text-brand-black/60 hover:text-brand-orange text-[13.5px] font-bold transition-colors">Contact</a>
                <a href="#demo" class="bg-brand-orange text-white text-sm font-bold py-2.5 px-6 rounded-md hover:bg-brand-black hover:shadow-lg hover:shadow-brand-black/20 transition-all duration-300">
                    Get A Demo
                </a>
            </div>

            <!-- Mobile Menu Toggle -->
            <button id="mobileMenuBtn" class="md:hidden text-brand-black text-2xl focus:outline-none" aria-label="Toggle Menu">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <!-- Mobile Menu Container -->
        <div id="mobileMenu" class="hidden md:hidden bg-white border-b border-gray-200 px-6 py-4 flex-col gap-4">
            <a href="#" class="text-brand-orange font-bold text-base">Home</a>
            <a href="#solutions" class="text-brand-black hover:text-brand-orange font-bold text-base">Solutions</a>
            <a href="#industries" class="text-brand-black hover:text-brand-orange font-bold text-base">Industries</a>
            <a href="#proof" class="text-brand-black hover:text-brand-orange font-bold text-base">Success Stories</a>
            <a href="#comparison" class="text-brand-black hover:text-brand-orange font-bold text-base">Why Bridge</a>
            <a href="#demo" class="bg-brand-orange text-white text-center font-bold py-3 px-6 rounded-md mt-2">Get A Demo</a>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative pt-20 pb-24 lg:pt-28 lg:pb-32 px-6 lg:px-10 max-w-[1360px] mx-auto flex flex-col lg:flex-row items-center gap-16 overflow-hidden">
        <!-- Left Content -->
        <div class="flex-1 w-full lg:min-w-[460px] animate-fade-up z-10">
            <div class="inline-block bg-brand-orange/10 text-brand-orange text-xs font-bold tracking-[1.5px] uppercase py-2 px-4 rounded-full mb-6">
                All-In-One Growth Platform
            </div>
            <h1 class="text-5xl lg:text-[64px] leading-[1.05] font-black tracking-tight mb-6 text-brand-black">
                One Platform.<br>
                Every Channel.<br>
                <span class="text-brand-orange">Measurable Growth.</span>
            </h1>
            <p class="text-lg leading-relaxed text-brand-black/60 max-w-lg mb-10 font-medium">
                Website, inventory, marketing, and analytics — connected in a single system that reports back real numbers, not guesswork. Built on 15 years of dealer-specific data.
            </p>
            <div class="flex flex-wrap gap-4 mb-6">
                <a href="#demo" class="bg-brand-orange text-white font-bold text-[15.5px] py-4 px-8 rounded-md shadow-[0_8px_20px_rgba(239,133,34,0.25)] hover:bg-brand-black hover:-translate-y-0.5 hover:shadow-[0_12px_24px_rgba(0,0,0,0.2)] transition-all duration-300">
                    Get A Demo
                </a>
                <a href="#solutions" class="border-2 border-brand-black text-brand-black font-bold text-[15.5px] py-3.5 px-8 rounded-md hover:bg-brand-black hover:text-white transition-all duration-300">
                    See How It Works
                </a>
            </div>
            <p class="text-[13.5px] text-brand-black/45 font-bold"><i class="fas fa-shield-alt text-brand-orange mr-1"></i> No pitch. No pressure. Just 30 minutes.</p>
        </div>

        <!-- Right Content: Interactive Fan-Out Cable SVG -->
        <div class="flex-1 w-full lg:min-w-[500px] relative animate-fade-up delay-200">
            
            <!-- Background Image Overlay -->
            <div class="absolute inset-0 z-0 rounded-2xl overflow-hidden shadow-2xl opacity-20 transform translate-y-12 scale-95">
                <img src="https://images.unsplash.com/photo-1601584841961-042843b2f2d9?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" alt="Commercial Equipment Dealership" class="w-full h-full object-cover filter grayscale">
                <div class="absolute inset-0 bg-gradient-to-t from-white via-white/80 to-transparent"></div>
            </div>

            <!-- Structural Cable Fan-Out SVG -->
            <div class="relative z-10 w-full pt-6">
                <svg viewBox="0 0 900 390" class="w-full h-auto drop-shadow-xl">
                    <!-- Base Ground Line -->
                    <line x1="60" y1="340" x2="840" y2="340" stroke="#000000" stroke-width="2" opacity="0.12"></line>
                    
                    <!-- Fan-Out Cables -->
                    <line x1="450" y1="30" x2="60" y2="340" stroke="#EF8522" stroke-width="2.5" stroke-linecap="round" class="animate-draw-line svg-line-1"></line>
                    <line x1="450" y1="30" x2="216" y2="340" stroke="#EF8522" stroke-width="2.5" stroke-linecap="round" class="animate-draw-line svg-line-2"></line>
                    <line x1="450" y1="30" x2="372" y2="340" stroke="#EF8522" stroke-width="2.5" stroke-linecap="round" class="animate-draw-line svg-line-3"></line>
                    <line x1="450" y1="30" x2="528" y2="340" stroke="#EF8522" stroke-width="2.5" stroke-linecap="round" class="animate-draw-line svg-line-4"></line>
                    <line x1="450" y1="30" x2="684" y2="340" stroke="#EF8522" stroke-width="2.5" stroke-linecap="round" class="animate-draw-line svg-line-5"></line>
                    <line x1="450" y1="30" x2="840" y2="340" stroke="#EF8522" stroke-width="2.5" stroke-linecap="round" class="animate-draw-line svg-line-6"></line>
                    
                    <!-- Top Core Inventory Anchor Point -->
                    <circle cx="450" cy="30" r="10" fill="#000000" stroke="#EF8522" stroke-width="3"></circle>
                    <text x="450" y="5" text-anchor="middle" fill="#000000" font-size="12" font-weight="900" letter-spacing="1">BRIDGE LIVE INVENTORY LEDGER</text>
                    
                    <!-- Nodes (Interactive Channels) -->
                    <!-- Channel 1: Website -->
                    <g class="channel-node animate-float" style="animation-delay: 0s;" onclick="alert('Channel: Website Showroom — Auto-publishes inventory specs & high-res photos.')">
                        <circle cx="60" cy="340" r="24" fill="#FFFFFF" stroke="#EF8522" stroke-width="3"></circle>
                        <foreignObject x="45" y="325" width="30" height="30">
                            <div class="flex items-center justify-center h-full text-brand-orange text-lg"><i class="fas fa-desktop"></i></div>
                        </foreignObject>
                        <text x="60" y="378" text-anchor="middle" fill="#000" font-size="13" font-weight="700">Website</text>
                    </g>

                    <!-- Channel 2: Search -->
                    <g class="channel-node animate-float" style="animation-delay: 0.5s;" onclick="alert('Channel: Search & Organic SEO — Schema structured so Google finds every unit.')">
                        <circle cx="216" cy="340" r="24" fill="#FFFFFF" stroke="#EF8522" stroke-width="3"></circle>
                        <foreignObject x="201" y="325" width="30" height="30">
                            <div class="flex items-center justify-center h-full text-brand-orange text-lg"><i class="fas fa-search"></i></div>
                        </foreignObject>
                        <text x="216" y="378" text-anchor="middle" fill="#000" font-size="13" font-weight="700">Search</text>
                    </g>

                    <!-- Channel 3: Paid Ads -->
                    <g class="channel-node animate-float" style="animation-delay: 1s;" onclick="alert('Channel: Paid Ads — Google Merchant & Meta inventory ad campaigns.')">
                        <circle cx="372" cy="340" r="24" fill="#FFFFFF" stroke="#EF8522" stroke-width="3"></circle>
                        <foreignObject x="357" y="325" width="30" height="30">
                            <div class="flex items-center justify-center h-full text-brand-orange text-lg"><i class="fas fa-bullhorn"></i></div>
                        </foreignObject>
                        <text x="372" y="378" text-anchor="middle" fill="#000" font-size="13" font-weight="700">Paid Ads</text>
                    </g>

                    <!-- Channel 4: Social -->
                    <g class="channel-node animate-float" style="animation-delay: 1.5s;" onclick="alert('Channel: Social — Automated Meta catalog sync & quick social post generator.')">
                        <circle cx="528" cy="340" r="24" fill="#FFFFFF" stroke="#EF8522" stroke-width="3"></circle>
                        <foreignObject x="513" y="325" width="30" height="30">
                            <div class="flex items-center justify-center h-full text-brand-orange text-lg"><i class="fas fa-share-alt"></i></div>
                        </foreignObject>
                        <text x="528" y="378" text-anchor="middle" fill="#000" font-size="13" font-weight="700">Social</text>
                    </g>

                    <!-- Channel 5: Email -->
                    <g class="channel-node animate-float" style="animation-delay: 2s;" onclick="alert('Channel: Email Marketing — Targeted price-drop & new unit notifications.')">
                        <circle cx="684" cy="340" r="24" fill="#FFFFFF" stroke="#EF8522" stroke-width="3"></circle>
                        <foreignObject x="669" y="325" width="30" height="30">
                            <div class="flex items-center justify-center h-full text-brand-orange text-lg"><i class="fas fa-envelope"></i></div>
                        </foreignObject>
                        <text x="684" y="378" text-anchor="middle" fill="#000" font-size="13" font-weight="700">Email</text>
                    </g>

                    <!-- Channel 6: Syndication -->
                    <g class="channel-node animate-float" style="animation-delay: 2.5s;" onclick="alert('Channel: Syndication — One-click multi-marketplace feed sync.')">
                        <circle cx="840" cy="340" r="24" fill="#FFFFFF" stroke="#EF8522" stroke-width="3"></circle>
                        <foreignObject x="825" y="325" width="30" height="30">
                            <div class="flex items-center justify-center h-full text-brand-orange text-lg"><i class="fas fa-cog"></i></div>
                        </foreignObject>
                        <text x="840" y="378" text-anchor="middle" fill="#000" font-size="13" font-weight="700">Syndication</text>
                    </g>
                </svg>
            </div>
        </div>
    </section>

    <!-- Stats Ribbon -->
    <section class="bg-brand-orange py-14 px-6 lg:px-10 border-y border-brand-black/10">
        <div class="max-w-[1200px] mx-auto flex flex-wrap justify-between items-center gap-8 text-center">
            <div class="flex-1 min-w-[200px]">
                <div class="text-4xl font-black text-white mb-1">1,200+</div>
                <div class="text-[12.5px] font-bold tracking-widest text-white/80 uppercase">Local Dealers Served</div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <div class="text-4xl font-black text-white mb-1">4.8M</div>
                <div class="text-[12.5px] font-bold tracking-widest text-white/80 uppercase">Buyers Connected</div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <div class="text-4xl font-black text-white mb-1">40+</div>
                <div class="text-[12.5px] font-bold tracking-widest text-white/80 uppercase">OEM Partners</div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <div class="text-4xl font-black text-white mb-1">15</div>
                <div class="text-[12.5px] font-bold tracking-widest text-white/80 uppercase">Years Dealer Focus</div>
            </div>
        </div>
    </section>

    <!-- Solutions Section -->
    <section id="solutions" class="py-28 px-6 lg:px-10 max-w-[1240px] mx-auto">
        <div class="text-center max-w-2xl mx-auto mb-10">
            <div class="text-brand-orange text-[12.5px] font-bold tracking-[1.5px] mb-4 uppercase">What We Do</div>
            <h2 class="text-4xl lg:text-[42px] font-black tracking-tight mb-5 text-brand-black leading-tight">One Entry Point.<br>Every Channel You Need.</h2>
            <p class="text-lg text-brand-black/60 font-medium">The same principle that holds up a bridge holds up your growth — one strong connection point, distributing the load across everywhere your customers are.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-4">
            <!-- Card 1 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-10 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:border-brand-orange/30 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl bg-brand-orange text-white flex items-center justify-center font-black text-lg shadow-md shadow-brand-orange/20">01</div>
                    <div class="w-12 h-12 rounded-full icon-box flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-desktop"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-brand-black mb-4">Attract More Visitors</h3>
                <p class="text-[15px] leading-relaxed text-brand-black/60 mb-6 font-medium">Fast, mobile-first websites engineered for search from the ground up — structured so search engines find you and built so visitors stay.</p>
                <div class="text-sm font-bold text-brand-orange"><i class="fas fa-chart-line mr-1"></i> Average 28% increase in qualified traffic.</div>
            </div>

            <!-- Card 2 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-10 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:border-brand-orange/30 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl bg-brand-orange text-white flex items-center justify-center font-black text-lg shadow-md shadow-brand-orange/20">02</div>
                    <div class="w-12 h-12 rounded-full icon-box flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-random"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-brand-black mb-4">Convert More Leads</h3>
                <p class="text-[15px] leading-relaxed text-brand-black/60 mb-6 font-medium">Smart forms, instant lead routing, and inventory syndicated to every marketplace your buyers already browse.</p>
                <div class="text-sm font-bold text-brand-orange"><i class="fas fa-sync mr-1"></i> Listings syndicated to 50+ marketplaces.</div>
            </div>

            <!-- Card 3 -->
            <div class="bg-white border border-gray-100 rounded-2xl p-10 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgba(0,0,0,0.06)] hover:border-brand-orange/30 transition-all duration-300 group">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-12 h-12 rounded-xl bg-brand-orange text-white flex items-center justify-center font-black text-lg shadow-md shadow-brand-orange/20">03</div>
                    <div class="w-12 h-12 rounded-full icon-box flex items-center justify-center text-xl group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
                <h3 class="text-2xl font-black text-brand-black mb-4">Grow With Real Campaigns</h3>
                <p class="text-[15px] leading-relaxed text-brand-black/60 mb-6 font-medium">SEO, paid search, and social managed by a team that reads the reports so your team doesn't have to.</p>
                <div class="text-sm font-bold text-brand-orange"><i class="fas fa-arrow-up mr-1"></i> 35% more site traffic within 90 days.</div>
            </div>
        </div>
    </section>

    <!-- Proof & Stats Section -->
    <section id="proof" class="bg-brand-black text-white py-28 px-6 lg:px-10 relative overflow-hidden">
        <div class="max-w-[1240px] mx-auto relative z-10">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <div class="text-brand-orange text-[12.5px] font-bold tracking-[1.5px] mb-4 uppercase">And It Works</div>
                <h2 class="text-4xl lg:text-[42px] font-black tracking-tight mb-5 leading-tight">15 Years Building For This.</h2>
                <p class="text-lg text-white/60 font-medium">We've seen what drives results — and what doesn't. Our infrastructure is built to deliver.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-8 mb-20 border-b border-white/10 pb-16">
                <div class="text-center">
                    <div class="text-4xl font-black text-brand-orange mb-2">1,200<span class="text-2xl">+</span></div>
                    <div class="text-xs font-bold tracking-wider text-white/50 uppercase">Dealers</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-brand-orange mb-2">4.8<span class="text-2xl">M</span></div>
                    <div class="text-xs font-bold tracking-wider text-white/50 uppercase">Visitors</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-brand-orange mb-2">15</div>
                    <div class="text-xs font-bold tracking-wider text-white/50 uppercase">Years Focus</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-brand-orange mb-2">40<span class="text-2xl">+</span></div>
                    <div class="text-xs font-bold tracking-wider text-white/50 uppercase">OEM Partners</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-brand-orange mb-2">96<span class="text-2xl">%</span></div>
                    <div class="text-xs font-bold tracking-wider text-white/50 uppercase">Support Rating</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-black text-brand-orange mb-2">92<span class="text-2xl">%</span></div>
                    <div class="text-xs font-bold tracking-wider text-white/50 uppercase">Retention</div>
                </div>
            </div>

            <!-- Feature Highlight Cards -->
            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white/5 border border-white/10 rounded-2xl p-10 hover:border-brand-orange/50 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center mb-6 shadow-[0_0_15px_rgba(239,133,34,0.3)]">
                        <i class="fas fa-bolt text-brand-orange text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-4">30% More Leads. Guaranteed.</h3>
                    <p class="text-[15px] leading-relaxed text-white/60 font-medium m-0">Dealers on hwy559bridge see an average 30% increase in qualified leads. Add SEO and that number climbs past 45%. We put it in writing.</p>
                </div>
                <div class="bg-white/5 border border-white/10 rounded-2xl p-10 hover:border-brand-orange/50 transition-colors group">
                    <div class="w-12 h-12 rounded-xl bg-white flex items-center justify-center mb-6 shadow-[0_0_15px_rgba(239,133,34,0.3)]">
                        <i class="fas fa-envelope-open-text text-brand-orange text-xl"></i>
                    </div>
                    <h3 class="text-2xl font-black mb-4">Email That Actually Gets Opened.</h3>
                    <p class="text-[15px] leading-relaxed text-white/60 font-medium m-0">Our automated email tools average a 54% open rate and 31% click-through, versus a 21% and 8% industry average. That gap is 15 years of dealer-only focus.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Comparison Table Section -->
    <section id="comparison" class="bg-[#1c1d20] py-28 px-6 lg:px-10 border-y border-[#333538]">
        <div class="max-w-[1100px] mx-auto">
            
            <!-- Header -->
            <div class="mb-14">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-[2px] bg-brand-orange"></div>
                    <div class="text-brand-orange text-[12.5px] font-bold tracking-[2px] uppercase">The Independent Question</div>
                </div>
                <h2 class="text-5xl lg:text-[56px] font-black tracking-tight mb-6 text-white leading-tight">
                    Ask yourself <span class="text-brand-orange">one thing.</span>
                </h2>
                <p class="text-xl text-white/80 font-medium max-w-3xl leading-relaxed">
                    Could you export your entire inventory and sold-price history out of your current system tomorrow — and post it anywhere you want, including sites that compete with the marketplace you use now?
                </p>
            </div>

            <!-- Comparison Matrix -->
            <div class="border border-[#333538] rounded-lg overflow-hidden flex flex-col">
                
                <!-- Table Headers -->
                <div class="flex flex-col md:flex-row">
                    <div class="flex-1 bg-[#222427] p-8 border-b md:border-b-0 md:border-r border-[#333538] flex items-center gap-4">
                        <span class="bg-white/10 text-white/60 text-[11px] font-bold px-3 py-1.5 rounded tracking-widest uppercase">Walled Garden</span>
                        <span class="text-2xl font-black text-white">The old way</span>
                    </div>
                    <div class="flex-1 bg-[#131416] p-8 flex items-center gap-4">
                        <span class="bg-brand-orange text-brand-black text-[11px] font-bold px-3 py-1.5 rounded tracking-widest uppercase">Bridge</span>
                        <span class="text-2xl font-black text-white">Independent by design</span>
                    </div>
                </div>
                
                <!-- Row 1 -->
                <div class="flex flex-col md:flex-row border-t border-[#333538]">
                    <div class="flex-1 bg-[#222427] p-6 lg:p-8 border-b md:border-b-0 md:border-r border-[#333538] flex items-start gap-4">
                        <i class="fas fa-times text-red-500 text-lg mt-1"></i>
                        <p class="text-[16px] text-white/80 font-medium m-0 leading-relaxed">Your listings live in <strong class="text-white">their</strong> cloud, on <strong class="text-white">their</strong> terms.</p>
                    </div>
                    <div class="flex-1 bg-[#131416] p-6 lg:p-8 flex items-start gap-4">
                        <i class="fas fa-check text-brand-orange text-lg mt-1"></i>
                        <p class="text-[16px] text-white/90 font-medium m-0 leading-relaxed">Your listings live in <strong class="text-white">your</strong> database, on <strong class="text-white">your</strong> server.</p>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="flex flex-col md:flex-row border-t border-[#333538]">
                    <div class="flex-1 bg-[#222427] p-6 lg:p-8 border-b md:border-b-0 md:border-r border-[#333538] flex items-start gap-4">
                        <i class="fas fa-times text-red-500 text-lg mt-1"></i>
                        <p class="text-[16px] text-white/80 font-medium m-0 leading-relaxed">Your sold prices feed the pricing reports they sell back to you.</p>
                    </div>
                    <div class="flex-1 bg-[#131416] p-6 lg:p-8 flex items-start gap-4">
                        <i class="fas fa-check text-brand-orange text-lg mt-1"></i>
                        <p class="text-[16px] text-white/90 font-medium m-0 leading-relaxed">Your sold prices stay on your books. Nowhere else.</p>
                    </div>
                </div>

                <!-- Row 3 -->
                <div class="flex flex-col md:flex-row border-t border-[#333538]">
                    <div class="flex-1 bg-[#222427] p-6 lg:p-8 border-b md:border-b-0 md:border-r border-[#333538] flex items-start gap-4">
                        <i class="fas fa-times text-red-500 text-lg mt-1"></i>
                        <p class="text-[16px] text-white/80 font-medium m-0 leading-relaxed">You reach only their marketplace's buyer traffic.</p>
                    </div>
                    <div class="flex-1 bg-[#131416] p-6 lg:p-8 flex items-start gap-4">
                        <i class="fas fa-check text-brand-orange text-lg mt-1"></i>
                        <p class="text-[16px] text-white/90 font-medium m-0 leading-relaxed">You reach every channel your buyers actually use — theirs included.</p>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="flex flex-col md:flex-row border-t border-[#333538]">
                    <div class="flex-1 bg-[#222427] p-6 lg:p-8 border-b md:border-b-0 md:border-r border-[#333538] flex items-start gap-4">
                        <i class="fas fa-times text-red-500 text-lg mt-1"></i>
                        <p class="text-[16px] text-white/80 font-medium m-0 leading-relaxed">Leave the platform and you start over from an empty shell.</p>
                    </div>
                    <div class="flex-1 bg-[#131416] p-6 lg:p-8 flex items-start gap-4">
                        <i class="fas fa-check text-brand-orange text-lg mt-1"></i>
                        <p class="text-[16px] text-white/90 font-medium m-0 leading-relaxed">Leave Bridge and you take every unit, photo, and sold price with you.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="bg-brand-gray py-28 px-6 lg:px-10">
        <div class="max-w-[1000px] mx-auto text-center">
            <div class="text-brand-orange text-[12.5px] font-bold tracking-[1.5px] mb-4 uppercase">Success Stories</div>
            <h2 class="text-4xl lg:text-[42px] font-black tracking-tight mb-12 text-brand-black leading-tight">Real Dealers. Verified Numbers.</h2>
            
            <div class="grid md:grid-cols-2 gap-8 text-left mb-12">
                <!-- Testimonial 1 -->
                <div class="bg-white rounded-2xl p-10 shadow-[0_16px_40px_rgba(0,0,0,0.04)] relative">
                    <i class="fas fa-quote-left text-brand-orange/20 text-4xl absolute top-8 right-8"></i>
                    <p class="text-xl leading-relaxed font-bold text-brand-black mb-8 italic relative z-10">"Inventory shows up correctly everywhere, every time. That alone was worth the switch to their platform."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-brand-black rounded-full flex items-center justify-center text-white font-black">TN</div>
                        <div>
                            <div class="font-black text-[15px] text-brand-black">T. Nakamura</div>
                            <div class="text-[13px] font-medium text-brand-black/50">Blue Ridge Marine Supply <span class="text-brand-orange font-bold ml-1">+180% sales</span></div>
                        </div>
                    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white rounded-2xl p-10 shadow-[0_16px_40px_rgba(0,0,0,0.04)] relative">
                    <i class="fas fa-quote-left text-brand-orange/20 text-4xl absolute top-8 right-8"></i>
                    <p class="text-xl leading-relaxed font-bold text-brand-black mb-8 italic relative z-10">"Our rep doesn't just manage our account — she actively manages our growth. The ROI is clear every month."</p>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-brand-black rounded-full flex items-center justify-center text-white font-black">AR</div>
                        <div>
                            <div class="font-black text-[15px] text-brand-black">A. Reyes</div>
                            <div class="text-[13px] font-medium text-brand-black/50">Cascade Powersports <span class="text-brand-orange font-bold ml-1">+210% leads</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Demo Form Section -->
    <section class="bg-brand-black text-white py-24 px-6 lg:px-10 border-b border-white/10" id="demo">
        <div class="max-w-[1000px] mx-auto flex flex-col md:flex-row items-center justify-between gap-12 text-center md:text-left">
            <div>
                <div class="inline-block bg-brand-orange/20 text-brand-orange text-xs font-bold tracking-widest uppercase py-1.5 px-4 rounded-full mb-4">Interactive Demo Suite</div>
                <h2 class="text-white text-4xl font-black mb-3 tracking-tight">See The Platform In Action.</h2>
                <p class="text-white/60 text-lg font-medium">30 minutes. Explore live inventory ledger, marketplace feeds, and mobile sales tool.</p>
            </div>
            <a href="mailto:demo@hwy559bridge.com?subject=Demo%20Request%20-%20HWY%20559%20Bridge" class="bg-brand-orange text-white font-bold text-[16px] py-4 px-10 rounded-md shadow-lg hover:bg-white hover:text-brand-black hover:-translate-y-1 transition-all duration-300 whitespace-nowrap">
                Request Live Demo <i class="fas fa-arrow-right ml-2 text-xs"></i>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-brand-black text-white pt-20 pb-10 px-6 lg:px-10 relative overflow-hidden">
        <div class="max-w-[1240px] mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12 lg:gap-8 mb-16">
                
                <!-- Brand Column -->
                <div class="lg:col-span-2 pr-0 lg:pr-12">
                    <img src="<?php echo content_url('/plugins/bridge-plugin/assets/logos/hwy559bridge_logo_final.png'); ?>" alt="HWY 559 Bridge" class="h-10 w-auto mb-6 brightness-0 invert" onerror="this.onerror=null; this.src='https://placehold.co/400x100/000000/EF8522?text=HWY+559+BRIDGE&font=oswald'">
                    <p class="text-[15px] leading-relaxed text-white/50 mb-8 font-medium max-w-sm">
                        Website, digital marketing, and inventory systems built exclusively for powersports, marine, trailer, ag, truck, and heavy equipment dealers. Somewhere along Highway 559.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-brand-orange/50 flex items-center justify-center text-brand-orange hover:bg-brand-orange hover:text-white transition-colors"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full border border-brand-orange/50 flex items-center justify-center text-brand-orange hover:bg-brand-orange hover:text-white transition-colors"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full border border-brand-orange/50 flex items-center justify-center text-brand-orange hover:bg-brand-orange hover:text-white transition-colors"><i class="fab fa-facebook-f"></i></a>
                    </div>
                </div>

                <!-- Links 1 -->
                <div>
                    <h4 class="text-[13px] font-black tracking-widest text-white/40 mb-6 uppercase">Solutions</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Website Platform</a></li>
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Digital Marketing</a></li>
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Inventory Syndication</a></li>
                    </ul>
                </div>

                <!-- Links 2 -->
                <div>
                    <h4 class="text-[13px] font-black tracking-widest text-white/40 mb-6 uppercase">Industries</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Powersports</a></li>
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Marine</a></li>
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Trailer & Agriculture</a></li>
                    </ul>
                </div>

                <!-- Links 3 -->
                <div>
                    <h4 class="text-[13px] font-black tracking-widest text-white/40 mb-6 uppercase">Company</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-white/70 hover:text-brand-orange text-[14.5px] font-medium transition-colors">Success Stories</a></li>
                        <li><a href="mailto:hello@hwy559bridge.com" class="text-brand-orange font-bold text-[14.5px] hover:text-white transition-colors">hello@hwy559bridge.com</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="border-t border-white/10 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
                <span class="text-[13.5px] font-medium text-white/40">© 2026 HWY 559 Bridge. All Rights Reserved.</span>
                <div class="flex gap-6">
                    <a href="#" class="text-[13.5px] font-medium text-white/40 hover:text-brand-orange transition-colors">Privacy Policy</a>
                    <a href="#" class="text-[13.5px] font-medium text-white/40 hover:text-brand-orange transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Navbar shrink on scroll
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            if (window.scrollY > 20) {
                navbar.classList.add('shadow-md');
            } else {
                navbar.classList.remove('shadow-md');
            }
        });

        // Mobile Menu Toggle
        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
                mobileMenu.classList.toggle('flex');
            });
        }
    </script>
    <?php wp_footer(); ?>
</body>
</html>
