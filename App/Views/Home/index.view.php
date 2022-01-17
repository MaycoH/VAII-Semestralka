<?php /** @var \App\Models\Aktualita[] $data */
// https://getbootstrap.com/docs/5.1/components/card/#horizontal
use App\Config\Configuration;
use App\Core\DB\Connection;
use App\Models\Auth; ?>
<div class="container">
    <div class="row">
        <div class="col col-md-8">

            <?php foreach (($data) as $aktualita) { ?>
            <div class="card mb-3">   <!-- style="width: 18rem;"   style="max-width: 540px;"   -->
                <div class="row g-0">
                    <div class="col-md-4">
                        <a href="?c=home&a=viewActuality&postid=<?= $aktualita->id ?>">
                            <img src="<?= Configuration::IMAGES_DIR."/$aktualita->imagePath" ?>" class="img-responsive" alt="<?= $aktualita->title ?>" >
                        </a>
                    </div>
                    <div class="col-md-8">
                <div class="card-body">
                    <div class="row">       <!-- div pre titulok a tlačidlá -->
                        <?php if (Auth::isLogged()) { ?>
                        <div class="col col-11">
                        <?php } else { ?>
                            <div class="col col-12">
                        <?php } ?>
                            <a href="?c=home&a=viewActuality&postid=<?= $aktualita->id ?>">
                                <h4 class="card-title"><?= $aktualita->title ?></h4>
                            </a>
                            <div class="Author">Autor: <?= $aktualita->author_id ?> </div>
                            <div class="perex"> <?= $aktualita->perex ?> </div>
                        </div>
                        <?php if (Auth::isLogged() ) { ?>
                            <div class="col col-1 text-end">
                                <?php if (Auth::isModerator()) { ?>
                                <a href="?c=home&a=editActuality&postid=<?= $aktualita->id ?>" class="btn btn-warning"> <i class="bi bi-pencil"></i></a>
                                <?php } ?>
                                <?php if (Auth::isAdmin()) { ?>
                                <a href="?c=home&a=removeActuality&postid=<?= $aktualita->id ?>" class="btn btn-danger"> <i class="bi bi-trash"></i></a>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
                </div>
                </div>
            <?php } ?>
            <div class="row" id="bottomButtons">
                <?php
                    isset($_GET["offset"]) ? $offset_old = $_GET["offset"] : $offset_old = 0;
                    if (isset($_GET["offset"]) && ($_GET["offset"] > Configuration::POCET_CLANKOV)) {
                        print('<div class="col text-start"><a href="?c=home&a=goNext&offset='.($offset_old-= Configuration::POCET_CLANKOV).'" class="btn btn-warning">Novšie príspevky</a></div>' );
                    } elseif (isset($offset_old) && $offset_old == Configuration::POCET_CLANKOV) {
                ?>
                <div class="col text-start"><a href="?c=home" class="btn btn-warning">Novšie príspevky</a></div>
                <?php }
                    $pocCl = Configuration::POCET_CLANKOV;
                    isset($_GET["offset"]) ? $offset_tmp = $_GET["offset"] + $pocCl : $offset_tmp = 0;
                    // Kontrola, či existujú staršie články na výpis
                    $count = Connection::connect()->prepare("SELECT * FROM actuality ORDER BY id desc LIMIT $pocCl OFFSET $offset_tmp");    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
                    $count->execute([]);    // Spustím ho
                    $count2 = $count->fetch();

                    if(count($data) > Configuration::POCET_CLANKOV - 1 && $count2 != false) {
                    print('<div class="col text-end"><a href="?c=home&a=goNext&offset=');
                        if (isset($_GET["offset"])) {
                            ((print($_GET["offset"] += Configuration::POCET_CLANKOV)));
                        } else {
                            ((print($_GET["offset"] = Configuration::POCET_CLANKOV)));
                        }
                    print('" class="btn btn-warning">Staršie príspevky</a></div>');
                    }
                ?>
            </div>
        </div>
    </div>
</div>

