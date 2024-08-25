<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Kelola keuangan pribadi Anda dengan mudah dan efisien menggunakan Rulfa Saving. Aplikasi ini menawarkan fitur lengkap untuk pencatatan pengeluaran, perencanaan anggaran, pemantauan investasi, dan analisis keuangan dengan keamanan data yang terjamin.">
    <meta name="keywords"
        content="Sistem Manajemen Keuangan Pribadi, Aplikasi Keuangan Pribadi, Pengelolaan Keuangan, Pencatatan Pengeluaran, Perencanaan Anggaran, Pemantauan Investasi, Keuangan Pribadi, Aplikasi Anggaran, Analisis Keuangan, Manajemen Keuangan, Aplikasi Finansial">
    <meta name="author" content="Rulfa Dev">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags (Untuk Sosial Media Sharing) -->
    <meta property="og:title" content="<?= $title ?>">
    <meta property="og:description"
        content="Kelola keuangan pribadi Anda dengan mudah dan efisien menggunakan Rulfa Saving.">
    <meta property="og:image" content="#">
    <meta property="og:url" content="<?= base_url(); ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $title ?>">
    <meta name="twitter:description"
        content="Kelola keuangan pribadi Anda dengan mudah dan efisien menggunakan Rulfa Saving.">
    <meta name="twitter:image" content="#">
    <meta name="twitter:site" content="@syhrl.xyz">

    <title><?= $title ?></title>

    <!-- Link Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon/favicon.ico'); ?>" />
    <link rel="shortcut icon" type="image/x-icon" href="<?= base_url('assets/images/favicon/favicon.ico'); ?>" />

    <!-- Include CSS, JS Files -->
    <link rel="stylesheet" href="<?= base_url('assets/css/tailwind.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/boxicons.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/aos.css'); ?>">

    <?php
    // Mendapatkan nama controller saat ini
    $currentController = service('router')->controllerName();

    // Cek apakah ini adalah halaman dashboard
    if ($currentController === '\App\Controllers\Admin'): ?>
        <!-- Libs CSS -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link rel="stylesheet"
            href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" />
        <link rel="stylesheet" href="<?= base_url('assets/libs/simplebar/dist/simplebar.min.css'); ?>">

        <!-- Theme CSS -->
        <link rel="stylesheet" href="<?= base_url('assets/css/theme.min.css'); ?>">
        <link rel="stylesheet" href="<?= base_url('assets/libs/apexcharts/dist/apexcharts.css'); ?>">
    <?php endif; ?>
</head>