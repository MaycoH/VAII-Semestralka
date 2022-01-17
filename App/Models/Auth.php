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
     * @return bool True ak sa užívateľ s daným menom našiel v DB, ináč false */
    public static function isRegistered($name): bool
    {
        try {
            return Auth::getAll("name = ?", [$name]) ? true : false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /** Funkcia slúžiaca na zaregistrovanie nového užívateľa (a jeho zápis do DB)
     * @param $name - Meno užívateľa
     * @param $password - Heslo užívateľa
     * @return bool <b>true</b>, ak registrácia bola úspešná, ináč <b>false</b> */
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
     * @return bool <b>true</b>, ak bola zmena úspešná, ináč <b>false</b> */
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
     * @return bool <b>true</b>, ak zmazanie užívateľa prebehlo úspešne, ináč <b>false</b> */
    public static function deleteAccount ($password): bool
    {
        try {
            $user = Auth::getOne($_SESSION['userId']);
        } catch (\Exception $e) {
            return false;
        }
        if (password_verify($password, $user->password)) {
            try {
                foreach (Aktualita::getAll("author_id = ?", [$user->id]) as $aktualita) {
                    $aktualita->author_id = null;
                    $aktualita->save();
                }
                foreach (Comment::getAll("author_id = ?", [$user->id]) as $comment) {
                    $comment->author_id = null;
                    $comment->save();
                }
                $user->delete();

                if (Auth::isLogged()) {
                    self::logout();
                }
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

    /** Funkcia pre odhlásenie
     * @return bool <b>true</b>, ak odhlásenie bolo úspešné, ináč <b>false</b> */
    public static function logout(): bool
    {
        if (isset($_SESSION['userId'])) {
            unset($_SESSION['userId']);
            return true;
        } else return false;
    }

    /** Funkcia, ktorá informuje či je používateľ prihlásený
     * @return bool <b>true</b>, ak je používateľ prihlásený, ináč <b>false</b> */
    public static function isLogged(): bool
    {
        return isset($_SESSION['userId']);
    }

    /** Funkcia, ktorá vráti meno aktuálne prihláseného používateľa
     * @return string <b>Meno zo session</b> ak je používateľ prihlásený, ináč vráti "<b>Neprihlásený</b>" */
    public static function getLoggedName()
    {
        return (Auth::isLogged() ? Auth::getOne($_SESSION['userId'])->name : "Neprihlásený");       // Ak je používateľ prihlásený, vráti jeho meno zo session, ináč vráti "Neprihlásený"
    }

    /** Funkcia, ktorá vráti užívateľskú rolu
     * @return string Rolu užívateľa, ak má nejakú priradenú. */
    public static function getRole(): string
    {
        try {
            return Auth::getOne($_SESSION['userId'])->role;
        } catch (\Exception $e) {
            return "Guest";
        }
    }

    /** Funkcia, ktorá kontroluje, či má daný užívateľ rolu "<b>Admin</b>"
     * @return bool <b>true</b>, ak daný užívateľ má nastavenú rolu "<b><i>Admin</i></b>", ináč <b>false</b> */
    public static function isAdmin(): bool
    {
        try {
            return Auth::isLogged() && Auth::getOne($_SESSION['userId'])->role == "Admin";
        } catch (\Exception $e) {
            return false;
        }

    }
    /** Funkcia, ktorá kontroluje, či má daný užívateľ rolu "<b>Moderator</b>"
     * @return bool <b>true</b>, ak daný užívateľ má nastavenú rolu "<b><i>Admin</i></b> alebo rolu "<b><i>Moderator</i></b>", ináč <b>false</b> */
    public static function isModerator(): bool
    {
        try {
            return Auth::isLogged()
                && (Auth::getOne($_SESSION['userId'])->role == "Admin"
                    || Auth::getOne($_SESSION['userId'])->role == "Moderator");
        } catch (\Exception $e) {
            return false;
        }
    }

    /** Funkcia, ktorá vracia užívateľove ID zo session.
     * @return int|void ID zo session, ak bolo nastavené. */
    public static function getUserId() {
        if (isset($_SESSION['userId']))
            return $_SESSION['userId'];
    }
}