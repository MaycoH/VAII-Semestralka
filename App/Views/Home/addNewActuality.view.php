<?php /** @var Array $data */ ?>
<div class="row">
    <div class="col">
        <form method="POST" enctype="multipart/form-data" action="?c=home&a=upload"> <!-- controller = "Home", akcia = "upload" enctype="multipart/form-data"-->
            <div>
                <div class="mb-3">
                    <label for="nazovClanku" class="form-label">Názov článku</label>
                    <input name="titulok" type="text" id="nazovClanku" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="perex" class="form-label">Perex</label>
                    <input name="perex" type="text" id="perex" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="titulnyObrazokClanku" class="form-label">Titulný obrázok článku</label>
                    <input name="subor" class="form-control" id="titulnyObrazokClanku" type="file">                     <!-- form-control-sm -->
                </div>
                <div class="mb-3">
                    <label for="textClanku" class="form-label">Text článku</label>
                    <textarea name="textClanku" id="textClanku" class="form-control" rows="15"></textarea>
                </div>
                    <input type="submit" value="Pridaj článok" class="btn btn-success">
                    <input type="reset" value="Zruš zmeny" class="btn btn-danger ms-4">     <!-- margin-left -->
                    <button type="button" class="btn btn-warning ms-4" onclick="history.back()">Vrátiť sa</button>
            </div>
        </form>
    </div>
</div>