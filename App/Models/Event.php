<?php

namespace App\Models;

use App\Core\Model;

class Event extends Model
{

    public $id;
    public $startTime;
    public $endTime;
    public $place;
    public $eventDescription;

    static public function setDbColumns()
    {
        return ['id', 'startTime', 'endTime', 'place', 'eventDescription'];
    }

    static public function setTableName()
    {
        return "events";
    }
}