<?php ?>
<?php /** @var \App\Models\Auth $data */ ?>
<div class="container">
    <div class="row">
        <div class="col">
            <form method="POST" action="?c=auth&a=register" onsubmit="return (checkLogin() && checkPasswords() ? true : false)">
                <div class="mb-3">
                    <label for="loginNameInput" class="form-label">Meno</label>
                    <input type="text" class="form-control" name="login" id="loginNameInput" placeholder="Prihlasovacie meno"onkeyup="checkLogin()">
                    <div class="valid-feedback">Prihlasovacie meno je OK.</div>
                    <div class="invalid-feedback">Prihlasovacie meno musí mať aspoň 3 znaky!.</div>
                </div>
                <div class="mb-3">
                    <label for="loginPassInput" class="form-label">Heslo</label>
                    <input type="password" class="form-control" name="password" id="loginPassInput" placeholder="Heslo" onkeyup="checkPasswords()">
                    <div class="valid-feedback">Heslo je OK.</div>
                    <div class="invalid-feedback">Heslo musí obsahovať aspoň 8 znakov!</div>
                </div>
                <div class="mb-3">
                    <label for="loginPassAgainInput" class="form-label">Zopakujte zadané heslo znovu:</label>
                    <input type="password" class="form-control" name="passwordAgain" id="loginPassAgainInput" placeholder="Zopakované heslo" onkeyup="checkPasswords()">
                    <div class="valid-feedback">Heslá sa zhodujú.</div>
                    <div class="invalid-feedback">Heslá sa nezhodujú!</div>
                </div>
                <?php
                if (isset($_SESSION["alreadyRegistered"])) {
                    if ($_SESSION["alreadyRegistered"] != false) { ?>
                        <h3 class="register-error">Užívateľ so zadaným menom už je zaregistrovaný! Zadajte iné meno.</h3>
                    <?php } else { ?>
                        <h3 class="register-success">Užívateľ bol úspešne zaregistrovaný.</h3>
                    <?php }
                    unset($_SESSION["alreadyRegistered"]);
                }
                if (isset($_SESSION["passwordsNotSame"])) {
                    if ($_SESSION["passwordsNotSame"] != false) { ?>
                        <h3 class="register-error">Heslá sa nezhodujú!</h3>
                    <?php }
                    unset($_SESSION["passwordsNotSame"]);
                }
                if (isset($_SESSION["namePassEmpty"])) {
                    if ($_SESSION["namePassEmpty"] != false) { ?>
                        <h3 class="register-error">Meno alebo heslo nebolo vyplnené.</h3>
                    <?php }
                    unset($_SESSION["namePassEmpty"]);
                }
                ?>
                    <input type="submit" value="Registrácia">
                    <input type="reset" value="Zmazať">
                    <input type="button" value="Vrátiť sa" onclick="redirectToHome()">
            </form>
        </div>
    </div>
</div>
