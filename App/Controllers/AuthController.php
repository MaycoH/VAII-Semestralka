<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\ViewResponse;
use App\Models\Auth;

/**
 * Trieda AuthController – Trieda slúži na obsluhu prihlásenia a registrácie.
 * @package App\Controllers
 */
class AuthController extends AControllerBase
{
    /** @inheritdoc */
    public function index() { }


    /** Metóda volajúca a vykresľujúca HTML formulár pre login
     * @return ViewResponse     */
    public function loginForm()
    {
        return $this->html([]);
    }

    /** Metóda volajúca a vykresľujúca HTML formulár pre registráciu
     * @return ViewResponse     */
    public function registerForm()
    {
        return $this->html([]);
    }

    public function changePassForm()
    {
        return$this->html();
    }

    /** Funkcia slúžiaca pre registráciu užívateľa. Ak užívateľ zadal platné údaje a užívateľ so zadaným menom ešte nie je registrovaný,
     * zaregistruje ho, ináč redirect na úvodnú stránku.      */
    public function register()
    {
        $name = $this->request()->getValue("login");           // Najskôr zistím, či je meno vôbec odoslané
        $password = $this->request()->getValue("password");    // Najskôr zistím, či je heslo vôbec odoslané
        $passwordAgain = $this->request()->getValue("passwordAgain");
        if (!empty($name) && !empty($password) && !empty($passwordAgain)) {
            if ($password >= 8 && $password < 255 && ($password === $passwordAgain)) {
                if (!Auth::isRegistered($name)) {           // Ak užívateľ so zadaným menom ešte nie je zaregistrovaný
                    Auth::register($name, $password);       // Urobíme registráciu
                    $_SESSION["alreadyRegistered"] = false;
                } else {
                    $_SESSION["alreadyRegistered"] = true;
                }
            } else {
                $_SESSION["passwordsNotSame"] = true;
            }
            } else {
            $_SESSION["namePassEmpty"] = true;
        }
        header("Location: ?c=auth&a=registerForm");    // Namiesto renderovania presmerujeme.
    }

    /** Funkcia pre prihlásenie užívateľa. Ak užívateľ zadal platné údaje, prihlási ho, ináč redirect na úvodnú stránku. */
    public function login()
    {
        $login = $this->request()->getValue("login");           // Najskôr zistím, či je meno vôbec odoslané
        $password = $this->request()->getValue("password");     // Najskôr zistím, či je heslo vôbec odoslané
        if ($login && $password) {                                   // Ak bolo meno a heslo zadané
            if (Auth::login($login, $password)) {                    // Urobíme prihlásenie
                $this->redirectToHome();
                return;
            } else {
                $_SESSION["wrongPassword"] = true;
            }
        } else {
            $_SESSION["namePassEmpty"] = true;
        }
        header("Location: ?c=auth&a=loginForm");    // Namiesto renderovania presmerujeme.
    }

    public function changePassword()
    {
        $oldPassword = $this->request()->getValue("oldPassword");               // Najskôr zistím, či je heslo vôbec odoslané
        $newPassword = $this->request()->getValue("password");               // Najskôr zistím, či je heslo vôbec odoslané
        $newPasswordAgain = $this->request()->getValue("passwordAgain");     // Najskôr zistím, či je heslo vôbec odoslané
        if (!empty($oldPassword) && !empty($newPassword) && !empty($newPasswordAgain)) {      // Skontrolujeme, či sú všetky polia vyplnené
            if ($newPassword >= 8 && $newPassword < 255 && ($newPassword === $newPasswordAgain)) {  // Skontrolujeme, či heslo spĺňa dĺžku a či nové heslá sú rovnaké
                if ($oldPassword !== $newPassword) {
                    if (Auth::changePassword($oldPassword, $newPassword)) {
                        return $this->html(['status' => "passChangedOK"],'changePassForm');
                    } else {
                        return $this->html(['status' => "oldPassWrong"],'changePassForm');
                    }
                } else {
                    return $this->html(['status' => "newPassSameAsOld"],'changePassForm');
                }
            } else {
                return $this->html(['status' => "newPasswordsNotSame"],'changePassForm');
            }
        } else {
            return $this->html(['status' => "passEmpty"],'changePassForm');
        }
    }

    /** Funkcia pre odhlásenie užívateľa */
    public function logout()
    {
        if (Auth::isLogged()) {
            Auth::logout();
        }
        $this->redirectToHome();
    }

    /** Funkcia pre redirect na úvod */
    private function redirectToHome()
    {
        header("Location: ?c=home");    // Namiesto renderovania presmerujeme.
    }
}