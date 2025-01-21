<?php
declare(strict_types=1);
class Authentification {
    private static function getFilePath(): string {

        return dirname(__FILE__) . '/users.json';
    }

    private static function readJson(): array {
        // Lecture du fichier JSON
        $filePath = self::getFilePath();
        if (!file_exists($filePath)) {
            return ['users' => []];
        }
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true);
    }

    private static function writeJson(array $data): void {

        $filePath = self::getFilePath();
        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function register(string $nom, string $motDePasse): string {
        $data = self::readJson();


        foreach ($data['users'] as $user) {
            if ($user['nom'] === $nom) {
                return "Cet utilisateur existe déjà.";
            }
        }


        $newUser = [
            "id" => count($data['users']) + 1,
            "nom" => $nom,
            "mot_de_passe" => $motDePasse,
            "friends_id" => [],
            "friends_request_id" => [],
            "films" => []
        ];
        $data['users'][] = $newUser;


        self::writeJson($data);

        return "Inscription réussie.";
    }

    public static function login(string $nom, string $motDePasse): array {
        $data = self::readJson();

        foreach ($data['users'] as $user) {
            if ($user['nom'] === $nom && $user['mot_de_passe'] === $motDePasse) {
                return [
                    "status" => "success",
                    "message" => "Connexion réussie.",
                    "user" => $user
                ];
            }
        }

        return [
            "status" => "error",
            "message" => "Nom d'utilisateur ou mot de passe incorrect."
        ];
    }
}