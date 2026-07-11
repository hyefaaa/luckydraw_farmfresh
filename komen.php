<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tinggalkan Komen | Farm Fresh Terengganu</title>
    <link rel="icon" type="image/x-icon" href="logo.png.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #f68b20;
            --primary-dark: #e05500;
            --bg: #fcf8f5;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --error: #ef4444;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

        .kad {
            background: var(--white);
            width: 100%;
            max-width: 440px;
            border-radius: 28px;
            padding: 38px 32px;
            box-shadow: 0 25px 50px rgba(246, 139, 32, 0.08);
            border-top: 10px solid var(--primary);
            text-align: center;
            animation: fadeInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .logo {
            display: block;
            max-width: 110px;
            height: auto;
            margin: 0 auto 14px auto;
        }

        /* Badge TikTok */
        .tiktok-section {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            border-radius: 16px;
            padding: 16px 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .tiktok-left {
            text-align: left;
        }

        .tiktok-label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            color: rgba(255,255,255,0.6);
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .tiktok-handle {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: var(--primary);
        }

        .btn-tiktok {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--primary);
            color: white;
            padding: 9px 18px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
            display: inline-block;
        }

        .btn-tiktok:hover {
            background: var(--primary-dark);
            transform: scale(1.05);
        }

        /* Divider */
        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 22px;
        }

        .divider hr {
            flex: 1;
            border: none;
            border-top: 1.5px solid #f1f5f9;
        }

        .divider span {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            white-space: nowrap;
        }

        h2 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
            letter-spacing: -0.4px;
        }

        .subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .input-group {
            text-align: left;
            margin-bottom: 16px;
        }

        label {
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: block;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: var(--text-dark);
            margin-bottom: 8px;
        }

        input[type="text"],
        textarea {
            font-family: 'Inter', sans-serif;
            width: 100%;
            padding: 13px 16px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-dark);
            background: #f8fafc;
            transition: all 0.2s ease;
            resize: none;
        }

        input[type="text"]:focus,
        textarea:focus {
            outline: none;
            border-color: var(--primary);
            background: #fff;
            box-shadow: 0 0 0 4px rgba(246, 139, 32, 0.12);
        }

        input::placeholder,
        textarea::placeholder { color: #94a3b8; font-weight: 400; }

        .char-count {
            text-align: right;
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 5px;
        }

        .char-count.warn { color: var(--error); }

        /* Butang-butang */
        .btn-row {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            flex: 1;
            padding: 14px;
            border-radius: 13px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-back {
            background: #f1f5f9;
            color: var(--text-muted);
        }

        .btn-back:hover {
            background: #e2e8f0;
            color: var(--text-dark);
        }

        .btn-send {
            background: linear-gradient(135deg, #f68b20, #e05500);
            color: white;
            box-shadow: 0 6px 18px rgba(246, 139, 32, 0.3);
        }

        .btn-send:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(246, 139, 32, 0.4);
        }

        .btn-send:disabled {
            background: #cbd5e1;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Notif berjaya */
        .notif-berjaya {
            display: none;
            background: #f0fdf4;
            border: 1.5px solid #bbf7d0;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #166534;
            font-weight: 500;
            text-align: left;
        }

        .notif-error {
            display: none;
            background: #fef2f2;
            border: 1.5px solid #fecaca;
            border-radius: 12px;
            padding: 14px 16px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #dc2626;
            font-weight: 500;
            text-align: left;
        }

        .footer-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 28px;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 1.2px;
            font-weight: 700;
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="kad">
    <img src="logo.png.png" alt="Logo Farm Fresh" class="logo">

    <h2>💬 Apa Kata Anda?</h2>
    <p class="subtitle">Nama anda sudah diisi secara automatik. Tinggalkan semangat atau doa anda!</p>

    <div id="notifBerjaya" class="notif-berjaya">✅ Komen anda berjaya dihantar! Terima kasih.</div>
    <div id="notifError" class="notif-error">❌ <span id="errorMsg">Gagal menghantar komen.</span></div>

    <form id="borangKomen">
        <div class="input-group">
            <label>Nama Anda</label>
            <input type="text" id="inputNama" name="nama" placeholder="Nama anda" required readonly
                   style="background: #f1f5f9; color: var(--text-muted);">
        </div>

        <div class="input-group">
            <label>Mesej / Komen</label>
            <textarea id="inputMesej" name="mesej" rows="4" 
                      placeholder="Contoh: Semoga ada rezeki harini! 🤲" 
                      maxlength="300" required></textarea>
            <div class="char-count" id="charCount">0 / 300</div>
        </div>

        <div class="btn-row">
            <a href="index.php" class="btn btn-back">← Kembali</a>
            <button type="submit" class="btn btn-send" id="btnHantar">Hantar Komen 🎉</button>
        </div>
    </form>

    <div class="footer-text">TWIN MATRIX ENTERPRISE | FARM FRESH TERENGGANU</div>
</div>

<script>
    // =============================================
    // ISI NAMA DARI URL PARAMETER
    // =============================================
    const params = new URLSearchParams(window.location.search);
    const namaCustomer = params.get('nama') || '';
    document.getElementById('inputNama').value = namaCustomer;

    // =============================================
    // KIRA AKSARA TEXTAREA
    // =============================================
    const textarea = document.getElementById('inputMesej');
    const charCount = document.getElementById('charCount');

    textarea.addEventListener('input', function () {
        const len = this.value.length;
        charCount.textContent = len + ' / 300';
        charCount.className = len >= 270 ? 'char-count warn' : 'char-count';
    });

    // =============================================
    // HANTAR KOMEN
    // =============================================
    document.getElementById('borangKomen').onsubmit = async function (e) {
        e.preventDefault();
        const btn = document.getElementById('btnHantar');
        const notifBerjaya = document.getElementById('notifBerjaya');
        const notifError   = document.getElementById('notifError');
        const errorMsg     = document.getElementById('errorMsg');

        notifBerjaya.style.display = 'none';
        notifError.style.display   = 'none';

        btn.disabled = true;
        btn.textContent = 'Sedang dihantar...';

        const formData = new FormData();
        formData.append('nama',  document.getElementById('inputNama').value);
        formData.append('mesej', textarea.value);

        try {
            const res  = await fetch('get_komen.php', { method: 'POST', body: formData });
            const data = await res.json();

            if (data.status === 'success') {
                notifBerjaya.style.display = 'block';
                textarea.value = '';
                charCount.textContent = '0 / 300';

                // Redirect ke index.html selepas 2 saat
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 2000);
            } else {
                notifError.style.display = 'block';
                errorMsg.textContent = data.mesej || 'Gagal menghantar komen.';
            }
        } catch (err) {
            notifError.style.display = 'block';
            errorMsg.textContent = 'Gagal menghubungi server. Sila semak sambungan anda.';
        }

        btn.disabled = false;
        btn.textContent = 'Hantar Komen 🎉';
    };
</script>

</body>
</html>
