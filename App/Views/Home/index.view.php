<?php /** @var \App\Models\Aktualita[] $data */

use App\Core\DB\Connection; ?>
<?php
$name = "index";
?>
<div class="container">
    <div class="row">
        <div class="col col-md-8">
            <script>
            </script>
<!--            <script>-->
<!--                function reqListener () {-->
<!--                    console.log(this.responseText);-->
<!--                }-->
<!---->
<!--                var oReq = new XMLHttpRequest(); // New request object-->
<!--                oReq.onload = function() {-->
<!--                    // This is where you handle what to do with the response.-->
<!--                    // The actual data is found on this.responseText-->
<!--                    alert(this.responseText); // Will alert: 42-->
<!--                };-->
<!--                oReq.open("get", "Semestralka/App/Controllers/HomeController.php", true);-->
<!--                //                               ^ Don't block the rest of the execution.-->
<!--                //                                 Don't wait until the request finishes to-->
<!--                //                                 continue.-->
<!--                oReq.send();-->
<!--            </script>-->
            <?php foreach (($data) as $aktualita) { ?>
            <div class="card mb-3">   <!-- style="width: 18rem;" -->
                <div class="card-body">
                   <div class="row">
                    <div class="col col-11"><h1 class="card-title"><?= $aktualita->title ?></h1></div>
                    <?php if (\App\Models\Auth::isLogged()) { ?>
                        <div class="col col-1 my-auto text-end">
                        <a href="?c=home&a=editActuality&postid=<?= $aktualita->id ?>" class="btn btn-warning"> <i class="bi bi-pencil"></i></a>
                        <a href="?c=home&a=removeActuality&postid=<?= $aktualita->id ?>" class="btn btn-danger"> <i class="bi bi-trash"></i></a>
                        </div>

                   </div>
                    <?php } ?>
                    <img src="<?= \App\Config\Configuration::ROOT_DIR."/".\App\Config\Configuration::IMAGES_DIR."/$aktualita->imagePath" ?>" class="img-responsive" alt="..." >
                    <p class="card-text"> <?= $aktualita->text ?> </p>
<!--                    <a href="#" class="btn btn-primary">Go somewhere</a>-->
                </div>
            </div>
            <?php } ?>
            <div class="row" id="bottomButtons">
<!--            <div class="text-end">-->
<!--                <a href="?c=home&a=goNext&offset=<//= (isset($_GET["offset"]) && ($_GET["offset"] >= 2)  ? $_GET["offset"]-=2 : "") ?>--><!--" class="btn btn-warning">Novšie príspevky</a>-->

<!--                --><?php
//                $pocCl = \App\Config\Configuration::POCET_CLANKOV;
//                $count = Connection::connect()->prepare("SELECT * FROM actuality ORDER BY id desc LIMIT $pocCl OFFSET 0");    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
//                $count->execute([]);    // Spustím ho
//                $count2 = $count->fetch(PDO::FETCH_NUM);
//                ?>
                <?php
                isset($_GET["offset"]) ? $offset_old = $_GET["offset"] : $offset_old = 0;
                if (isset($_GET["offset"]) && ($_GET["offset"] > \App\Config\Configuration::POCET_CLANKOV)) {
                    print('<div class="col text-start"><a href="?c=home&a=goNext&offset='.($offset_old-=\App\Config\Configuration::POCET_CLANKOV).'" class="btn btn-warning">Novšie príspevky</a></div>' );
                } elseif (isset($offset_old) && $offset_old == \App\Config\Configuration::POCET_CLANKOV) {
                    print('<div class="col text-start"><a href="?c=home" class="btn btn-warning">Novšie príspevky</a></div>' );
                }

//                if (isset($_GET["offset"]) && ($_GET["offset"] > \App\Config\Configuration::POCET_CLANKOV)) {
//                        print('<div class="text-start"><a href="?c=home&a=goNext&offset='.($_GET["offset"]-=\App\Config\Configuration::POCET_CLANKOV).'" class="btn btn-warning">Novšie príspevky</a></div>' );
//                    } elseif (isset($_GET["offset"]) && $_GET["offset"] == 2) {
//                        print('<a href="?c=home" class="btn btn-warning text-start">Novšie príspevky</a></div>' );
//                    }
                ?>

                <?php
                                $pocCl = \App\Config\Configuration::POCET_CLANKOV;
                                isset($_GET["offset"]) ? $offset_tmp = $_GET["offset"] + $pocCl : $offset_tmp = 0;
                                $count = Connection::connect()->prepare("SELECT * FROM actuality ORDER BY id desc LIMIT $pocCl OFFSET $offset_tmp");    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
                                $count->execute([]);    // Spustím ho
                                $count2 = $count->fetch();

                    if(count($data) > 1 && $count2 != false) {
                    print('<div class="col text-end"><a href="?c=home&a=goNext&offset=');
                        if (isset($_GET["offset"])) {
                            ((print($_GET["offset"] += 2)));
                        } else {
                            ((print($_GET["offset"] = 2)));
                        }

//                        (isset($_GET["offset"]) ? ( print($_GET["offset"]+=\App\Config\Configuration::POCET_CLANKOV) ) : ( print($_GET["offset"]=\App\Config\Configuration::POCET_CLANKOV) ) );
                    print('" class="btn btn-warning">Staršie príspevky</a></div>');
                    }
                ?>
            </div>
<!--            </div>-->
            <!-- TODO: Dopracovať view  - DONE?-->
            <!-- TODO: Spraviť "Zmazanie príspevku (z DB); úpravu príspevku  - DONE?-->
        </div>
    </div>
</div>

<!--●	Termín 2-->
<!--    ○	Implementované všetky CRUD operácie-->
<!--    ○	Kontrola vstupov na strane klienta, aj servera-->
<!--    ○	Netriviálny JavaScript-->

