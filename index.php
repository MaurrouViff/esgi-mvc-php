<?php
include "getRacine.php";
include "$racine/controller/MainController.php";


$action = $_GET["action"] ?? "defaut";

$fichier = MainController::execute($action);
include "$racine/controller/$fichier";