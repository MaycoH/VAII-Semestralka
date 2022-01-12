<?php

namespace App\Controllers;

use App\Config\Configuration;
use App\Core\AControllerBase;
use App\Core\Responses\JsonResponse;
use App\Models\Event;

class EventsController extends AControllerBase
{

    /** @inheritDoc */
    public function index()
    {
        return $this->html([]);
    }

    /** Funkcia pre pridanie novej udalosti do DB
     * @return JsonResponse odpoveď klientovi o úspešnosti pridania vo forme JSON správy */
    public function createNewEvent(): JsonResponse
    {
        $zaciatok = strip_tags($this->request()->getValue('zaciatok'));    // 2022-01-10T12:30
        try {
            $beginTime = date('Y-m-d H:i', strtotime($zaciatok) ?: throw new \Exception("Date or time of begin is wrong"));
        } catch (\Exception $e) {
            return $this->json("wrongStartDateTime");
        }

        $koniec = strip_tags($this->request()->getValue('koniec'));
        try {
            $endTime = date('Y-m-d H:i', strtotime($koniec) ?: throw new \Exception("Date or time of end is wrong"));
        } catch (\Exception $e) {
            return $this->json("wrongEndDateTime");
        }

        if($beginTime > $endTime)
            return $this->json("cantEndBeforeStart");
        if ($endTime < date('Y-m-d H:i'))
            return $this->json("endIsOver");

        $miesto = strip_tags($this->request()->getValue('miesto'));
        if (!(strlen($miesto) > 5 && strlen($miesto) < 255))
            return $this->json("placeIsShort");

        $popis = strip_tags($this->request()->getValue('popis'), Configuration::ALLOWED_TAGS);
        if (strlen($popis) > 5) {
            $newEvent = new Event();
            $newEvent->startTime = $beginTime;
            $newEvent->endTime = $endTime;
            $newEvent->eventDescription = $popis;
            $newEvent->save();
            return $this->json("OK");
        } else {
            return $this->json("wrongDesc");
        }
    }

    /** Funkcia pre získanie všetkých udalostí z DB a zaslanie klientovi
     * @return JsonResponse odpoveď klientovi so zoznamom udalostí vo forme JSON správy
     */
    public function getAllEvents(): JsonResponse
    {
        try {
            $events = Event::getAll();
        } catch (\Exception $e) {
            $events = null;
        }
        return $this->json($events);
    }
}