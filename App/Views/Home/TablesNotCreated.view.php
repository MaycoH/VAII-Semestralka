<?php /** @var \App\Models\Aktualita[] $data */
?>

<div class="container">
    <div class="row">
        <div class="col col-md-8">
            <?php
            try {
                $count = \App\Core\DB\Connection::connect()->prepare("SHOW TABLES");
                $count->execute([]);    // Spustím ho
                $count2 = $count->fetchAll(); ?>
                <h3 class="register-error">Všetky tabuľky neboli ešte vytvorené!</h3>
                <?php
                $tableNames = ["aktuality", "comments", "events", "users"];
                $i = 0;
                foreach ($count2 as $item) {
                    if ($item[0] != $tableNames[$i]) {
                        ?>
                        Tabuľka <strong>"<?php echo $tableNames[$i] ?>"</strong> ešte nebola vytvorená!<br>
                        <?php
                        $i++;
                    } else $i++;
                }
            } catch (Exception $e) {
                if (str_contains($e->getMessage(), '[2002]')) {     // Zlyhalo pripojenie ?>
                    <h3 class="register-error">Problém s pripojením k DB! Je databázový server spustený?</h3>
                <?php } else if (str_contains($e->getMessage(), '[1049]')) {    // Nesprávna databáza ?>
                    <h3 class="register-error">Nesprávna schéma databázy! Skontrolujte, či je zvolená správna databáza.</h3>
                <?php }
            } ?>
        </div>
    </div>
</div>

