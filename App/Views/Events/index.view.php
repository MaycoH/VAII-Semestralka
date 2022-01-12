<?php /** @var \App\Models\Event[] $action */

use App\Config\Configuration; ?>

<div class="container">
    <div class="row">
        <div class="col col-md-8">
            <?php if (\App\Models\Auth::isLogged()) { ?>
            <div class="card mb-3">   <!-- Vytvorí rámec pre jednotlivú položku   -->
                <button type="button" class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#formular">Pridať novú udalosť</button>
                <div class="card-body collapse" id="formular">
                    <div class="mb-3">
                        <label for="startTime" class="form-label">Čas začiatku udalosti:</label>
                        <input name="zaciatok" type="datetime-local" id="startTime" class="form-control" onchange="checkInput(this)">
                        <div class="valid-feedback">Čas začiatku udalosti je OK.</div>
                        <div class="invalid-feedback">Zadaný neplatný čas začiatku udalosti!</div>
                    </div>
                    <div class="mb-3">
                        <label for="endTime" class="form-label">Čas konca udalosti:</label>
                        <input name="koniec" type="datetime-local" id="endTime" class="form-control" onchange="checkInput(this)">
                        <div class="valid-feedback">Čas konca udalosti je OK.</div>
                        <div class="invalid-feedback" id="endError">Zadaný neplatný čas konca udalosti! Nesmie byť starší ako čas začiatku alebo uplynutý.</div>
                    </div>
                    <div class="mb-3">
                        <label for="place" class="form-label">Miesto konania udalosti:</label>
                        <input name="miesto" type="text" id="place" class="form-control" placeholder="Tu zadajte miesto konania udalosti, napr. Žilina" onkeyup="checkInput(this)">
                        <div class="valid-feedback"></div>
                        <div class="invalid-feedback">Miesto je neplatné. Musí obsahovať > 5 a < 255 znakov!</div>
                    </div>
                    <div class="mb-3">
                        <label for="eventDescription" class="form-label">Popis udalosti:</label>
                        <textarea name="popis" id="eventDescription" class="form-control" rows="3" placeholder="Tu zadajte popis udalosti..." onkeyup="checkInput(this)"></textarea>
                        <div class="invalid-feedback">Popis udalosti je krátky! Musí obsahovať viac ako 5 znakov!</div>
                    </div>
                    <div class="mt-3">
                        <button class="btn btn-success" id="btn-odoslat">Odošli</button>
                        <input type="reset" value="Zruš zmeny" class="btn btn-danger ms-4">     <!-- margin-left -->
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="card mb-3">
                <h3 class="card-body">Udalosti: </h3>  <!-- Vytvorí rámec pre jednotlivú položku   -->
                <div id="comments"></div>
            </div>
        </div>
    </div>
</div>
