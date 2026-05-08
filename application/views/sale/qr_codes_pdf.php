<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR Codes for Sale #<?= $sale->reference_no ?></title>
    <style>
        body { font-family: Arial, sans-serif; }
        .qr-container { text-align: center; margin-bottom: 20px; page-break-inside: avoid; }
        .qr-title { font-weight: bold; margin-bottom: 5px; }
        .qr-code { width: 150px; height: 150px; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>QR Codes for Sale #<?= $sale->reference_no ?></h1>
        <p>Total Boxes: <?= $sale->no_of_boxes ?></p>
    </div>

    <?php foreach ($box_data as $box): ?>
        <div class="qr-container">
            <div class="qr-title">Box <?= $box['box_number'] ?> - Code: <?= $box['random_no'] ?></div>
            <?php
            // Convert image to base64 for PDF rendering
            $image_data = file_get_contents($box['qr_full_path']);
            $base64 = 'data:image/png;base64,' . base64_encode($image_data);
            ?>
            <img class="qr-code" src="<?= $base64 ?>">
        </div>
    <?php endforeach; ?>
</body>
</html>