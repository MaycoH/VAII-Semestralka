<?php /** @var Array $data */ ?>
<div class="row">
    <div class="col">
        <form method="POST" enctype="multipart/form-data" action="?c=home&a=editActualityPostBack&postid=<?=$data['postid']?>"> <!-- controller = "Home", akcia = "upload" enctype="multipart/form-data"-->
            <div>
                <div class="mb-3">
                    <label for="nazovClanku" class="form-label">Názov článku</label>
                    <input name="titulok" type="text" id="nazovClanku" class="form-control" value="<?= $data['titulok'] ?>">
                </div>
                <div class="mb-3">
                    <label for="titulnyObrazokClanku" class="form-label">Titulný obrázok článku</label>
                    <input name="subor" class="form-control" id="titulnyObrazokClanku" type="file">                     <!-- form-control-sm -->
                </div>
                <div class="mb-3">
                    <label for="textClanku" class="form-label">Text článku</label>
                    <textarea name="textClanku" id="textClanku" class="form-control" rows="15"><?= $data['textClanku'] ?></textarea>
                </div>
                <input type="submit" value="Uprav článok" class="btn btn-success">
                <input type="reset" value="Zruš zmeny" class="btn btn-danger ms-4">     <!-- margin-left -->
                <button type="button" class="btn btn-warning ms-4" onclick="history.back()">Vrátiť sa</button>
            </div>
        </form>
    </div>
</div>