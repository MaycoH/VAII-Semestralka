<?php ?>
<div class="container">
    <div class="row">
        <div class="col">
            <form method="POST" action="?c=auth&a=login">
                <div class="mb-3">
                    <label for="loginNameInput" class="form-label">Meno</label>
                    <input type="text" class="form-control" name="login" id="loginNameInput" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label for="loginNamePass" class="form-label">Heslo</label>
                    <input type="password" class="form-control" name="password" id="loginNamePass" placeholder="Heslo">
                </div>
                    <input type="submit" value="Prihlásiť">
                </div>
            </form>
        </div>
    </div>
</div>
