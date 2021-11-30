<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Auth;

class AuthController extends AControllerBase
{

    /** @inheritdoc */
    public function index()
    {
    }


    /** Metóda volajúca a vykresľujúca HTML formulár pre login
     * @return \App\Core\Responses\ViewResponse
     */
    public function loginForm()
    {
        return $this->html([]);
    }

    public function login()
    {
        //TODO:
        $name = $this->request()->getValue("login");           // Najskôr zistím, či je meno vôbec odoslané
        $password = $this->request()->getValue("password");    // Najskôr zistím, či je heslo vôbec odoslané
        if ($name && $password) {
            Auth::login($name, $password);     // Urobíme prihlásenie
        }
        $this->redirectToHome();
    }

    public function logout()
    {
        Auth::logout();
        $this->redirectToHome();
    }

    /** Funkcia pre redirect na úvod */
    public function redirectToHome()
    {
        header("Location: ?c=home");    // Namiesto renderovania presmerujeme.
//        return $this->html([]);     // Aj keď nič nevykresľujem, musím vracať html response, ináč sa mi nič nevykreslí.
    }
}