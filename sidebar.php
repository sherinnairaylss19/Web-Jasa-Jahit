<div class="profile">
    <img src="<?= $_SESSION['foto']; ?>" id="user-foto" alt="User" referrerpolicy="no-referrer">
    <span id="user-nama"><?= $_SESSION['nama']; ?></span>
</div>

<ul>
    <?php if ($_SESSION['role'] === 'pemilik'): ?>
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'dashboard_pemilik.php') ? 'active' : ''; ?>">
            <a href="dashboard_pemilik.php"><i class="fa-solid fa-desktop"></i> Dashboard</a>
        </li>
    <?php else: ?>
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'dashboard_penjahit.php') ? 'active' : ''; ?>">
            <a href="dashboard_penjahit.php"><i class="fa-solid fa-desktop"></i> Dashboard</a>
        </li>
    <?php endif; ?>

    <li class="<?= (basename($_SERVER['PHP_SELF']) == 'pesanan.php') ? 'active' : ''; ?>">
        <a href="pesanan.php"><i class="fa-solid fa-pen-to-square"></i> Pesanan</a>
    </li>

        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'pelanggan.php') ? 'active' : ''; ?>">
            <a href="pelanggan.php"><i class="fa-solid fa-user-group"></i> Pelanggan</a>
        </li>

    <li class="<?= (basename($_SERVER['PHP_SELF']) == 'pembayaran.php') ? 'active' : ''; ?>">
        <a href="pembayaran.php"><i class="fa-solid fa-wallet"></i> Pembayaran</a>
    </li>

    <?php if ($_SESSION['role'] === 'pemilik'): ?>
        <li class="<?= (basename($_SERVER['PHP_SELF']) == 'laporan.php') ? 'active' : ''; ?>">
            <a href="laporan.php"><i class="fa-solid fa-clock"></i> Laporan</a>
        </li>
    <?php endif; ?>

    <li><a href="logout.php" class="nav-link logout-btn"><i class="fa-solid fa-power-off"></i> Logout</a></li>
</ul>