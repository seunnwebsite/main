<?php
include 'config/config.php';
// Ensure config is loaded
if (!isset($link)) {$link = mysqli_connect("localhost", "root", "", "trading_db"); 
}
?>
<!DOCTYPE html>
<html lang="en" class="dark scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($sitename ?? 'TradingPlatform'); ?> | Market Analytics & Charting</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,es,fr,de,zh-CN,ar,hi,pt,ru,ja,ko,it,nl,tr',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false
            }, 'google_translate_element');
        }
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

    <style>
        .glass-card { background: rgba(17, 24, 39, 0.7); backdrop-filter: blur(12px); border: 1px solid rgba(30, 41, 59, 0.5); }
        .market-card {
            background: rgba(11, 18, 32, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 24px;
            padding: 24px;
            transition: 0.3s;
            overflow: hidden;
            position: relative;
        }
        .market-card:hover {
            transform: translateY(-4px);
            border-color: rgba(99, 102, 241, 0.4);
            box-shadow: 0 15px 35px rgba(99, 102, 241, 0.1);
        }
        #google_translate_element { display: flex; align-items: center; }
        .goog-te-gadget-simple { 
            background-color: transparent !important; 
            border: 1px solid #334155 !important; 
            border-radius: 9999px !important; 
            padding: 6px 14px !important; 
            display: flex !important; 
            align-items: center !important; 
        }
        .goog-te-gadget-icon, .goog-te-gadget-simple img { display: none !important; }
        .goog-te-gadget-simple span { color: #94a3b8 !important; font-size: 13px !important; }
    </style>
</head>
<body class="bg-[#02040a] text-gray-200 antialiased">

<!-- MARKET BAR -->
<div class="bg-[#010308] border-b border-white/5 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between h-10">
            <div class="hidden lg:flex items-center gap-6 text-xs">
                <span class="flex items-center gap-2 text-indigo-400 font-medium">
                    <span class="w-2 h-2 bg-indigo-500 rounded-full"></span>
                    Live Feeds
                </span>
                <span class="text-gray-500">Forex • Crypto • Commodities • Indices</span>
            </div>
            <div class="flex-1 overflow-hidden mx-4">
                <div id="market-ticker" class="whitespace-nowrap text-xs font-medium text-gray-400">
                    Loading live market feed...
                </div>
            </div>
            <div class="hidden xl:flex items-center gap-5 text-xs text-gray-500">
                <span>Real-Time Data Feed</span>
            </div>
        </div>
    </div>
</div>

<!-- NAVIGATION -->
<nav class="sticky top-0 z-50 bg-[#02040a]/85 backdrop-blur-2xl border-b border-white/5">
    <div class="max-w-7xl mx-auto px-5">
        <div class="h-20 flex items-center justify-between">
            <a href="index.php" class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-r from-indigo-600 to-cyan-500 flex items-center justify-center font-bold text-white shadow-lg">
                    <?php echo strtoupper(substr($sitename ?? 'T', 0, 1)); ?>
                </div>
                <div>
                    <h2 class="text-white text-xl font-bold tracking-tight">
                        <?php echo htmlspecialchars($sitename ?? 'TradingPlatform'); ?>
                    </h2>
                </div>
            </a>

            <div class="hidden lg:flex items-center gap-8">
                <a href="#markets" class="text-sm font-medium text-gray-300 hover:text-white transition">Markets</a>
                <a href="#features" class="text-sm font-medium text-gray-300 hover:text-white transition">Features</a>
                <a href="#tools" class="text-sm font-medium text-gray-300 hover:text-white transition">Terminal</a>
                <a href="#faq" class="text-sm font-medium text-gray-300 hover:text-white transition">FAQ</a>
                <div id="google_translate_element" class="hidden md:flex"></div>
            </div>

            <div class="flex items-center gap-4">
                <a href="auth/login.php" class="hidden md:flex px-4 py-2 text-sm font-medium text-gray-300 hover:text-white transition">
                    Sign In
                </a>
                <a href="auth/register.php" class="px-5 py-2.5 rounded-xl font-medium text-sm text-white bg-indigo-600 hover:bg-indigo-500 transition shadow-lg shadow-indigo-600/20">
                    Create Account
                </a>
                <button id="mobileMenuBtn" class="lg:hidden text-white text-xl">
                    <i class="fa-solid fa-bars"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- MOBILE MENU -->
    <div id="mobileMenu" class="hidden lg:hidden border-t border-white/5 bg-[#050913] px-6 py-6 flex flex-col gap-4">
        <a href="#markets" class="text-gray-300">Markets</a>
        <a href="#features" class="text-gray-300">Features</a>
        <a href="#tools" class="text-gray-300">Terminal</a>
        <a href="#faq" class="text-gray-300">FAQ</a>
        <div class="border-t border-white/5 pt-4 flex flex-col gap-3">
            <a href="auth/login.php" class="w-full text-center py-2.5 rounded-xl border border-white/10 text-white">Sign In</a>
            <a href="auth/register.php" class="w-full text-center py-2.5 rounded-xl bg-indigo-600 text-white font-medium">Create Account</a>
        </div>
    </div>
</nav>

<!-- HERO SECTION -->
<section class="relative overflow-hidden py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 relative z-10">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
            <div>
                <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full border border-indigo-500/20 bg-indigo-500/10 text-indigo-300 text-xs font-medium mb-6">
                    Professional Analytics Suite
                </span>
                <h1 class="text-4xl md:text-6xl font-bold leading-tight text-white mb-6">
                    Multi-Asset Market Analytics & Charting
                </h1>
                <p class="text-lg text-gray-400 leading-relaxed max-w-xl mb-8">
                    Monitor market pricing, analyze technical indicators, and manage your asset portfolio across major global exchanges in real time.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="auth/register.php" class="px-7 py-3.5 bg-indigo-600 hover:bg-indigo-500 rounded-xl font-semibold text-white transition shadow-lg shadow-indigo-500/20">
                        Get Started
                    </a>
                    <a href="#markets" class="px-7 py-3.5 border border-gray-700 hover:border-gray-500 rounded-xl font-semibold text-white transition">
                        View Markets
                    </a>
                </div>
            </div>

            <!-- TradingView Chart Container -->
            <div class="bg-[#0b1220] border border-white/10 rounded-2xl overflow-hidden shadow-2xl p-4">
                <div class="h-[400px] w-full rounded-xl overflow-hidden">
                    <div class="tradingview-widget-container" style="height:100%; width:100%;">
                        <div id="tradingview_chart" style="height:100%; width:100%;"></div>
                        <script type="text/javascript" src="https://s3.tradingview.com/tv.js"></script>
                        <script type="text/javascript">
                        new TradingView.widget({
                            "autosize": true,
                            "symbol": "BINANCE:BTCUSDT",
                            "interval": "D",
                            "timezone": "Etc/UTC",
                            "theme": "dark",
                            "style": "1",
                            "locale": "en",
                            "toolbar_bg": "#0b1220",
                            "enable_publishing": false,
                            "container_id": "tradingview_chart"
                        });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- LIVE MARKETS -->
<section id="markets" class="py-20 bg-[#040812]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="mb-12">
            <span class="text-indigo-400 font-medium uppercase tracking-wider text-xs">Instruments</span>
            <h2 class="text-3xl md:text-4xl font-bold text-white mt-2">Tracked Assets</h2>
            <p class="text-gray-400 mt-2 max-w-xl text-sm">Aggregated pricing data across major digital and traditional asset classes.</p>
        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="market-card">
                <p class="text-xs text-gray-500">Bitcoin</p>
                <h4 class="font-bold text-white text-lg">BTC/USD</h4>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xl font-bold text-white">Live Data</span>
                    <span class="text-xs text-indigo-400 font-medium">Crypto</span>
                </div>
            </div>

            <div class="market-card">
                <p class="text-xs text-gray-500">Ethereum</p>
                <h4 class="font-bold text-white text-lg">ETH/USD</h4>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xl font-bold text-white">Live Data</span>
                    <span class="text-xs text-indigo-400 font-medium">Crypto</span>
                </div>
            </div>

            <div class="market-card">
                <p class="text-xs text-gray-500">Spot Gold</p>
                <h4 class="font-bold text-white text-lg">XAU/USD</h4>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xl font-bold text-white">Live Data</span>
                    <span class="text-xs text-indigo-400 font-medium">Commodity</span>
                </div>
            </div>

            <div class="market-card">
                <p class="text-xs text-gray-500">Euro / US Dollar</p>
                <h4 class="font-bold text-white text-lg">EUR/USD</h4>
                <div class="mt-4 flex items-center justify-between">
                    <span class="text-xl font-bold text-white">Live Data</span>
                    <span class="text-xs text-indigo-400 font-medium">Forex</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section id="features" class="py-24 bg-[#02040a]">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center max-w-2xl mx-auto mb-16">
            <h2 class="text-3xl md:text-5xl font-bold text-white">Platform Capabilities</h2>
            <p class="mt-4 text-gray-400 text-sm">Engineered for clean portfolio observation, indicator analysis, and low latency.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <div class="p-8 rounded-2xl border border-white/5 bg-[#0b1220]/60">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-6 text-indigo-400">
                    <i class="fa-solid fa-chart-line text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Technical Charting</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Integrated technical charting tools with flexible indicators, drawing overlays, and timeframe controls.</p>
            </div>

            <div class="p-8 rounded-2xl border border-white/5 bg-[#0b1220]/60">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-6 text-indigo-400">
                    <i class="fa-solid fa-shield-halved text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Account Security</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Two-factor authentication, secure session handling, and role-based permissions protect user data.</p>
            </div>

            <div class="p-8 rounded-2xl border border-white/5 bg-[#0b1220]/60">
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 flex items-center justify-center mb-6 text-indigo-400">
                    <i class="fa-solid fa-bell text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Price Alerts</h3>
                <p class="text-gray-400 text-sm leading-relaxed">Configurable price thresholds and notifications to stay aware of key market shifts.</p>
            </div>
        </div>
    </div>
</section>

<!-- FAQ -->
<section id="faq" class="py-20 bg-[#040812]">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-white">Frequently Asked Questions</h2>
        </div>

        <div class="space-y-4">
            <div class="bg-[#0b1220]/80 border border-white/5 rounded-2xl p-6">
                <h4 class="font-bold text-white text-base">How do I create an account?</h4>
                <p class="text-gray-400 text-sm mt-2">Click on 'Create Account', enter your basic details, and set up your dashboard credentials.</p>
            </div>
            <div class="bg-[#0b1220]/80 border border-white/5 rounded-2xl p-6">
                <h4 class="font-bold text-white text-base">Where does the market data come from?</h4>
                <p class="text-gray-400 text-sm mt-2">Market data is streamed directly via external public APIs and standardized TradingView widgets.</p>
            </div>
            <div class="bg-[#0b1220]/80 border border-white/5 rounded-2xl p-6">
                <h4 class="font-bold text-white text-base">Can I access the interface on mobile?</h4>
                <p class="text-gray-400 text-sm mt-2">Yes, the entire layout is responsive and accessible from any standard mobile or desktop web browser.</p>
            </div>
        </div>
    </div>
</section>

<!-- FOOTER -->
<footer class="bg-[#02040a] pt-12 pb-8 border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid md:grid-cols-4 gap-8 mb-8 text-sm">
            <div>
                <h3 class="font-bold text-white mb-3"><?php echo htmlspecialchars($sitename ?? 'TradingPlatform'); ?></h3>
                <p class="text-gray-500">Market observation and portfolio charting portal.</p>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Links</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="auth/login.php" class="hover:text-white transition">Sign In</a></li>
                    <li><a href="auth/register.php" class="hover:text-white transition">Register</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Legal</h4>
                <ul class="space-y-2 text-gray-400">
                    <li><a href="main/privacy.php" class="hover:text-white transition">Privacy Policy</a></li>
                    <li><a href="main/terms.php" class="hover:text-white transition">Terms of Service</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold text-white mb-3">Support</h4>
                <p class="text-gray-400"><?php echo htmlspecialchars($site_email ?? 'support@example.com'); ?></p>
            </div>
        </div>

        <div class="border-t border-white/5 pt-6 text-center text-xs text-gray-500">
            &copy; <?php echo date("Y"); ?> <?php echo htmlspecialchars($sitename ?? 'TradingPlatform'); ?>. All rights reserved. Market data is provided for informational and analytical purposes only.
        </div>
    </div>
</footer>

<script>
    // Live Ticker Feed
    async function updateTicker() {
        const ticker = document.getElementById('market-ticker');
        try {
            const res = await fetch('https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&per_page=8');
            const data = await res.json();
            ticker.innerHTML = data.map(coin => `<span>${coin.symbol.toUpperCase()}: $${coin.current_price.toLocaleString()}</span>`).join(' &nbsp; | &nbsp; ');
        } catch (e) { 
            ticker.innerHTML = "BTC: $108,421 | ETH: $5,232 | SOL: $215 | XRP: $1.84"; 
        }
    }
    updateTicker();
    setInterval(updateTicker, 60000);

    // Mobile Menu Toggle
    const btn = document.getElementById('mobileMenuBtn');
    const menu = document.getElementById('mobileMenu');
    btn.addEventListener('click', () => {
        menu.classList.toggle('hidden');
    });
</script>

</body>
</html>