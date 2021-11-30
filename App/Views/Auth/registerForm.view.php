<?php ?>
<?php /** @var \App\Models\Auth $data */ ?>

<div class="container">
    <div class="row">
        <div class="col">
            <form method="POST" action="?c=auth&a=register">
                <div class="mb-3">
                    <label for="loginNameInput" class="form-label">Meno</label>
                    <input type="text" class="form-control" name="login" id="loginNameInput" placeholder="name@example.com">
                </div>
                <div class="mb-3">
                    <label for="loginNamePass" class="form-label">Heslo</label>
                    <input type="password" class="form-control" name="password" id="loginNamePass" placeholder="Heslo">
                </div>
                    <input type="submit" value="Registrácia">
                    <input type="reset" value="Zmazať">
                </div>
            </form>
        </div>
    </div>
</div>
