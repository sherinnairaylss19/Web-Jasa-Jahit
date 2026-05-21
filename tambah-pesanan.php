<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { 
    header("Location: index.php"); 
    exit(); 
}

$id_p_lama = isset($_GET['id_pelanggan']) ? $_GET['id_pelanggan'] : "";
$nama = ""; $no_hp = ""; $alamat = "";

if ($id_p_lama != "") {
    $cek_p = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_p_lama'");
    $data_p = mysqli_fetch_assoc($cek_p);
    if ($data_p) {
        $nama   = $data_p['nama_lengkap'];
        $no_hp  = $data_p['no_hp'];
        $alamat = $data_p['alamat_lengkap'];
    }
}

// Ambil data ukuran dari pesanan sebelumnya jika ada
$data_lama = null;
if (isset($_GET['dari_pesanan'])) {
    $id_ref = (int)$_GET['dari_pesanan'];
    $stmt_lama = mysqli_prepare($koneksi,
        "SELECT lebar_bahu, lingkar_dada, panjang_lengan, panjang_baju,
                lingkar_pinggang, lingkar_pinggul, lingkar_paha,
                jenis_pesanan, catatan
         FROM pesanan WHERE id_pesanan = ?");
    mysqli_stmt_bind_param($stmt_lama, "i", $id_ref);
    mysqli_stmt_execute($stmt_lama);
    $result_lama = mysqli_stmt_get_result($stmt_lama);
    $data_lama = mysqli_fetch_assoc($result_lama);
}

function val_lama($data_lama, $key) {
    return htmlspecialchars($data_lama[$key] ?? '', ENT_QUOTES, 'UTF-8');
}

$jenis_list = ['Kemeja', 'Atasan', 'Celana', 'Celana Jeans', 'Gamis', 'Kebaya', 'Seragam'];
$jenis_db   = $data_lama['jenis_pesanan'] ?? '';
$is_manual  = $jenis_db !== '' && !in_array($jenis_db, $jenis_list);

if (isset($_POST['submit'])) {
    $id_pelanggan = $_POST['id_pelanggan_hidden']; 
    $nama    = $_POST['nama'];        
    $no_hp   = $_POST['no_hp'];        
    $alamat  = $_POST['alamat'];        
    $tgl_m   = $_POST['tgl_masuk'];
    $tgl_t   = $_POST['tgl_tenggat'];
    $total   = $_POST['total'];        
    $jenis   = $_POST['jenis_pesanan'];
    $catatan = $_POST['catatan'];
    $status  = "Proses";

    $l_bahu     = $_POST['lebar_bahu'] ?? '';
    $l_dada     = $_POST['lingkar_dada'] ?? '';
    $p_lengan   = $_POST['panjang_lengan'] ?? '';
    $l_pinggang = $_POST['lingkar_pinggang'] ?? '';
    $l_pinggul  = $_POST['lingkar_pinggul'] ?? '';
    $l_paha     = $_POST['lingkar_paha'] ?? '';

    // Pilih panjang_baju dari field yang sesuai jenis pesanan
    $jenis_celana = ['Celana', 'Celana Jeans'];
    if (in_array($jenis, $jenis_celana)) {
        $p_baju = $_POST['panjang_celana'] ?? '';
    } else {
        $p_baju = $_POST['panjang_baju'] ?? '';
    }

    if (empty($id_pelanggan)) {
        $query_pelanggan = "INSERT INTO pelanggan (nama_lengkap, no_hp, alamat_lengkap) 
                            VALUES ('$nama', '$no_hp', '$alamat')";
        mysqli_query($koneksi, $query_pelanggan);
        $id_pelanggan = mysqli_insert_id($koneksi);
    } else {
        mysqli_query($koneksi, "UPDATE pelanggan SET no_hp='$no_hp', alamat_lengkap='$alamat' WHERE id_pelanggan='$id_pelanggan'");
    }

    $query_pesanan = "INSERT INTO pesanan (id_pelanggan, tgl_masuk, tgl_tenggat, total_biaya, jenis_pesanan, catatan, status_produksi, lebar_bahu, lingkar_dada, panjang_lengan, panjang_baju, lingkar_pinggang, lingkar_pinggul, lingkar_paha) 
                      VALUES ('$id_pelanggan', '$tgl_m', '$tgl_t', '$total', '$jenis', '$catatan', '$status', '$l_bahu', '$l_dada', '$p_lengan', '$p_baju', '$l_pinggang', '$l_pinggul', '$l_paha')";

    if (mysqli_query($koneksi, $query_pesanan)) {
        echo "<script>alert('Data Berhasil Disimpan'); window.location='pesanan.php';</script>";
    } else {
        echo "Error Pesanan: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .alert-info {
            background: #fffbeb;
            border: 1px solid #fcd34d;
            border-radius: 6px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #92400e;
        }
        #manual-jenis-wrap {
            margin-top: 8px;
            display: none;
        }
        #manual-jenis-wrap input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar"><?php include 'sidebar.php'; ?></nav>

        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Tambahkan Pesanan</p>
            </div>

            <div class="form-section">
                <h2 class="form-title"><?= ($id_p_lama != "") ? "Pesanan Ulang: $nama" : "Form Pesanan Baru" ?></h2>

                <?php if ($data_lama): ?>
                    
                <?php endif; ?>

                <form action="" method="POST">
                    <input type="hidden" name="id_pelanggan_hidden" value="<?= $id_p_lama ?>">

                    <div class="form-grid">
                        <!-- Kolom Kiri: Data Pelanggan -->
                        <div class="form-column">
                            <h3>Data Pelanggan</h3>
                            <div class="input-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" value="<?= $nama ?>" required <?= ($id_p_lama != "") ? "readonly style='background:#f0f0f0;'" : "" ?>>
                            </div>
                            <div class="input-group">
                                <label>No Handphone</label>
                                <input type="text" name="no_hp" value="<?= $no_hp ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Alamat Lengkap</label>
                                <input type="text" name="alamat" value="<?= $alamat ?>" required>
                            </div>
                            <div class="input-row">
                                <div class="input-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" value="<?= date('Y-m-d') ?>" required>
                                </div>
                                <div class="input-group">
                                    <label>Tanggal Tenggat</label>
                                    <input type="date" name="tgl_tenggat" required>
                                </div>
                            </div>
                            <div class="input-group">
                                <label>Total Biaya</label>
                                <input type="number" name="total" required>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Detail Jahitan & Ukuran -->
                        <div class="form-column">
                            <h3>Detail Jahitan &amp; Ukuran</h3>

                            <div class="input-group">
                                <label>Jenis Pesanan</label>
                                <select id="jenis_select" style="width:100%; padding:8px;" onchange="handleJenisChange(this.value)">
                                    <option value="">-- Pilih Jenis --</option>
                                    <?php foreach ($jenis_list as $j): ?>
                                        <option value="<?= $j ?>" <?= (!$is_manual && $jenis_db === $j) ? 'selected' : '' ?>>
                                            <?= $j ?>
                                        </option>
                                    <?php endforeach; ?>
                                    <option value="lainnya" <?= $is_manual ? 'selected' : '' ?>>
                                        Lainnya (input manual)
                                    </option>
                                </select>

                                <div id="manual-jenis-wrap" style="<?= $is_manual ? 'display:block;' : 'display:none;' ?>">
                                    <input
                                        type="text"
                                        id="jenis_manual"
                                        placeholder="Ketik jenis pesanan..."
                                        value="<?= $is_manual ? val_lama($data_lama, 'jenis_pesanan') : '' ?>"
                                        oninput="document.getElementById('jenis_pesanan_final').value = this.value.trim()"
                                    >
                                </div>

                                <input type="hidden" name="jenis_pesanan" id="jenis_pesanan_final" value="<?= val_lama($data_lama, 'jenis_pesanan') ?>">
                            </div>

                            <!-- UKURAN ATASAN -->
                            <div id="ukuran_atasan" class="ukuran-group" style="display:none;">
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Lebar Bahu</label>
                                        <input type="text" name="lebar_bahu" value="<?= val_lama($data_lama, 'lebar_bahu') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Lingkar Dada</label>
                                        <input type="text" name="lingkar_dada" value="<?= val_lama($data_lama, 'lingkar_dada') ?>" placeholder="cm">
                                    </div>
                                </div>
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Panjang Lengan</label>
                                        <input type="text" name="panjang_lengan" value="<?= val_lama($data_lama, 'panjang_lengan') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Panjang Baju</label>
                                        <input type="text" name="panjang_baju" value="<?= val_lama($data_lama, 'panjang_baju') ?>" placeholder="cm">
                                    </div>
                                </div>
                            </div>

                            <!-- UKURAN CELANA -->
                            <div id="ukuran_celana" class="ukuran-group" style="display:none;">
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Lingkar Pinggang</label>
                                        <input type="text" name="lingkar_pinggang" value="<?= val_lama($data_lama, 'lingkar_pinggang') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Lingkar Pinggul</label>
                                        <input type="text" name="lingkar_pinggul" value="<?= val_lama($data_lama, 'lingkar_pinggul') ?>" placeholder="cm">
                                    </div>
                                </div>
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Lingkar Paha</label>
                                        <input type="text" name="lingkar_paha" value="<?= val_lama($data_lama, 'lingkar_paha') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Panjang Celana</label>
                                        <input type="text" name="panjang_celana" value="<?= val_lama($data_lama, 'panjang_baju') ?>" placeholder="cm">
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Catatan Tambahan</label>
                                <textarea name="catatan" rows="6"><?= val_lama($data_lama, 'catatan') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-buttons">
                        <button type="submit" name="submit" class="btn-save">Simpan Pesanan</button>
                        <button type="button" class="btn-cancel" onclick="window.location='pesanan.php'">Kembali</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
    const mapping = {
        'Kemeja':       'ukuran_atasan',
        'Atasan':       'ukuran_atasan',
        'Gamis':        'ukuran_atasan',
        'Kebaya':       'ukuran_atasan',
        'Seragam':      'ukuran_atasan',
        'Celana':       'ukuran_celana',
        'Celana Jeans': 'ukuran_celana',
    };

    function tampilkanUkuran(jenis) {
        document.querySelectorAll('.ukuran-group').forEach(el => el.style.display = 'none');
        const target = mapping[jenis];
        if (target) document.getElementById(target).style.display = 'block';
    }

    function handleJenisChange(val) {
        const manualWrap  = document.getElementById('manual-jenis-wrap');
        const finalInput  = document.getElementById('jenis_pesanan_final');
        const manualInput = document.getElementById('jenis_manual');

        if (val === 'lainnya') {
            manualWrap.style.display = 'block';
            manualInput.focus();
            finalInput.value = manualInput.value.trim();
            tampilkanUkuran('');
        } else {
            manualWrap.style.display = 'none';
            finalInput.value = val;
            tampilkanUkuran(val);
        }
    }

    // Inisialisasi saat halaman load — tampilkan ukuran sesuai jenis dari data lama
    (function init() {
        const select     = document.getElementById('jenis_select');
        const finalInput = document.getElementById('jenis_pesanan_final');
        const currentVal = finalInput.value;

        if (select.value === 'lainnya') {
            document.getElementById('manual-jenis-wrap').style.display = 'block';
            tampilkanUkuran('');
        } else {
            tampilkanUkuran(currentVal);
        }
    })();
    </script>
</body>
</html>