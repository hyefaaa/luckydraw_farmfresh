<?php
include 'db_config.php';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="ms">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Moo-rah Rezeki | Farm Fresh Terengganu</title>
    
    <link rel="icon" type="image/png" href="logo.png"/>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght=400;600;700;800&family=Inter:wght=400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <style>
        /* 1. DEKLARASI FAIL FONT KUSTOM ANDA */
        @font-face {
            font-family: 'WhYYouGoTtABeSoMeAn';
            src: url('KGWhYYouGoTtABeSoMeAn.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        /* 2. KELAS CSS UNTUK DIGUNAKAN PADA TEKS */
        .font-custom {
            font-family: 'WhYYouGoTtABeSoMeAn', sans-serif !important;
        }

        .soft-shadow { box-shadow: 0 12px 32px -4px rgba(246, 139, 32, 0.08); }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        @keyframes slideInUp {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .komen-baru { animation: slideInUp 0.4s ease forwards; }

        /* Border pelangi beranimasi untuk poster */
        @keyframes borderGlow {
            0%   { border-color: #f68b20; box-shadow: 0 0 18px rgba(246,139,32,0.5); }
            33%  { border-color: #ff4500; box-shadow: 0 0 24px rgba(255,69,0,0.5); }
            66%  { border-color: #ffd700; box-shadow: 0 0 24px rgba(255,215,0,0.5); }
            100% { border-color: #f68b20; box-shadow: 0 0 18px rgba(246,139,32,0.5); }
        }
        .poster-border {
            border: 6px solid #f68b20;
            animation: borderGlow 3s ease-in-out infinite;
            border-radius: 1.2rem;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .poster-border:hover {
            transform: scale(1.01);
        }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                "on-tertiary": "#ffffff", "error": "#ba1a1a",
                "tertiary-fixed": "#e2e2e2", "surface-container-highest": "#e5e2df",
                "on-tertiary-fixed": "#1a1c1c", "outline": "#897363",
                "outline-variant": "#dcc2b0", "tertiary-fixed-dim": "#c6c6c7",
                "on-tertiary-container": "#3a3c3c", "on-secondary": "#ffffff",
                "secondary-fixed-dim": "#c2c8c0", "background": "#fdf9f6",
                "surface-container-low": "#f7f3f0", "on-secondary-fixed-variant": "#424842",
                "on-tertiary-fixed-variant": "#454747", "on-primary-fixed": "#2f1500",
                "inverse-primary": "#ffb77e", "on-surface-variant": "#554336",
                "primary-container": "#f68b20", "error-container": "#ffdad6",
                "on-primary-fixed-variant": "#6e3900", "primary": "#914c00",
                "surface-bright": "#fdf9f6", "primary-fixed": "#ffdcc3",
                "on-secondary-fixed": "#171d18", "surface-dim": "#ddd9d6",
                "secondary-fixed": "#dee4db", "on-error-container": "#93000a",
                "secondary": "#5a6059", "secondary-container": "#dee4db",
                "on-secondary-container": "#60665f", "surface-container": "#f1edea",
                "on-primary-container": "#5e3000", "primary-fixed-dim": "#ffb77e",
                "inverse-surface": "#31302f", "surface-tint": "#914c00",
                "surface-container-lowest": "#ffffff", "tertiary": "#5d5f5f",
                "surface-container-high": "#ebe7e5", "on-background": "#1c1b1a",
                "inverse-on-surface": "#f4f0ed", "surface": "#fdf9f6",
                "surface-variant": "#e5e2df", "tertiary-container": "#a5a6a6",
                "on-primary": "#ffffff", "on-surface": "#1c1b1a", "on-error": "#ffffff"
              },
              "borderRadius": {
                "DEFAULT": "1rem", "lg": "2rem", "xl": "3rem", "full": "9999px"
              },
              "spacing": {
                "gutter": "24px", "margin-mobile": "16px",
                "container-max": "1200px", "base": "8px", "margin-desktop": "40px"
              },
              "fontFamily": {
                "headline-md": ["Plus Jakarta Sans"], "display-lg": ["Plus Jakarta Sans"],
                "headline-lg": ["Plus Jakarta Sans"], "body-md": ["Inter"],
                "label-md": ["Inter"], "display-lg-mobile": ["Plus Jakarta Sans"],
                "body-lg": ["Inter"]
              },
              "fontSize": {
                "headline-md": ["24px", {"lineHeight": "1.3", "fontWeight": "700"}],
                "display-lg": ["48px", {"lineHeight": "1.1", "letterSpacing": "-0.02em", "fontWeight": "800"}],
                "headline-lg": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                "label-md": ["14px", {"lineHeight": "1.2", "letterSpacing": "0.01em", "fontWeight": "600"}],
                "display-lg-mobile": ["36px", {"lineHeight": "1.1", "fontWeight": "800"}],
                "body-lg": ["18px", {"lineHeight": "1.6", "fontWeight": "400"}]
              }
            },
          },
        }
    </script>
</head>
<body class="bg-background text-on-background font-body-md selection:bg-primary-fixed">

<header class="fixed top-0 w-full z-50 flex justify-between items-center px-gutter py-3 max-w-container-max mx-auto shadow-sm bg-surface">
    <div class="flex items-center gap-3">
        <img src="logo.png.png" alt="Logo Farm Fresh" class="h-10 w-auto object-contain">
        <span class="font-bold text-primary inline-block text-[15px] sm:text-[24px]">Farm Fresh Terengganu</span>
    </div>
</header>

<main class="pt-24 pb-20">

<section class="max-w-container-max mx-auto px-gutter mb-20">
    <div class="flex flex-col lg:flex-row gap-12 items-center justify-center w-full">

        <div class="w-full md:w-4/5 lg:w-[42%] flex justify-center">
            <div class="poster-border w-full max-w-[420px] lg:max-w-full shadow-lg">
                <img src="poster.png.png" alt="Poster Cabutan Bertuah Moo-rah Rezeki" class="w-full h-auto block"/>
            </div>
        </div>

 <div class="w-full lg:w-[54%] flex flex-col gap-6 justify-center">

            <div class="bg-surface-container-low p-8 rounded-xl shadow-sm border border-surface-container-high/50">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-fixed text-on-primary-fixed font-label-md mb-5 animate-pulse">
                    <span class="material-symbols-outlined text-[18px]">radio_button_checked</span>
                    SIARAN LANGSUNG AKAN DATANG
                </div>
                <h1 class="font-custom text-[38px] md:text-[48px] mb-4 leading-tight tracking-wide text-[#f68b20] capitalize">
                    saksikan cabutan bertuah farm fresh!
                </h1>
                <p class="font-body-lg text-body-lg text-on-surface-variant mb-6 leading-relaxed">
                    Jangan lepaskan detik kemenangan anda dan hantar borang penyertaan resit anda sekarang.
                </p>
                <a href="daftar.php" class="inline-block bg-primary text-white px-8 py-4 rounded-xl font-bold hover:scale-105 active:scale-95 transition-all shadow-md text-center w-full sm:w-auto">
                    Hantar Resit & Daftar Sekarang
                </a>
            </div>

            <div class="bg-surface-container-low p-6 rounded-xl shadow-sm border border-surface-container-high/50">
                <p class="font-label-md text-on-surface-variant text-center mb-4 uppercase tracking-widest text-[12px] font-bold">
                    ⏳ Masa Berbaki Sebelum Cabutan
                </p>
                <div class="grid grid-cols-4 gap-4 max-w-[550px] mx-auto">
                    <div class="bg-surface-container-lowest soft-shadow rounded-xl p-4 flex flex-col items-center justify-center border border-surface-container">
                        <span class="font-display-lg text-primary text-[36px] md:text-[42px] font-extrabold leading-none mb-1" id="days">00</span>
                        <span class="font-label-md text-on-surface-variant uppercase text-[10px] tracking-wider font-semibold">Hari</span>
                    </div>
                    <div class="bg-surface-container-lowest soft-shadow rounded-xl p-4 flex flex-col items-center justify-center border border-surface-container">
                        <span class="font-display-lg text-primary text-[36px] md:text-[42px] font-extrabold leading-none mb-1" id="hours">00</span>
                        <span class="font-label-md text-on-surface-variant uppercase text-[10px] tracking-wider font-semibold">Jam</span>
                    </div>
                    <div class="bg-surface-container-lowest soft-shadow rounded-xl p-4 flex flex-col items-center justify-center border border-surface-container">
                        <span class="font-display-lg text-primary text-[36px] md:text-[42px] font-extrabold leading-none mb-1" id="minutes">00</span>
                        <span class="font-label-md text-on-surface-variant uppercase text-[10px] tracking-wider font-semibold">Minit</span>
                    </div>
                    <div class="bg-surface-container-lowest soft-shadow rounded-xl p-4 flex flex-col items-center justify-center border border-surface-container">
                        <span class="font-display-lg text-primary text-[36px] md:text-[42px] font-extrabold leading-none mb-1" id="seconds">00</span>
                        <span class="font-label-md text-on-surface-variant uppercase text-[10px] tracking-wider font-semibold">Saat</span>
                    </div>
                </div>
            </div>

            <div class="w-full relative rounded-xl overflow-hidden soft-shadow border-4 border-surface-container bg-black aspect-video">
                <div class="w-full h-full">
                    <video class="w-full h-full object-cover" autoplay loop muted playsinline>
                        <source src="live.mp4" type="video/mp4">
                        Pelayar anda tidak menyokong elemen video.
                    </video>
                </div>
                <div class="absolute inset-0 flex flex-col justify-between p-5 pointer-events-none bg-gradient-to-t from-black/70 via-transparent to-black/40">
                    <div class="flex justify-between items-start w-full">
                        <div class="bg-black/60 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-primary-container flex items-center justify-center shadow-inner">
                                <span class="material-symbols-outlined text-white text-[16px]">person</span>
                            </div>
                            <div class="text-white">
                                <div class="text-[12px] font-bold tracking-wide">@mr_mooooo</div>
                                <div class="text-[10px] opacity-90 font-medium">1.2k Penonton sedang menonton</div>
                            </div>
                        </div>
                        <div class="bg-error px-3 py-1 rounded-md text-white font-bold text-[10px] tracking-widest shadow-md animate-pulse">LIVE</div>
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center group cursor-pointer">
                    <a href="https://www.tiktok.com/@mr_mooooo" target="_blank"
                       class="w-16 h-16 bg-primary-container/95 rounded-full flex items-center justify-center text-white soft-shadow transform group-hover:scale-110 transition-transform pointer-events-auto shadow-xl">
                        <span class="material-symbols-outlined text-[36px] ml-1">play_arrow</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>

<section class="max-w-container-max mx-auto px-gutter mb-20">
    <div class="bg-primary-container rounded-lg p-8 md:p-10 flex flex-col md:flex-row justify-between items-center gap-6 relative overflow-hidden shadow-md">
        <div class="absolute inset-0 opacity-10 pointer-events-none"
             style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="relative z-10 text-center md:text-left">
            <h2 class="font-custom text-[30px] md:text-[38px] text-on-primary mb-2 tracking-wide">
                Ikuti kami di TikTok
            </h2>
            <p class="font-body-lg text-body-lg text-on-primary/90">Dapatkan info terkini dan tonton sesi cabutan secara eksklusif.</p>
        </div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-4 w-full sm:w-auto justify-center">
            <span class="font-display-lg text-white font-extrabold text-[26px] md:text-[32px]">@mr_mooooo</span>
            <a class="bg-white text-primary px-8 py-3.5 rounded-full font-bold hover:bg-surface-container-lowest hover:scale-105 active:scale-95 transition-all soft-shadow inline-block text-center w-full sm:w-auto"
               href="https://www.tiktok.com/@mr_mooooo" target="_blank" rel="noopener noreferrer">
               Follow Sekarang
            </a>
        </div>
    </div>
</section>

<section class="max-w-container-max mx-auto px-gutter">
    <div class="bg-white rounded-lg soft-shadow overflow-hidden flex flex-col h-[500px] border border-surface-container">
        <div class="p-6 border-b border-surface-container bg-surface-container-low/50 flex justify-between items-center">
            <h3 class="font-headline-md text-headline-md flex items-center gap-2 text-on-surface">
                <span class="material-symbols-outlined text-primary">chat_bubble</span>
                Sembang Komuniti
            </h3>
            <div class="flex items-center gap-2">
                <span class="text-[12px] bg-secondary-container text-on-secondary-container px-3 py-1 rounded-full font-bold">LIVE UPDATE</span>
                <span id="jumlahKomen" class="text-[12px] bg-primary-fixed text-on-primary-fixed px-3 py-1 rounded-full font-bold">0 komen</span>
            </div>
        </div>

        <div class="flex-grow p-6 overflow-y-auto space-y-4 bg-white" id="chat-feed">
            <div class="flex items-center justify-center h-full" id="loadingState">
                <div class="text-center text-on-surface-variant">
                    <div class="text-[32px] mb-2">💬</div>
                    <p class="text-[13px]">Memuatkan komen...</p>
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-surface-container bg-surface-container-low">
            <a href="daftar.php" class="flex items-center gap-3 w-full px-5 py-3 rounded-full border border-outline-variant bg-white text-on-surface-variant text-[14px] hover:border-primary hover:bg-primary-fixed/20 transition-all cursor-pointer no-underline shadow-sm">
                <span class="material-symbols-outlined text-primary text-[20px]">edit</span>
                <span>Daftar dulu untuk tinggalkan komen... 🐄</span>
            </a>
        </div>
    </div>
</section>

</main>

<footer class="w-full py-12 px-gutter flex flex-col items-center gap-8 bg-surface-container-high">
    <div class="pt-8 border-t border-outline-variant w-full max-w-container-max text-center">
        <p class="text-[14px] text-on-surface-variant">© 2026 Farm Fresh Terengganu.</p>
    </div>
</footer>

<script>
// COUNTDOWN TIMER
function updateCountdown() {
    const target   = new Date("June 29, 2026 21:00:00").getTime();
    const now      = new Date().getTime();
    const distance = target - now;
    if (distance < 0) {
        ['days','hours','minutes','seconds'].forEach(id => document.getElementById(id).innerText = "00");
        return;
    }
    document.getElementById('days').innerText    = Math.floor(distance / 86400000).toString().padStart(2,'0');
    document.getElementById('hours').innerText   = Math.floor((distance % 86400000) / 3600000).toString().padStart(2,'0');
    document.getElementById('minutes').innerText = Math.floor((distance % 3600000) / 60000).toString().padStart(2,'0');
    document.getElementById('seconds').innerText = Math.floor((distance % 60000) / 1000).toString().padStart(2,'0');
}
setInterval(updateCountdown, 1000);
updateCountdown();

// SEMBANG KOMUNITI — LOAD DARI DATABASE
const chatFeed     = document.getElementById('chat-feed');
const loadingState = document.getElementById('loadingState');
const jumlahKomen  = document.getElementById('jumlahKomen');

const warnaList = [
    'bg-primary-fixed','bg-secondary-fixed','bg-tertiary-fixed',
    'bg-yellow-100','bg-green-100','bg-blue-100','bg-pink-100','bg-purple-100'
];
function getWarna(nama) { return warnaList[nama.charCodeAt(0) % warnaList.length]; }

function buatElemenKomen(item, animasi = false) {
    const div = document.createElement('div');
    div.className = 'flex gap-3' + (animasi ? ' komen-baru' : '');
    div.innerHTML = `
        <div class="w-9 h-9 rounded-full ${getWarna(item.nama)} flex-shrink-0 flex items-center justify-center font-bold text-primary text-[14px]">
            ${item.nama.charAt(0).toUpperCase()}
        </div>
        <div class="bg-surface-container-low p-3 rounded-lg rounded-tl-none flex-grow">
            <div class="flex justify-between items-center mb-1">
                <span class="font-bold text-[13px] text-on-surface">${item.nama}</span>
                <span class="text-[11px] text-on-surface-variant">${item.masa}</span>
            </div>
            <p class="text-[13px] text-on-surface leading-relaxed">${item.mesej}</p>
        </div>`;
    return div;
}

let idKomenTerakhir  = 0;
let sudahMuatPertama = false;

async function muatKomen() {
    try {
        const res  = await fetch('get_komen.php');
        const data = await res.json();

        if (!sudahMuatPertama) {
            loadingState.style.display = 'none';
            if (data.length === 0) {
                chatFeed.innerHTML = `
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center text-on-surface-variant">
                            <div class="text-[32px] mb-2">💬</div>
                            <p class="text-[13px]">Belum ada komen lagi. Jadilah yang pertama!</p>
                        </div>
                    </div>`;
            } else {
                chatFeed.innerHTML = '';
                data.forEach(item => chatFeed.appendChild(buatElemenKomen(item)));
                chatFeed.scrollTop = chatFeed.scrollHeight;
                idKomenTerakhir = data.length;
            }
            jumlahKomen.textContent = data.length + ' komen';
            sudahMuatPertama = true;
        } else {
            if (data.length > idKomenTerakhir) {
                const emptyState = chatFeed.querySelector('.flex.items-center.justify-center');
                if (emptyState) chatFeed.innerHTML = '';
                data.slice(idKomenTerakhir).forEach(item => chatFeed.appendChild(buatElemenKomen(item, true)));
                idKomenTerakhir = data.length;
                chatFeed.scrollTop = chatFeed.scrollHeight;
                jumlahKomen.textContent = data.length + ' komen';
            }
        }
    } catch (err) {
        if (!sudahMuatPertama) {
            loadingState.innerHTML = `
                <div class="text-center text-on-surface-variant">
                    <div class="text-[32px] mb-2">⚠️</div>
                    <p class="text-[13px]">Gagal memuatkan komen.</p>
                </div>`;
            sudahMuatPertama = true;
        }
    }
}

muatKomen();
setInterval(muatKomen, 5000);
</script>
</body>
</html>