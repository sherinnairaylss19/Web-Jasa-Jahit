<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) { header("Location: index.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: pesanan.php"); exit(); }

$id_pesanan = (int)$_GET['id'];

$stmt = mysqli_prepare($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.no_hp, pelanggan.alamat_lengkap 
                                   FROM pesanan 
                                   JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                   WHERE pesanan.id_pesanan = ?");
mysqli_stmt_bind_param($stmt, "i", $id_pesanan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) { header("Location: pesanan.php"); exit(); }

$pesan_sukses = '';
$pesan_error  = '';

if (isset($_POST['update'])) {
    $nama     = mysqli_real_escape_string($koneksi, trim($_POST['nama']));
    $no_hp    = mysqli_real_escape_string($koneksi, trim($_POST['no_hp']));
    $alamat   = mysqli_real_escape_string($koneksi, trim($_POST['alamat']));
    $tgl_m    = mysqli_real_escape_string($koneksi, $_POST['tgl_masuk']);
    $tgl_t    = mysqli_real_escape_string($koneksi, $_POST['tgl_tenggat']);
    $total    = (float)$_POST['total'];
    $jenis    = mysqli_real_escape_string($koneksi, trim($_POST['jenis_pesanan']));
    $catatan  = mysqli_real_escape_string($koneksi, trim($_POST['catatan']));
    $status   = mysqli_real_escape_string($koneksi, $_POST['status_produksi']);
    $l_bahu   = mysqli_real_escape_string($koneksi, trim($_POST['lebar_bahu'] ?? ''));
    $l_dada   = mysqli_real_escape_string($koneksi, trim($_POST['lingkar_dada'] ?? ''));
    $p_lengan = mysqli_real_escape_string($koneksi, trim($_POST['panjang_lengan'] ?? ''));
    $p_baju   = mysqli_real_escape_string($koneksi, trim($_POST['panjang_baju'] ?? ''));

    $q1 = mysqli_query($koneksi, "UPDATE pelanggan 
                                   SET nama_lengkap = '$nama', no_hp = '$no_hp', alamat_lengkap = '$alamat' 
                                   WHERE id_pelanggan = '".$data['id_pelanggan']."'");

    $q2 = mysqli_query($koneksi, "UPDATE pesanan 
                                   SET tgl_masuk       = '$tgl_m',
                                       tgl_tenggat     = '$tgl_t',
                                       total_biaya     = '$total',
                                       jenis_pesanan   = '$jenis',
                                       catatan         = '$catatan',
                                       status_produksi = '$status',
                                       lebar_bahu      = '$l_bahu',
                                       lingkar_dada    = '$l_dada',
                                       panjang_lengan  = '$p_lengan',
                                       panjang_baju    = '$p_baju'
                                   WHERE id_pesanan = '$id_pesanan'");

    if ($q1 && $q2) {
        $stmt2 = mysqli_prepare($koneksi, "SELECT pesanan.*, pelanggan.nama_lengkap, pelanggan.no_hp, pelanggan.alamat_lengkap 
                                            FROM pesanan 
                                            JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                            WHERE pesanan.id_pesanan = ?");
        mysqli_stmt_bind_param($stmt2, "i", $id_pesanan);
        mysqli_stmt_execute($stmt2);
        $result2 = mysqli_stmt_get_result($stmt2);
        $data = mysqli_fetch_assoc($result2);
        $pesan_sukses = 'Data pesanan berhasil diperbarui!';
    } else {
        $pesan_error = 'Gagal memperbarui data. Silakan coba lagi.';
    }
}

$jenis_list = ['Kemeja', 'Atasan', 'Celana', 'Celana Jeans', 'Gamis', 'Kebaya', 'Seragam'];
$jenis_db   = $data['jenis_pesanan'] ?? '';
$is_manual  = $jenis_db !== '' && !in_array($jenis_db, $jenis_list);

function val($data, $key) {
    return htmlspecialchars($data[$key] ?? '', ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pesanan - Jasa Jahit</title>
    <link rel="stylesheet" href="css/pesanan.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .alert { padding: 10px 16px; border-radius: 6px; margin-bottom: 16px; font-size: 14px; }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* Wrapper input manual */
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
        #manual-jenis-wrap .hint {
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <nav class="sidebar"><?php include 'sidebar.php'; ?></nav>
        <div class="main-content">
            <div class="header-breadcrumb">
                <p><a href="pesanan.php"><i class="fa-solid fa-chevron-left"></i> Kembali Pesanan</a> / Edit Pesanan</p>
            </div>
            <div class="form-section">
                <h2 class="form-title">Edit Data Pesanan</h2>

                <?php if ($pesan_sukses): ?>
                    <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> <?= $pesan_sukses ?></div>
                <?php endif; ?>
                <?php if ($pesan_error): ?>
                    <div class="alert alert-error"><i class="fa-solid fa-circle-xmark"></i> <?= $pesan_error ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="form-grid">

                        <!-- Kolom Kiri: Data Pelanggan -->
                        <div class="form-column">
                            <h3>Data Pelanggan</h3>

                            <div class="input-group">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" value="<?= val($data, 'nama_lengkap') ?>" required>
                            </div>
                            <div class="input-group">
                                <label>No Handphone</label>
                                <input type="text" name="no_hp" value="<?= val($data, 'no_hp') ?>" required>
                            </div>
                            <div class="input-group">
                                <label>Alamat Lengkap</label>
                                <input type="text" name="alamat" value="<?= val($data, 'alamat_lengkap') ?>" required>
                            </div>

                            <div class="input-row">
                                <div class="input-group">
                                    <label>Tanggal Masuk</label>
                                    <input type="date" name="tgl_masuk" value="<?= val($data, 'tgl_masuk') ?>" required>
                                </div>
                                <div class="input-group">
                                    <label>Tanggal Tenggat</label>
                                    <input type="date" name="tgl_tenggat" value="<?= val($data, 'tgl_tenggat') ?>" required>
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Total Biaya</label>
                                <input type="number" name="total" value="<?= val($data, 'total_biaya') ?>" required>
                            </div>

                            <div class="input-group">
                                <label>Status Produksi</label>
                                <select name="status_produksi" style="width:100%; padding:8px;">
                                    <?php
                                    $status_list = ['Proses', 'Selesai', 'Telat', 'Siap Diambil'];
                                    foreach ($status_list as $s):
                                        $selected = ($data['status_produksi'] === $s) ? 'selected' : '';
                                    ?>
                                        <option value="<?= $s ?>" <?= $selected ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

        
                        <div class="form-column">
                            <h3>Detail Jahitan &amp; Ukuran</h3>

                            <div class="input-group">
                                <label>Jenis Pesanan</label>

                                <!-- Dropdown pilihan jenis -->
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

                                <!-- Input manual — muncul jika pilih "Lainnya" -->
                                <div id="manual-jenis-wrap" style="<?= $is_manual ? 'display:block;' : 'display:none;' ?>">
                                    <input
                                        type="text"
                                        id="jenis_manual"
                                        placeholder="Ketik jenis pesanan..."
                                        value="<?= $is_manual ? val($data, 'jenis_pesanan') : '' ?>"
                                        oninput="document.getElementById('jenis_pesanan_final').value = this.value.trim()"
                                    >
                        
                                </div>

                               
                                <input
                                    type="hidden"
                                    name="jenis_pesanan"
                                    id="jenis_pesanan_final"
                                    value="<?= val($data, 'jenis_pesanan') ?>"
                                >
                            </div>

                            <!-- UKURAN ATASAN -->
                            <div id="ukuran_atasan" class="ukuran-group" style="display:none;">
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Lebar Bahu</label>
                                        <input type="text" name="lebar_bahu" value="<?= val($data,'lebar_bahu') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Lingkar Dada</label>
                                        <input type="text" name="lingkar_dada" value="<?= val($data,'lingkar_dada') ?>" placeholder="cm">
                                    </div>
                                </div>
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Panjang Lengan</label>
                                        <input type="text" name="panjang_lengan" value="<?= val($data,'panjang_lengan') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Panjang Baju</label>
                                        <input type="text" name="panjang_baju" value="<?= val($data,'panjang_baju') ?>" placeholder="cm">
                                    </div>
                                </div>
                            </div>

                            <!-- UKURAN CELANA -->
                            <div id="ukuran_celana" class="ukuran-group" style="display:none;">
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Lingkar Pinggang</label>
                                        <input type="text" name="lingkar_pinggang" value="<?= val($data,'lingkar_pinggang') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Lingkar Pinggul</label>
                                        <input type="text" name="lingkar_pinggul" value="<?= val($data,'lingkar_pinggul') ?>" placeholder="cm">
                                    </div>
                                </div>
                                <div class="input-row">
                                    <div class="input-group">
                                        <label>Lingkar Paha</label>
                                        <input type="text" name="lingkar_paha" value="<?= val($data,'lingkar_paha') ?>" placeholder="cm">
                                    </div>
                                    <div class="input-group">
                                        <label>Panjang Celana</label>
                                        <input type="text" name="panjang_baju" value="<?= val($data,'panjang_baju') ?>" placeholder="cm">
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label>Catatan Tambahan</label>
                                <textarea name="catatan" rows="5"><?= val($data,'catatan') ?></textarea>
                            </div>
                        </div>

                    </div><!-- end .form-grid -->

                    <div class="form-buttons">
                        <button type="submit" name="update" class="btn-save">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                        </button>
                        <a href="hapus_pesanan.php?id=<?= $id_pesanan ?>"
                           class="btn-delete"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus pesanan ini? Semua data pesanan ini akan hilang.')">
                            <i class="fa-solid fa-trash"></i> Hapus Pesanan
                        </a>
                        <button type="button" class="btn-cancel" onclick="window.location='pesanan.php'">Batal</button>
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
        const manualWrap = document.getElementById('manual-jenis-wrap');
        const finalInput = document.getElementById('jenis_pesanan_final');
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

    (function init() {
        const select = document.getElementById('jenis_select');
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