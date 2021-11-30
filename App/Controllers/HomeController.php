<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\DB\Connection;
use App\Models\Aktualita;
use mysql_xdevapi\Exception;

/**
 * Class HomeController
 * Example of simple controller
 * @package App\Controllers
 */
class HomeController extends AControllerBase
{
//    public $offset=0;
//    public static $name = "Aktuality";
    /** Úvodná stránka, ktorá bude zobrazovať zoznam všetkých aktualít. */
    public function index()
    {
//        $aktualita = Aktualita::getAll();
        $num = Configuration::POCET_CLANKOV;
        $aktualita = Aktualita::getAll( "id > 0 ORDER BY id desc LIMIT $num");
        return $this->html($aktualita);
    }



    public function contact()
    {
        return $this->html(
            []
        );
    }

    /** Funkcia (podstránka "Pridať novú aktualitu"), ktorá slúži pre pridanie novej aktuality. */
    public function addNewActuality()
    {
        return $this->html([]);
    }

    /** Funkcia pre upload súboru */
    public function upload()
    {   // TODO: Ošetriť prázdny titulok a text
        if (isset($_FILES['subor'])) {
            if ($_FILES["subor"]["error"] == UPLOAD_ERR_OK) {
                $tmp_name = $_FILES["subor"]["tmp_name"];

                $name = time()."_".$_FILES["subor"]["name"];
                $path = Configuration::IMAGES_DIR."/$name";

                move_uploaded_file($tmp_name, $path);

                $newActuality = new Aktualita();
                $newActuality->imagePath = $name;
                $newActuality->title = $_POST["titulok"];
                $newActuality->text = $this->request()->getValue('textClanku');
                $newActuality->save();
            }
        }
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

//            Connection::connect()->prepare("DELETE FROM actuality WHERE id = ?")             // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
//            ->execute([intval($postId)]);                                                        // Spustím ho
        }
        $this->redirectToHome();    // Presmerujeme
    }

    /** Funkcia pre úpravu aktuality */
    public function editActuality()
    {
        // TODO: Dokončiť úpravu aktuality
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        $title = $this->request()->getValue('title');
///

        if ($postId) {                          // Ak sme post našli        TODO: Ošetriť neplatný postId - DONE
            try {
                $actuality = Aktualita::getOne($postId);                                           // Vytiahnem si záznam s daným "$postId" z DB
///
//            Connection::connect()->prepare("DELETE FROM actuality WHERE id = ?")             // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
//            ->execute([intval($postId)]);                                                    // Spustím ho
            return $this->html([
                'titulok' => $actuality->title,
                'textClanku' => $actuality->text,
                'postid' => $postId
            ]);
            } catch (\Exception $e) {               // V prípade výnimky (neplatné ID) redirectni
                $this->redirectToHome();
            }
        }
//        header("Location: ?c=home&a=editActuality");

//        if ($postId) {                          // Ak sme post našli
//            Connection::connect()->prepare("UPDATE actuality SET title = ? WHERE id = ?")    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
//            ->execute([46, intval($postId)]);                                                        // Spustím ho
//        }
//        $this->redirectToHome();    // Presmerujeme
    }

    public function editActualityPostBack()
    {//TODO: Ošetriť zmenu obrázku
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        $newTitle = $this->request()->getValue('titulok');
        $newText = $this->request()->getValue('textClanku');

        if ($postId) {                          // Ak sme post našli
//            if ($titletext)
            $actuality = Aktualita::getOne($postId);        // Vytiahnem si záznam s daným "$postId" z DB
            Connection::connect()->prepare("UPDATE actuality SET title = ?, text = ? WHERE id = ?")    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
                ->execute([$newTitle ? $newTitle : $actuality->title, $newText ? $newText : $actuality->text, intval($postId)]);    // Spustím ho
        }
        $this->redirectToHome();    // Presmerujeme
    }

    public function goNext()
    {
        $offset = $this->request()->getValue('offset'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        unset($_GET["a"]);
        $this->index();
        $num = Configuration::POCET_CLANKOV;
        $count = Connection::connect()->prepare("SELECT count(*) FROM actuality");    // Pomocou "connect()" si vyžiadam spojenie a preparenem si SQL
        $count->execute([]);    // Spustím ho
        $count2 = $count->fetch();
//        echo json_encode($count2);
        $aktualita = Aktualita::getAll( "id > 0 ORDER BY id desc LIMIT $num OFFSET $offset");
        return $this->html($aktualita);
    }

    /** Funkcia pre redirect na úvod */
    public function redirectToHome()
    {
        header("Location: ?c=home");    // Namiesto renderovania presmerujeme.
//        return $this->html([]);     // Aj keď nič nevykresľujem, musím vracať html response, ináč sa mi nič nevykreslí.
    }

    /** Funkcia vracajúca názov * @return string     */
//    public function getNazov()
//    {
//        return name;
//    }
}