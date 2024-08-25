<!DOCTYPE html>
<html lang="en">
    <head>
        <?= $this->include('layout/header') ?>
        <style>
        /* Menambah ketinggian pada bagian gambar */
        .custom-image-height {
            min-height: 600px; /* Sesuaikan tinggi gambar */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .gradient {
            background: linear-gradient(90deg, #d53369 0%, #daae51 100%);
        }
        .gradientback {
            background: linear-gradient(90deg, #daae51 0%, #d53369 100%);
        }
        </style>
    </head>

    <body class="bg-gray-100 flex items-center justify-center min-h-screen">
        <?= $this->renderSection('content') ?>
    </body>

</html>