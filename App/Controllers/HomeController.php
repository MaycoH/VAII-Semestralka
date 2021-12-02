<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\DB\Connection;
use App\Core\Responses\ViewResponse;
use App\Models\Aktualita;

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
        $aktualita = Aktualita::getAll( "id > 0 ORDER BY id desc LIMIT $num");
        return $this->html($aktualita);
    }


    /** Metóda volajúca a vykresľujúca kontaktný formulár
     * @return ViewResponse
     */
    public function contact(): ViewResponse
    {
        return $this->html([]);
    }

    /** Funkcia (podstránka "Pridať novú aktualitu"), ktorá slúži pre pridanie novej aktuality. */
    public function addNewActuality(): ViewResponse
    {
        return $this->html([]);
    }

    /** Funkcia pre upload súboru */
    public function upload()
    {   // TODO: Ošetriť prázdny titulok a text
        $newActuality = new Aktualita();
        $newActuality->imagePath = $this->moveUploadedFile("subor");
        $newActuality->title = $this->request()->getValue('titulok');
        $newActuality->text = $this->request()->getValue('textClanku');
        $newActuality->save();

        $this->redirectToHome();    // Presmerujeme
    }

    /** Funkcia pre zmazanie aktuality */
    public function removeActuality()
    {
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.

        if ($postId) {                          // Ak sme post našli
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

    /** Funkcia pre úpravu aktuality */
    public function editActuality()
    {
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        $title = $this->request()->getValue('title');
        if ($postId) {                          // Ak sme post našli
            try {
                $actuality = Aktualita::getOne($postId);                                           // Vytiahnem si záznam s daným "$postId" z DB
                return $this->html([
                    'titulok' => $actuality->title,
                    'textClanku' => $actuality->text,
                    'postid' => $postId
                ]);
            } catch (\Exception $e) {               // V prípade výnimky (neplatné ID) redirectni
                $this->redirectToHome();
            }
        }
    }

    public function editActualityPostBack()
    {//TODO: Ošetriť zmenu obrázku
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        $newTitle = $this->request()->getValue('titulok');
        $newImage = $this->moveUploadedFile("subor");
        $newText = $this->request()->getValue('textClanku');

        if ($postId) {                                      // Ak sme post našli
            $actuality = Aktualita::getOne($postId);        // Vytiahnem si záznam s daným "$postId" z DB
            Connection::connect()->prepare("UPDATE actuality SET title = ?, imagePath = ?, text = ? WHERE id = ?")    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
                ->execute([$newTitle ? $newTitle : $actuality->title, $newImage ? $newImage : $actuality->imagePath, $newText ? $newText : $actuality->text, intval($postId)]);    // Spustím ho
        }
        $this->redirectToHome();    // Presmerujeme
    }

    public function goNext()
    {
        $offset = $this->request()->getValue('offset'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        unset($_GET["a"]);
        $num = Configuration::POCET_CLANKOV;
        $aktualita = Aktualita::getAll( "id > 0 ORDER BY id desc LIMIT $num OFFSET $offset");
        return $this->html($aktualita);
    }

    /** Funkcia pre redirect na úvod */
    private function redirectToHome()
    {
        header("Location: ?c=home");    // Namiesto renderovania presmerujeme.
//        return $this->html([]);     // Aj keď nič nevykresľujem, musím vracať html response, ináč sa mi nič nevykreslí.
    }

    /** Funkcia pre nahratie súboru z inputu na server.
     * @return string|null
     */
    private function moveUploadedFile($inputName): ?string
    {
        if (isset($_FILES[$inputName])) {
            if ($_FILES["$inputName"]["error"] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["$inputName"]["tmp_name"];

                $name = time() . "_" . $_FILES["$inputName"]["name"];
                $path = Configuration::IMAGES_DIR . "/$name";

                move_uploaded_file($tmp_name, $path);
                return $name;
            }
        }
        return null;
    }
}