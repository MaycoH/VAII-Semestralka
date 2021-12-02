<?php ?>
<div class="container">
    <div class="row">
        <div class="col">
            <form method="POST" action="?c=auth&a=login" onsubmit="return (checkLogin() && !emptyPass())">
                <div class="mb-3">
                    <label for="loginNameInput" class="form-label">Meno</label>
                    <input type="text" class="form-control" name="login" id="loginNameInput" placeholder="Prihlasovacie meno" onkeyup="checkLogin()">
                    <div class="valid-feedback">Prihlasovacie meno je OK.</div>
                    <div class="invalid-feedback">Prihlasovacie meno musí mať aspoň 3 znaky!.</div>
                </div>
                <div class="mb-3">
                    <label for="loginPassInput" class="form-label">Heslo</label>
                    <input type="password" class="form-control" name="password" id="loginPassInput" placeholder="Heslo" onkeyup="emptyPass()">
                    <div class="invalid-feedback">Nezadali ste heslo!</div>
                </div>
                <?php
                if (isset($_SESSION["namePassEmpty"])) {
                    if ($_SESSION["namePassEmpty"] != false) {
                        print('<h3 class="register-error">Meno alebo heslo nebolo vyplnené!</h3>');
                    }
                    unset($_SESSION["namePassEmpty"]);
                }
                if (isset($_SESSION["wrongPassword"])) {
                    if ($_SESSION["wrongPassword"] != false) {
                        print('<h3 class="register-error">Zadali ste nesprávne heslo!</h3>');
                    }
                    unset($_SESSION["wrongPassword"]);
                }
                ?>
                    <input type="submit" value="Prihlásiť">
                    <input type="reset" value="Zmazať">
                    <input type="button" value="Vrátiť sa" onclick="history.back()">
            </form>
        </div>
    </div>
</div>
