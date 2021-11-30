<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Covid informácie</title>
    <link rel="icon" type="image/x-icon" href="<?= \App\Config\Configuration::ROOT_DIR ?>/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?= \App\Config\Configuration::ROOT_DIR ?>/public/script.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= \App\Config\Configuration::ROOT_DIR ?>/public/css.css">


</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-end">
    <div class="container">
        <a class="navbar-brand" href="#">Covid informácie</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="?c=home">Aktuality</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?c=home&a=contact">Kontakt</a>
                </li>

<!--                --><?php //if (\App\Auth::isLogged()) { ?>
                <?php if (!true) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="?c=home&a=contact">Prihlásenie</a>
                </li>
                <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="?c=home&a=addNewActuality">Pridať novú aktualitu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?c=home&a=contact">Odhlásenie</a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="row mt-2">
        <div class="col">
                <?= $contentHTML ?>
        </div>
    </div>
</div>
</body>
</html>

