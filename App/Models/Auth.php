<?php

namespace App\Models;

use App\Core\Model;

/** Trieda má za úlohu povedať, či je používateľ prihlásený a ak áno, tak pod akým menom */
class Auth extends Model
{
    public $id;
    public $name;
    public $password;
    public $role;

    /** Vracia zoznam columnov, ktoré sú v DB (ktoré stĺpce sa budú z databázy mapovať do modelu) */
    static public function setDbColumns()
    {
        return ['id', 'name', 'password', 'role'];

    }

    /** Vracia názov tabuľky, v ktorej sa dáta nachádzajú */
    static public function setTableName()
    {
        return "users";
    }

    /** Funkcia kontrolujúca, či je užívateľ s daným menom už zaregistrovaný
     * @param $name - Hľadané meno
     * @return bool True ak sa užívateľ s daným menom našiel v DB, ináč false
     */
    public static function isRegistered($name): bool
    {
        return Auth::getAll("name = ?", [$name]) ? true : false;
    }

    /** Funkcia slúžiaca na zaregistrovanie nového užívateľa (a jeho zápis do DB)
     * @param $name - Meno užívateľa
     * @param $password - Heslo užívateľa
     * @return bool True, ak registrácia bola úspešná, ináč false
     */
    public static function register($name, $password): bool
    {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $user = new Auth();
        $user->name = $name;
        $user->password = $hash;
        $user->role = 'User';
        try {
            $user->save();
        } catch (\Exception $e) {
            return false;
        }
        return true;
    }

    /** Funkcia slúžiaca pre zmenu hesla užívateľa.
     * @param $oldPassword - Pôvodné heslo používateľa
     * @param $newPassword - Nové heslo používateľa
     * @return bool True, ak bola zmena úspešná, ináč false
     */
    public static function changePassword ($oldPassword, $newPassword): bool
    {
//        $users = Auth::getAll("name = ?", [$username]);
        try {
            $user = Auth::getOne($_SESSION['userId']);
        } catch (\Exception $e) {
            return false;
        }
//        foreach ($users as $user) {
            if (password_verify($oldPassword, $user->password)) {
                $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
                try {
                    $user->save();
                } catch (\Exception $e) {
                    return false;
                }
                return true;
            }
//        }
        return false;
    }

    /** Funkcia slúžiaca pre zmazanie užívateľa.
     * @param $password - Heslo pre overenie užívateľa
     * @return bool True, ak zmazanie užívateľa prebehlo úspešne, ináč false
     */
    public static function deleteAccount ($password): bool
    {
        try {
            $user = Auth::getOne($_SESSION['userId']);
        } catch (\Exception $e) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            if (Auth::isLogged()) {
                self::logout();
            }
            try {
                $user->delete();
            } catch (\Exception $e) {
                return false;
            }
            return true;
        }
        return false;
    }

    /** Funkcia slúžiaca na prihlásenie užívateľa. Funkcia vyhľadá užívateľa s daným menom a heslom v DB a ak sa zhodujú, prihlási ho.
     * @param $name - Prihlasovacie meno
     * @param $password - Prihlasovacie heslo
     * @return bool True, ak bolo prihlásenie úspešné, ináč false
     */
    public static function login($name, $password): bool
    {
        try {
            $users = Auth::getAll("name = ?", [$name]);
        } catch (\Exception $e) {
            return false;
        }
        foreach ($users as $user) {
            if (password_verify($password, $user->password)) {
                $_SESSION['userId'] = $user->id;
                return true;
            }
        }
        return false;
    }

    /** Funkcia pre odhlásenie */
    public static function logout()
    {
        if (isset($_SESSION['userId'])) {
            unset($_SESSION['userId']);
        }
    }

    /** Funkcia, ktorá informuje či je používateľ prihlásený
     * @return bool true, ak je používateľ prihlásený, ináč false
     */
    public static function isLogged(): bool
    {
        return isset($_SESSION['userId']);
    }

    /** Funkcia, ktorá vráti meno aktuálne prihláseného používateľa
     * @return mixed|string Meno zo session ak je používateľ prihlásený, ináč vráti "Neprihlásený" */
    public static function getLoggedName()
    {
//        return ($this->isLogged() ? $_SESSION['name'] : "Neprihlásený");    // Ak je používateľ prihlásený, vráti jeho meno zo session, ináč vráti "Neprihlásený"
//        return (Auth::isLogged() ? $_SESSION['name'] : "Neprihlásený");       // Ak je používateľ prihlásený, vráti jeho meno zo session, ináč vráti "Neprihlásený"
        return (Auth::isLogged() ? Auth::getOne($_SESSION['userId'])->name : "Neprihlásený");       // Ak je používateľ prihlásený, vráti jeho meno zo session, ináč vráti "Neprihlásený"
    }

    /** Funkcia, ktorá vráti užívateľskú rolu
     * @return string|null Rolu užívateľa, ak má nejakú priradenú, ináč null.
     */
    public static function getRole()
    {
//        $users = Auth::getAll("name = ?", [$_SESSION['name']]);
//        foreach ($users as $user) {
//            return $user->role;
//        }
//        return null;
        try {
            return Auth::getOne($_SESSION['userId'])->role;
        } catch (\Exception $e) {
            return "Guest";
        }
    }
}