<?php /** @var \App\Models\Aktualita[] $data */

use App\Config\Configuration;
use App\Models\Auth; ?>

<div class="container">
    <div class="row">
        <div class="col col-md-8">
            <?php foreach (($data) as $aktualita) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="row">
                        <div class="col col-11"><h1 class="card-title"><?= $aktualita->title ?></h1></div>
                        <?php if (Auth::isLogged()) { ?>
                            <div class="col col-1 my-auto text-end">
                                <a href="?c=home&a=editActuality&postid=<?= $aktualita->id ?>" class="btn btn-warning"> <i class="bi bi-pencil"></i></a>
                                <a href="?c=home&a=removeActuality&postid=<?= $aktualita->id ?>" class="btn btn-danger"> <i class="bi bi-trash"></i></a>
                            </div>
                        <?php } ?>
                        <h5 id="perex"> <?= $aktualita->perex ?> </h5>
                    </div>
                    <img src="<?= Configuration::IMAGES_DIR."/$aktualita->imagePath" ?>" class="img-responsive" alt="..." >
                    <p class="card-text"> <?= $aktualita->text ?> </p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h3> Komentáre k článku </h3>
                    <?php if (Auth::isLogged()) { ?>
                    <div>
                        <input id="comment-text" type="text" placeholder="Zadaj text komentára...">
                        <button id="btn-odoslat">Odoslať komentár</button>
                    </div>
                    <?php } ?>
                    <div id="comments"></div>       <!-- TU budú komentáre -->
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>