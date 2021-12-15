<?php /** @var \App\Models\Auth[] $data */ ?>
<div class="container">
    <div class="row">
        <div class="col">
            <form method="POST" action="?c=auth&a=changePassword" onsubmit="return (checkPasswords() && !emptyPass('loginOldPassInput') ? true : false)">
                <div class="mb-3">
                    <label for="loginOldPassInput" class="form-label">Pôvodné heslo</label>
                    <input type="oldPpassword" class="form-control" name="oldPassword" id="loginOldPassInput" placeholder="Pôvodné heslo" onkeyup="emptyPass('loginOldPassInput')">
                    <div class="invalid-feedback">Nezadali ste heslo!</div>
                </div>
                <div class="mb-3">
                    <label for="loginPassInput" class="form-label">Nové heslo</label>
                    <input type="password" class="form-control" name="password" id="loginPassInput" placeholder="nové heslo" onkeyup="checkPasswords()">
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
                if (isset($data['status'])) {
                    if ($data['status'] == "newPasswordsNotSame") { ?>
                        <h3 class="register-error">Nové heslo a zopakované heslo sa nezhoduje!</h3>
                    <?php }
                    if ($data['status'] == "passEmpty") { ?>
                        <h3 class="register-error">Niektoré z hesiel nebolo vyplnené!</h3>
                    <?php }
                    if ($data['status'] == "newPassSameAsOld") { ?>
                        <h3 class="register-error">Nové heslo je rovnaké ako pôvodné heslo!</h3>
                    <?php }
                    if ($data['status'] == "oldPassWrong") { ?>
                        <h3 class="register-error">Pôvodné heslo je nesprávne!</h3>
                    <?php }
                    if ($data['status'] == "passChangedOK") { ?>
                        <h3 class="register-success">Heslo bolo úspešne zmenené!</h3>
                    <?php }
                }
                ?>
                    <input type="submit" value="Zmeniť heslo">
                    <input type="reset" value="Zmazať">
                    <input type="button" value="Vrátiť sa" onclick="history.back()">
            </form>
        </div>
    </div>
</div>
