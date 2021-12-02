<?php

use App\Config\Configuration;
use App\Models\Auth;
$stranka = "Aktuality";
if (isset($_GET["c"])) {            // Ak je zvolený Controller
    if (isset($_GET["a"])) {        // Ak je zvolená aj Akcia
        if($_GET["c"] == "home" && $_GET["a"] == "contact")
            $stranka = "Kontakt";
        if($_GET["c"] == "home" && $_GET["a"] == "addNewActuality")
            $stranka = "Pridať novú aktualitu";
        if($_GET["c"] == "home" && $_GET["a"] == "editActuality")
            $stranka = "Upraviť aktualitu";
        if($_GET["c"] == "auth" && $_GET["a"] == "loginForm")
            $stranka = "Prihlásenie";
        if($_GET["c"] == "auth" && $_GET["a"] == "registerForm")
            $stranka = "Registrácia";
    }
}
?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Covid informácie - <?= $stranka ?></title>
    <link rel="icon" type="image/x-icon" href="<?= Configuration::ROOT_DIR ?>/public/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="<?= Configuration::ROOT_DIR ?>/public/script.js"></script>
    <link rel="stylesheet" href="<?= Configuration::ROOT_DIR ?>/public/css.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.0/font/bootstrap-icons.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark justify-content-end">
    <div class="container">
        <a class="navbar-brand" href="#">Covid informácie  - <?= $stranka ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="collapsibleNavbar">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link <?php $stranka == "Aktuality" ? print("active") : "" ?>" href="?c=home">Aktuality</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php $stranka == "Kontakt" ? print("active") : "" ?>" href="?c=home&a=contact">Kontakt</a>
                </li>

                <?php if (!Auth::isLogged()) { ?>
                    <li class="nav-item">
                        <a class="nav-link <?php $stranka == "Prihlásenie" ? print("active") : "" ?>" href="?c=auth&a=loginForm">Prihlásenie</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php $stranka == "Registrácia" ? print("active") : "" ?>" href="?c=auth&a=registerForm">Registrácia</a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $stranka == "Pridať novú aktualitu" ? print("active") : "" ?> " href="?c=home&a=addNewActuality">Pridať novú aktualitu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?c=auth&a=logout">Odhlásenie</a>
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

