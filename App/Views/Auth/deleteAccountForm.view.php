<?php /** @var \App\Models\Auth[] $data */ ?>
<div class="container">
    <div class="row">
        <div class="col">
            <form method="POST" action="?c=auth&a=deleteAccount" onsubmit="return (!emptyPass('loginPassInput'))">
                <div class="mb-3">
                    <label for="loginPassInput" class="form-label">Potvrďte zmazanie účtu zadaním hesla:</label>
                    <input type="password" class="form-control" name="password" id="loginPassInput" placeholder="Heslo" onkeyup="emptyPass('loginPassInput')">
                    <div class="invalid-feedback">Nezadali ste heslo!</div>
                </div>
                <?php
                if (isset($data['status'])) {
                    if ($data['status'] == "passwordWrong") { ?>
                        <h3 class="register-error">Zadané heslo je nesprávne!</h3>
                    <?php }
                    if ($data['status'] == "passwordOK") { ?>
                        <h3 class="register-success">Účet bol úspešne vymazaný!</h3>
                    <?php }
                }
                ?>
                    <input type="submit" value="Zmazať účet">
                    <input type="reset" value="Zmazať formulár">
                    <input type="button" value="Vrátiť sa" onclick="history.back()">
            </form>
        </div>
    </div>
</div>
