<?php

namespace App\Config;

/**
 * Class Configuration
 * Main configuration for the application
 * @package App\Config
 */
class Configuration
{
    public const DB_HOST = 'localhost';
    public const DB_NAME = 'covidinfo';
    public const DB_USER = 'root';
    public const DB_PASS = 'dtb456';

    public const LOGIN_URL = '/';

    public const ROOT_LAYOUT = 'root.layout.view.php';

    public const DEBUG_QUERY = false;

    public const UPLOAD_DIR = "public/files";

    /** @var string Konštanta určujúca root adresár projektu */
    public const ROOT_DIR = "Semestralka";
    /** @var string Konštanta určujúca podadresár s obrázkami projektu */
    public const IMAGES_DIR = "public/images";
//    public const IMAGES_DIR = Configuration::ROOT_DIR."public/images";
    /** @var int Konštanta určujúca počet aktualít na stránke */
    public const POCET_CLANKOV = 3;

    /** @var string Konštanta určujúca povolené tagy v inputoch */
    public const ALLOWED_TAGS = '<p><b><strong><br><i><em><small><sub><sup><del><ins><mark>';
}