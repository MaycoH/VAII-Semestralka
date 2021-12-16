<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\DB\Connection;
use App\Core\Responses\ViewResponse;
use App\Models\Aktualita;
use App\Models\Auth;

// TODO: Ošetrenie neplatných hodnôt

/**
 * Class HomeController
 * Example of simple controller
 * @package App\Controllers
 */
class HomeController extends AControllerBase
{
    /** Úvodná stránka, ktorá bude zobrazovať zoznam všetkých aktualít. */
    public function index()
    {
//        $aktualita = Aktualita::getAll();
        $num = Configuration::POCET_CLANKOV;
//        $aktualita = Aktualita::getAll( "id > 0 ORDER BY id desc LIMIT $num");
        $aktualita = Aktualita::getAllJoin('users', "author_id=users.id", 'actuality.*, users.name AS author_id', "actuality.id > 0 ORDER BY id desc LIMIT $num");
        return $this->html($aktualita);
    }


    /** Metóda volajúca a vykresľujúca kontaktný formulár
     * @return ViewResponse
     */
    public function contact(): ViewResponse
    {
        return $this->html([]);
    }

    /** Funkcia, ktorá slúži pre vykreslenie aktuality vo view „viewActuality“ po kliknutí na tlačidlo „Zobraziť viac“ v zozname aktualít */
    public function viewActuality(): ViewResponse
    {
        // TODO: Vytvoriť viewActuality

        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        return $this->html([Aktualita::getOne($postId)]);
    }

    /** Funkcia (podstránka „Pridať novú aktualitu“), ktorá slúži pre pridanie novej aktuality. */
    public function addNewActuality(): ViewResponse
    {
        if (Auth::isLogged() && (Auth::getRole() == 'Admin' || Auth::getRole() == 'Moderator'))
            return $this->html([]);
        else
            return $this->html([]);
    }

    /** Funkcia pre upload súboru */
    public function upload()
    {   // TODO: Ošetriť prázdny titulok a text
        $title = strip_tags($this->request()->getValue('titulok'), Configuration::ALLOWED_TAGS);
        $perex = strip_tags($this->request()->getValue('perex'), Configuration::ALLOWED_TAGS);
        $imagePath = $this->moveUploadedFile("subor");
        $text = strip_tags($this->request()->getValue('textClanku'), Configuration::ALLOWED_TAGS);
        if (strlen($title) > 5 && strlen($perex) > 5 && strlen($text) > 5) {
            if (strlen($title) < 255 && strlen($perex) < 255) {
                $newActuality = new Aktualita();
                $newActuality->title = $title;
                $newActuality->perex = $perex;
                $newActuality->imagePath = $imagePath;
                $newActuality->text = $text;
                $newActuality->author_id = $_SESSION['userId'];
                $newActuality->save();
            }
        } else {
            return $this->html([
                'titulok' => $title,
                'perex' => $perex,
                'textClanku' => $text,
                'error' => "wrongData"
            ],'addNewActuality');
        }
        $this->redirectToHome();    // Presmerujeme
    }

    /** Funkcia pre zmazanie aktuality */
    public function removeActuality()
    {
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.

        if ($postId && Auth::isLogged() && Auth::getRole() == "Admin") {                          // Ak sme post našli
            try {
                $actuality = Aktualita::getOne($postId);
                $actuality->delete();
            } catch (\Exception $e) {               // V prípade výnimky (neplatné ID) redirectni
                $this->redirectToHome();
            }
            // Pomocou PDO:
//            Connection::connect()->prepare("DELETE FROM actuality WHERE id = ?")             // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
//            ->execute([intval($postId)]);                                                    // Spustím ho
        }
        $this->redirectToHome();    // Presmerujeme
    }

    /** Funkcia pre úpravu aktuality - Funkcia pre predvyplnenie formulára */
    public function editActuality()
    {
        if (Auth::isLogged() && (Auth::getRole() == 'Moderator' || Auth::getRole() == 'Admin')) {
            $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
            $title = $this->request()->getValue('title');
            if ($postId) {                          // Ak sme post našli
                try {
                    $actuality = Aktualita::getOne($postId);                                           // Vytiahnem si záznam s daným "$postId" z DB
                    return $this->html([
                        'titulok' => $actuality->title,
                        'perex' => $actuality->perex,
                        'textClanku' => $actuality->text,
                        'postid' => $postId
                    ]);
                } catch (\Exception $e) {               // V prípade výnimky (neplatné ID) redirectni
                    $this->redirectToHome();
                }
            }
        } else $this->redirectToHome();
    }

    public function editActualityPostBack()
    {//TODO: Ošetriť zmenu obrázku
    // TODO: Doriešiť zmenu dát - DONE
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        $newTitle = strip_tags($this->request()->getValue('titulok'), Configuration::ALLOWED_TAGS);
        $newPerex = strip_tags($this->request()->getValue('perex'), Configuration::ALLOWED_TAGS);
        $newImage = $this->moveUploadedFile("subor");
        $newText = strip_tags($this->request()->getValue('textClanku'),Configuration::ALLOWED_TAGS);

        if ($postId) {                                      // Ak sme post našli
            $actuality = Aktualita::getOne($postId);        // Vytiahnem si záznam s daným "$postId" z DB
            if (strlen($newTitle) > 5 && strlen($newPerex) > 5 && strlen($newText) > 5) {
                if (strlen($newTitle) < 255 && strlen($newPerex) < 255) {
                    $actuality->title = $newTitle;
                    $actuality->perex = $newPerex;
                    $actuality->imagePath = $newImage ? $newImage : $actuality->imagePath;
                    $actuality->text = $newText;
                    $actuality->save();
//            Connection::connect()->prepare("UPDATE actuality SET title = ?, imagePath = ?, text = ? WHERE id = ?")    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
//                ->execute([$newTitle ? $newTitle : $actuality->title, $newImage ? $newImage : $actuality->imagePath, $newText ? $newText : $actuality->text, intval($postId)]);    // Spustím ho
                }
            } else {
                return $this->html([
                    'titulok' => $actuality->title,
                    'perex' => $actuality->perex,
                    'textClanku' => $actuality->text,
                    'postid' => $postId,
                    'error' => "wrongData"
                ],'editActuality');
            }
        }
        $this->redirectToHome();    // Presmerujeme
    }

    public function goNext(): ViewResponse
    {
        $offset = $this->request()->getValue('offset'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        unset($_GET["a"]);
        $num = Configuration::POCET_CLANKOV;
        $aktualita = Aktualita::getAll( "id > 0 ORDER BY id desc LIMIT ? OFFSET $offset");
        return $this->html($aktualita);
    }

    /** Funkcia pre redirect na úvod */
    private function redirectToHome()
    {
        header("Location: ?c=home");    // Namiesto renderovania presmerujeme.
//        return $this->html([]);     // Aj keď nič nevykresľujem, musím vracať html response, ináč sa mi nič nevykreslí.
    }

    /** Funkcia pre nahratie súboru z inputu na server.
     * @return string|null Názov súboru v prípade úspešného nahratia súboru, ináč null.
     */
    private function moveUploadedFile($inputName): ?string
    {
        if (isset($_FILES[$inputName])) {
            $ext = strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION));
            $fileType = $_FILES[$inputName]["type"];
            if (($ext === 'jpg' || $ext === 'jpeg') && $fileType === "image/jpeg") {    // Kontrola typu súboru
                if ($_FILES["$inputName"]["error"] == UPLOAD_ERR_OK) {
                    $tmp_name = $_FILES["$inputName"]["tmp_name"];

                    $name = time() . "_" . $_FILES["$inputName"]["name"];
                    $path = Configuration::IMAGES_DIR . "/$name";

                    move_uploaded_file($tmp_name, $path);
                    return $name;
                }
            }
        }
        return null;
    }
}