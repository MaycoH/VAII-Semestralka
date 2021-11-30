<?php

namespace App\Models;

/** Trieda má za úlohu povedať, či je používateľ prihlásený a ak áno, tak pod akým menom */
class Auth
{
    public static function login($name, $password)
    {
        if ($name == 'fero' && $password == "test")
        $_SESSION['name'] = $name;

    }

    /** Funkcia pre odhlásenie */
    public static function logout()
    {
        unset($_SESSION['name']);
    }

    /** Funkcia, ktorá informuje či je používateľ prihlásený
     * @return bool true, ak je používateľ prihlásený, ináč false
     */
    public static function isLogged(): bool
    {
        return isset($_SESSION['name']);
    }

    /** Funkcia, ktorá vráti meno aktuálne prihláseného používateľa
     * @return mixed|string meno zo session ak je používateľ prihlásený, ináč vráti "Neprihlásený" */
    public static function getLoggedName()
    {
//        return ($this->isLogged() ? $_SESSION['name'] : "Neprihlásený");    // Ak je používateľ prihlásený, vráti jeho meno zo session, ináč vráti "Neprihlásený"
        return (Auth::isLogged() ? $_SESSION['name'] : "Neprihlásený");    // Ak je používateľ prihlásený, vráti jeho meno zo session, ináč vráti "Neprihlásený"
    }
}