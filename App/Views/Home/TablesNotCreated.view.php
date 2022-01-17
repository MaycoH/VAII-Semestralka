<?php /** @var \App\Models\Aktualita[] $data */
?>

<div class="container">
    <div class="row">
        <div class="col col-md-8">
            <h3 style="color:red">Všetky tabuľky neboli ešte vytvorené!</h3>
            <?php
            $count = \App\Core\DB\Connection::connect()->prepare("SHOW TABLES");    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
            $count->execute([]);    // Spustím ho
            $count2 = $count->fetchAll();

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
            ?>

        </div>
    </div>
</div>

