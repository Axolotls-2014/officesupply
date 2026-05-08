<div class="container">
    <div class="card mt-5">
        <div class="card-header">
            <h3>Box Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Sale Reference:</strong> <?= $sale->reference_no ?></p>
                    <p><strong>Box Number:</strong> <?= $box['box_number'] ?></p>
                    <p><strong>Unique Code:</strong> <?= $box['random_no'] ?></p>
                    <p><strong>Invoice Date:</strong> <?= date('d/m/Y', strtotime($sale->invoice_date)) ?></p>
                </div>
                <!--<div class="col-md-6 text-center">-->
                <!--    <img src="<?= base_url('uploads/qr_codes/boxes/'.$box['qr_filename']) ?>" -->
                <!--         style="width: 200px; height: 200px;">-->
                <!--    <p class="mt-2">Scan this QR to verify</p>-->
                <!--</div>-->
            </div>
        </div>
    </div>
</div>
