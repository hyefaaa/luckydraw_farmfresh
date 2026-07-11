<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cabutan Bertuah | Farm Fresh Terengganu</title>
	
    <link rel="icon" type="image/x-icon" href="logo.png.png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #f68b20;
            --primary-hover: #e05500;
            --bg-color: #fcf8f5;
            --white: #ffffff;
            --text-dark: #1e293b;
            --text-muted: #64748b;
            --error-color: #ef4444;
        }

        body { 
            font-family: 'Inter', sans-serif; 
            background-color: var(--bg-color); 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0;
            padding: 20px;
            -webkit-font-smoothing: antialiased;
        }

        .borang-kad { 
            background: var(--white); 
            padding: 45px 35px; 
            border-radius: 28px; 
            box-shadow: 0 20px 40px rgba(255, 102, 0, 0.05), 0 1px 3px rgba(0,0,0,0.02); 
            width: 100%; 
            max-width: 420px; 
            text-align: center;
            border-top: 10px solid var(--primary-color);
        }

        .header img {
            display: block;
            max-width: 160px;
            height: auto;
            margin: 0 auto 15px auto;
        }

        .header h2 { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 5px 0;
            font-size: 26px;
            color: var(--text-dark);
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .header p {
            color: var(--text-muted);
            font-size: 14px;
            margin-bottom: 35px;
            font-weight: 400;
        }

        .input-group {
            margin-bottom: 22px;
            text-align: left;
        }

        label { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: block;
            font-size: 12px; 
            font-weight: 700; 
            color: var(--text-dark); 
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.7px;
        }

        input { 
            font-family: 'Inter', sans-serif;
            width: 100%; 
            padding: 14px 16px; 
            border: 1.5px solid #e2e8f0; 
            border-radius: 12px; 
            box-sizing: border-box; 
            font-size: 14px;
            background-color: #f8fafc;
            transition: all 0.2s ease;
            color: var(--text-dark);
            font-weight: 500;
        }

        input:focus {
            outline: none;
            border-color: var(--primary-color);
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(255, 102, 0, 0.12);
        }

        input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        input[type="file"] {
            padding: 12px;
            background: #fff;
            border: 1.5px dashed #cbd5e1;
            cursor: pointer;
            font-size: 13px;
        }

        /* Hanya butang submit borang sahaja */
        #butangHantar { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            width: 100%; 
            padding: 16px; 
            background-color: var(--primary-color); 
            color: white; 
            border: none; 
            border-radius: 14px; 
            font-weight: 700; 
            font-size: 16px;
            cursor: pointer; 
            margin-top: 15px; 
            transition: all 0.2s ease;
            box-shadow: 0 6px 20px rgba(255, 102, 0, 0.2);
            letter-spacing: 0.5px;
        }

        #butangHantar:hover { 
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255, 102, 0, 0.3);
        }

        #butangHantar:disabled {
            background-color: #cbd5e1;
            cursor: not-allowed;
            transform: none !important;
            box-shadow: none !important;
        }

        /* MODAL */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6); 
            backdrop-filter: blur(6px);         
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.show {
            opacity: 1;
            pointer-events: auto;
        }

        .modal-box {
            background: #fff;
            padding: 35px 28px;
            border-radius: 28px;
            max-width: 400px;
            width: 90%;
            text-align: center;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            transform: scale(0.8);
            transition: transform 0.3s ease;
            border-bottom: 8px solid var(--primary-color); 
        }

        .modal-overlay.show .modal-box {
            transform: scale(1);
        }

        .modal-icon-container {
            width: 74px;
            height: 74px;
            margin: 0 auto 16px auto;
            display: flex;
            justify-content: center;
            align-items: center;
            border-radius: 50%;
            font-size: 38px;
        }

        .modal-box.success-theme { border-bottom-color: var(--primary-color); }
        .success-theme .modal-icon-container { background-color: rgba(255, 102, 0, 0.1); }

        .modal-box.error-theme { border-bottom-color: var(--error-color); }
        .error-theme .modal-icon-container { background-color: rgba(239, 68, 68, 0.1); }

        .modal-box h3 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0 0 10px 0;
            color: var(--text-dark);
            font-size: 22px;
            font-weight: 700;
        }

        .modal-box p {
            font-family: 'Inter', sans-serif;
            color: #475569;
            font-size: 13.5px;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        /* DUA BUTANG DALAM MODAL */
        .modal-btn-row {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 4px;
        }

        .modal-btn {
            font-family: 'Plus Jakarta Sans', sans-serif;
            border: none;
            padding: 14px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }

        .modal-btn-komen {
            background: linear-gradient(135deg, #f68b20, #e05500);
            color: white;
            box-shadow: 0 4px 14px rgba(246, 139, 32, 0.3);
        }

        .modal-btn-komen:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(246, 139, 32, 0.4);
        }

        .modal-btn-back {
            background: #f1f5f9;
            color: var(--text-muted);
        }

        .modal-btn-back:hover {
            background: #e2e8f0;
            color: var(--text-dark);
        }

        /* Butang tutup untuk modal error */
        .modal-btn-tutup {
            background: var(--text-dark);
            color: white;
        }

        .modal-btn-tutup:hover {
            background: #000;
            transform: translateY(-1px);
        }

        .footer-text {
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin-top: 40px;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 1.2px;
            font-weight: 700;
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div class="borang-kad">
    <div class="header">
        <img src="logo.png.png" alt="Logo Farm Fresh">
        <h2>Pendaftaran Cabutan Bertuah</h2>
        <p>Lengkapkan butiran pembelian anda</p>
    </div>

    <form id="borangDaftar" enctype="multipart/form-data">
        <div class="input-group">
            <label>Nama Penuh</label>
            <input type="text" id="inputNama" name="name" placeholder="Seperti dalam IC" required>
        </div>
        
        <div class="input-group">
            <label>Nombor Telefon</label>
            <input type="tel" name="phone" placeholder="Contoh: 0112345678" required>
        </div>

        <div class="input-group">
            <label>Nombor IC</label>
            <input type="text" id="ic_input" name="ic_no" placeholder="Contoh: 950102-11-5344" maxlength="14" required>
        </div>

        <div class="input-group">
            <label>Nombor Resit</label>
            <input type="text" id="receipt_input" name="receipt_no" placeholder="Contoh: #3-19244" maxlength="9" required>
        </div>
        
        <div class="input-group">
            <label>Muat Naik Resit (Min RM32)</label>
            <input type="file" name="receipt" accept="image/*" required>
        </div>
        
        <button type="submit" id="butangHantar">HANTAR SEKARANG</button>
    </form>
    
    <div class="footer-text">TWIN MATRIX ENTERPRISE | FARM FRESH TERENGGANU</div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="popupModal">
    <div class="modal-box" id="modalBox">
        <div class="modal-icon-container" id="modalIcon">🎉</div>
        <h3 id="modalTitle">Pendaftaran Berjaya!</h3>
        <p id="modalMessage"></p>
        <div class="modal-btn-row" id="modalBtnRow"></div>
    </div>
</div>

<script>
    // AUTO-FORMAT IC
    document.getElementById('ic_input').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length > 6 && value.length <= 8) {
            value = value.slice(0, 6) + '-' + value.slice(6);
        } else if (value.length > 8) {
            value = value.slice(0, 6) + '-' + value.slice(6, 8) + '-' + value.slice(8, 12);
        }
        e.target.value = value;
    });
	
    // AUTO-FORMAT RESIT
    document.getElementById('receipt_input').addEventListener('input', function (e) {
        let value = e.target.value.replace(/\D/g, ''); 
        if (value.length > 0 && value.length <= 1) {
            value = '#' + value;
        } else if (value.length > 1) {
            value = '#' + value.slice(0, 1) + '-' + value.slice(1);
        }
        e.target.value = value;
    });

    // KAWALAN MODAL
    const modal     = document.getElementById('popupModal');
    const modalBox  = document.getElementById('modalBox');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle   = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const modalBtnRow  = document.getElementById('modalBtnRow');

    // Simpan nama customer untuk dihantar ke komen.php
    let namaCustomer = '';

    function bukaModal(status, mesej) {
        modalBox.className = "modal-box";
        modalBtnRow.innerHTML = ''; // kosongkan butang lama

        if (status === 'success') {
            modalBox.classList.add('success-theme');
            modalIcon.innerText = "🎉";
            modalTitle.innerText = "Pendaftaran Berjaya!";

            modalMessage.innerHTML = 
                "Terima kasih kerana menyokong Farm Fresh Terengganu. Maklumat anda telah selamat disimpan. Semoga rezeki dan tuah memihak kepada anda!<br><br>" +
                "<div style='margin-bottom:8px; font-size:13px;'>Jangan lupa follow TikTok kami:</div>" +
                "<div style='white-space:nowrap; font-size:22px; font-weight:800; margin:10px 0;'>" +
                "👉 <a href='https://www.tiktok.com/@mr_mooooo' target='_blank' style='color:var(--primary-color); text-decoration:underline;'>@mr_mooooo</a> 👈" +
                "</div>" +
                "<div style='font-size:12px; color:var(--text-muted); font-weight:500; margin-bottom:4px;'>untuk mengetahui maklumat lanjut tentang live ini! 🐄✨</div>";

            // Butang 1: Tinggalkan Komen
            const btnKomen = document.createElement('a');
            btnKomen.href = 'komen.php?nama=' + encodeURIComponent(namaCustomer);
            btnKomen.className = 'modal-btn modal-btn-komen';
            btnKomen.innerHTML = '💬 Tinggalkan Komen';
            modalBtnRow.appendChild(btnKomen);

            // Butang 2: Kembali ke Utama
            const btnBack = document.createElement('a');
            btnBack.href = 'index.php';
            btnBack.className = 'modal-btn modal-btn-back';
            btnBack.innerHTML = '← Kembali ke Utama';
            modalBtnRow.appendChild(btnBack);

        } else {
            modalBox.classList.add('error-theme');
            modalIcon.innerText = "❌";
            modalTitle.innerText = "Pendaftaran Gagal";
            modalMessage.innerText = mesej;

            // Butang tutup untuk error
            const btnTutup = document.createElement('button');
            btnTutup.className = 'modal-btn modal-btn-tutup';
            btnTutup.innerHTML = 'Tutup';
            btnTutup.onclick = tutupModal;
            modalBtnRow.appendChild(btnTutup);
        }

        modal.classList.add('show');
    }

    function tutupModal() {
        modal.classList.remove('show');
    }

    // HANTAR BORANG
    document.getElementById('borangDaftar').onsubmit = async (e) => {
        e.preventDefault();
        const btn = document.getElementById('butangHantar');

        // Simpan nama customer sebelum reset borang
        namaCustomer = document.getElementById('inputNama').value;

        btn.disabled = true;
        btn.innerText = "Sedang dihantar...";

        const formData = new FormData(e.target);
        
        try {
            const response = await fetch('process.php', {
                method: 'POST',
                body: formData
            });
            const result = await response.text();

            if (result.trim() === 'success') {
                bukaModal('success', '');
                e.target.reset();
            } else {
                bukaModal('error', result);
            }
        } catch (error) {
            bukaModal('error', 'Gagal menghubungi server. Sila semak sambungan anda.');
        }

        btn.disabled = false;
        btn.innerText = "HANTAR SEKARANG";
    };
</script>

</body>
</html>
