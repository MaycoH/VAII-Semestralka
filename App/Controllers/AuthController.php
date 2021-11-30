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
     * @return \App\Core\Responses\ViewResponse     */
    public function loginForm(): \App\Core\Responses\ViewResponse
    {
        return $this->html([]);
    }

    /** Metóda volajúca a vykresľujúca HTML formulár pre registráciu
     * @return \App\Core\Responses\ViewResponse     */
    public function registerForm(): \App\Core\Responses\ViewResponse
    {
        return $this->html([]);
    }

    /** Funkcia slúžiada pre registráciu užívateľa. Ak užívateľ zadal platné údaje a užívateľ so zadaným menom ešte nie je registrovaný,
     * zaregistruje ho, ináč redirect na úvodnú stránku.      */
    public function register()
    {
        //TODO:
        $name = $this->request()->getValue("login");           // Najskôr zistím, či je meno vôbec odoslané
        $password = $this->request()->getValue("password");    // Najskôr zistím, či je heslo vôbec odoslané
        if (!Auth::isRegistered($name)) {           // Ak užívateľ so zadaným menom ešte nie je zaregistrovaný
            Auth::register($name, $password);       // Urobíme registráciu
            return true;
        }
        return false;
//        $this->redirectToHome();                    // Ak sa nepodarilo užívateľa zaregistrovať, redirect
    }

    /** Funkcia pre prihlásenie užívateľa. Ak užívateľ zadal platné údaje, prihlási ho, ináč redirect na úvodnú stránku. */
    public function login()
    {
        //TODO:
        $name = $this->request()->getValue("login");           // Najskôr zistím, či je meno vôbec odoslané
        $password = $this->request()->getValue("password");    // Najskôr zistím, či je heslo vôbec odoslané
        if ($name && $password) {                       // Ak bolo meno a heslo zadané
            Auth::login($name, $password);              // Urobíme prihlásenie
        }
        $this->redirectToHome();                        // Ak aspoň jedno z toho chýba, redirect
    }

    /** Funkcia pre odhlásenie užívateľa */
    public function logout()
    {
        Auth::logout();
        $this->redirectToHome();
    }

    /** Funkcia pre redirect na úvod */
    public function redirectToHome()
    {
        header("Location: ?c=home");    // Namiesto renderovania presmerujeme.
    }
}