<?php

namespace App\Models;

use App\Core\DB\Connection;
use App\Core\Model;

class Aktualita extends Model
{
    public $id;
    public $title;
    public $imagePath;
    public $text;


    /** Vracia zoznam columnov, ktoré sú v DB (ktoré stĺpce sa budú z databázy mapovať do modelu) */
    static public function setDbColumns()
    {
        return ['id', 'title', 'imagePath', 'text'];
    }

    /** Vracia názov tabuľky, v ktorej sa dáta nachádzajú */
    static public function setTableName()
    {
        return "actuality";
    }
}