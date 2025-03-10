<?php

class MainController {

    private static array $actions = array(
        "defaut" => "accueil.php",
        'accueil' => "accueil.php",
        'search' => "searchMovieController.php",
        'movieDetails' => "MovieInfosController.php",
        "connexion" => "connexion.php",
        "authentification" => "authentification.php",
        "logout" => "logout.php",
    );


    public static function execute(string $action): string {
        if (array_key_exists($action, self::$actions)) {
            return self::$actions[$action];
        } else {
            return self::$actions["defaut"];
        }
    }
}

$action = "detail";
$page = MainController::execute($action);