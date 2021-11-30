<?php

namespace App\Models;

use App\Core\Model;

/** Trieda má za úlohu povedať, či je používateľ prihlásený a ak áno, tak pod akým menom */
class Auth extends Model
{
    public $id;
    public $name;
    public $password;

    /** Vracia zoznam columnov, ktoré sú v DB (ktoré stĺpce sa budú z databázy mapovať do modelu) */
    static public function setDbColumns()
    {
        return ['id', 'name', 'password'];

    }

    /** Vracia názov tabuľky, v ktorej sa dáta nachádzajú */
    static public function setTableName()
    {
        return "users";
    }

    /** Funkcia kontrolujúca, či je užívateľ s daným menom už zaregistrovaný
     * @param $name - Hľadané meno
     * @return bool true ak sa užívateľ s daným menom našiel v DB, ináč false
     */
    public static function isRegistered($name): bool
    {
        return Auth::getAll("name = ?", [$name]) ? true : false;
    }

    /** Funkcia slúžiada na zaregistrovanie nového užívateľa (a jeho zápis do DB)
     * @param $name - Meno užívateľa
     * @param $password - Heslo užívateľa
     */
    public static function register($name, $password)
    {
        $user = new Auth();
        $user->name = $name;
        $user->password = $password;
        $user->save();
    }

    /** Funkcia slúžiada na prihlásenie užívateľa. Funkcia vyhľadá užívateľa s daným menom a heslom v DB a ak sa zhodujú, prihlási ho.
     * @param $name - Prihlasovacie meno
     * @param $password - Prihlasovacie heslo
     */
    public static function login($name, $password)
    {
        if(Auth::getAll("name = ? AND password = ?", [$name, $password])) {
            $_SESSION['name'] = $name;
        }
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