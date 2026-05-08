<?php
function format_url($url) {
    if (!preg_match("/^https?:\/\//", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
?>

<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= base_url('assets/admin/') ?>assets/images/favicon-32x32.png" type="image/png">
    <title>All Demos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
    .demo-img {
        height: 250px;
        object-fit: cover;
        border-radius: 0.375rem 0.375rem 0 0;
    }
    .demo-card {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .demo-card:hover {
        transform: scale(1.03);
    }
    .no-results {
        text-align: center;
        font-size: 1.2rem;
        font-weight: bold;
        color: gray;
        display: none;
    }

    @media (max-width: 576px) {
        .demo-card {
            max-width: 90%;
            margin: 0 auto;
        }
        .demo-img {
            height: 180px;
        }
    }
    </style>
</head>

<body>
    <main class="container-fluid py-4">
        <div class="d-flex justify-content-between mb-3">
            <input type="text" id="searchInput" class="form-control w-50" placeholder="Search by project name">
            <select id="typeFilter" class="form-select w-25">
                <option value="">All Types</option>
                <?php foreach ($types as $type): ?>
                    <option value="<?= htmlspecialchars($type->id) ?>"><?= htmlspecialchars($type->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

<div class="row" id="demoContainer">
    <?php foreach ($demos as $demo): ?>
        <div class="col-6 col-sm-4 col-md-3 col-lg-3 mb-4 demo-card"
             data-type="<?= htmlspecialchars($demo->type) ?>" 
             data-name="<?= htmlspecialchars(strtolower($demo->project)) ?>">
            <a class="card h-100 shadow-sm text-decoration-none" href="<?= base_url('demo/info/'.$demo->id) ?>" target="_blank">
                <img src="<?= base_url('uploads/demo/' . $demo->attachment) ?>" class="card-img-top demo-img" alt="<?= htmlspecialchars($demo->project) ?>">
                <div class="card-body text-center">
                    <h6 class="card-title mb-1"> <?= htmlspecialchars($demo->project) ?> </h6>
                    <span class="badge bg-success"> <?= $this->db_model->select('name', 'categories', array('id' => $demo->type)); ?> </span>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>
        <div class="no-results">No Demos Found</div>
    </main>

    <script>
        $(document).ready(function() {
            function filterDemos() {
                let search = $('#searchInput').val().toLowerCase();
                let type   = $('#typeFilter').val().toString(); 
                let count  = 0;

                $('.demo-card').each(function() {
                    let name = $(this).data('name') || ''; 
                    let demoType = $(this).data('type') || '';  

                    if (typeof demoType !== 'string') {
                        demoType = demoType.toString();  
                    }

                    let match = name.includes(search) && (type === '' || demoType === type);
                    $(this).toggle(match);
                    if (match) count++;
                });

                $('.no-results').toggle(count === 0);
            }

            $('#searchInput, #typeFilter').on('input change', filterDemos);
            filterDemos();  
        });
    </script>
</body>
</html>
