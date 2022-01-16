<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\Responses\ViewResponse;
use App\Core\Responses\JsonResponse;
use App\Models\Aktualita;
use App\Models\Auth;
use App\Models\Comment;

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
        foreach ($aktualita as $act) {
            $act->author_id = Auth::getOne($act->author_id)->name;
        }
//        $aktualita = Aktualita::getAllJoin('users', "author_id=users.id", 'actuality.*, users.name AS author_id', "actuality.id > 0 ORDER BY id desc LIMIT $num");
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
        $postId = $this->request()->getValue('postid'); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        return $this->html([Aktualita::getOne($postId)]);
    }

    /** Funkcia (podstránka „Pridať novú aktualitu“), ktorá slúži pre pridanie novej aktuality. */
    public function addNewActuality(): ViewResponse
    {
        if (Auth::isModerator())
            return $this->html([]);
        else
            return $this->html([]);
    }

    /** Funkcia pre upload súboru */
    public function upload()
    {
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
                $newActuality->text = preg_replace('/\R/', '<br>', $text);
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
        $postId = strip_tags($this->request()->getValue('postid')); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.

        if (is_numeric($postId) && Auth::isAdmin()) {                          // Ak sme post našli
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
        if (Auth::isModerator()) {
            $postId = strip_tags($this->request()->getValue('postid')); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
            if (is_numeric($postId)) {                          // Ak sme post našli
                try {
                    $actuality = Aktualita::getOne($postId);                                           // Vytiahnem si záznam s daným "$postId" z DB
                    return $this->html([
                        'titulok' => $actuality->title,
                        'perex' => $actuality->perex,
                        'textClanku' => preg_replace('/<br(\s+\/)?>/', "\r\n", $actuality->text),       // regexp na <br> aj <br />
                        'postid' => $postId
                    ]);
                } catch (\Exception $e) {               // V prípade výnimky (neplatné ID) redirectni
                    $this->redirectToHome();
                }
            } else $this->redirectToHome();

    } else $this->redirectToHome();
    }

    public function editActualityPostBack()
    {
        $postId = strip_tags($this->request()->getValue('postid')); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        $newTitle = strip_tags($this->request()->getValue('titulok'), Configuration::ALLOWED_TAGS);
        $newPerex = strip_tags($this->request()->getValue('perex'), Configuration::ALLOWED_TAGS);
        $newImage = $this->moveUploadedFile("subor");
        $newText = strip_tags($this->request()->getValue('textClanku'),Configuration::ALLOWED_TAGS);

        if (is_numeric($postId)) {                                      // Ak sme post našli
            try {
                $actuality = Aktualita::getOne($postId);
            } catch (\Exception $e) {       // Neplatné Post ID
                $this->redirectToHome();
            }
            if (strlen($newTitle) > 5 && strlen($newPerex) > 5 && strlen($newText) > 5) {
                if (strlen($newTitle) < 255 && strlen($newPerex) < 255) {
                    $actuality->title = $newTitle;
                    $actuality->perex = $newPerex;
                    $actuality->imagePath = $newImage ? $newImage : $actuality->imagePath;
                    $actuality->text = preg_replace('/\R/', '<br>', $newText);      // regexp na všetky typy newLine
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

    public function goNext()
    {
        $offset = strip_tags($this->request()->getValue('offset')); // Najskôr hľadá kľúč v poli "_POST", potom v poli "_GET" a ak ho nenájde, vráti NULL.
        if (isset($_GET["a"])) unset($_GET["a"]);
        if (is_numeric($offset)) {
            $num = Configuration::POCET_CLANKOV;
            try {
                $aktualita = Aktualita::getAll("id > 0 ORDER BY id desc LIMIT $num OFFSET $offset");
                foreach ($aktualita as $act) {
                    $act->author_id = Auth::getOne($act->author_id)->name;
                }
            } catch (\Exception $e) { // Neplatné ID
                $this->redirectToHome();
            }
            return $this->html($aktualita);
        } else $this->redirectToHome();
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
            $ext = strip_tags(strtolower(pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION)));   // ext = prípona názvu súboru
            $fileType = $_FILES[$inputName]["type"];                                              // filetype = MIME typ súboru
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

    /** Funkcia pre získanie všetkých komentárov prislúchajúcich danému príspevku z DB a zaslanie klientovi
     * @return JsonResponse odpoveď klientovi so zoznamom komentárov patriacich danému príspevku vo forme JSON správy */
    public function getAllComments(): JsonResponse
    {
        $postId = strip_tags($this->request()->getValue("postid"));
        if (is_numeric($postId)) {
            $comments = Comment::getAll("actuality_id = ?", [$postId]);
            foreach ($comments as $comment) {
                try {
                    $comment->author_id = Auth::getOne($comment->author_id)->name;
                } catch (\Exception $e) {
                }
            }
            return $this->json($comments);
        }
        return  $this->json(null);
    }

    /** Funkcia pre pridanie nového komentára do DB
     * @return JsonResponse odpoveď klientovi o úspešnosti pridania vo forme JSON správy */
    public function addComment(): JsonResponse
    {
        if (Auth::isLogged()) {
            $msgText = strip_tags($this->request()->getValue("comment"));
            $post_id = strip_tags($this->request()->getValue("post_id"));
            $author_id = Auth::getUserId();
            if (strlen($msgText) < 3 || !is_numeric($post_id)) {
                return $this->json("Error");
            }
            $msg = new Comment();
            $msg->comment = $msgText;
            $msg->actuality_id = $post_id;
            $msg->author_id = $author_id;
            $msg->save();
            return $this->json("OK");
        } else return $this->json("notLogged");
    }
}