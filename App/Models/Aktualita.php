<?php

namespace App\Models;

use App\Core\Model;

use App\Core\DB\Connection;
use PDOException;

class Aktualita extends Model
{
    public $id;
    public $title;
    public $imagePath;
    public $perex;
    public $text;
    public $author_id;


    /** Vracia zoznam columnov, ktoré sú v DB (ktoré stĺpce sa budú z databázy mapovať do modelu) */
    static public function setDbColumns()
    {
        return ['id', 'title', 'imagePath', 'perex', 'text', 'author_id'];
    }

    /** Vracia názov tabuľky, v ktorej sa dáta nachádzajú */
    static public function setTableName()
    {
        return "actuality";
    }

}