<?php
declare(strict_types=1);
class Authentification {
    private static function getFilePath(): string {
        // Chemin vers le fichier JSON
        return dirname(__FILE__) . '/users.json';
    }

    private static function readJson(): array {
        // Lecture du fichier JSON
        $filePath = self::getFilePath();
        if (!file_exists($filePath)) {
            return ['users' => []]; // Retourne une structure vide si le fichier n'existe pas
        }
        $jsonContent = file_get_contents($filePath);
        return json_decode($jsonContent, true);
    }

    private static function writeJson(array $data): void {
        // Écriture dans le fichier JSON
        $filePath = self::getFilePath();
        file_put_contents($filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function register(string $nom, string $motDePasse): string {
        $data = self::readJson();

        // Vérification si l'utilisateur existe déjà
        foreach ($data['users'] as $user) {
            if ($user['nom'] === $nom) {
                return "Cet utilisateur existe déjà.";
            }
        }

        // Création d'un nouvel utilisateur
        $newUser = [
            "id" => count($data['users']) + 1,
            "nom" => $nom,
            "mot_de_passe" => $motDePasse,
            "friend_id" => [],
            "films" => []
        ];
        $data['users'][] = $newUser;

        // Écriture dans le fichier JSON
        self::writeJson($data);

        return "Inscription réussie.";
    }

    public static function login(string $nom, string $motDePasse): string {
        $data = self::readJson();

        // Recherche de l'utilisateur
        foreach ($data['users'] as $user) {
            if ($user['nom'] === $nom && $user['mot_de_passe'] === $motDePasse) {
                return "Connexion réussie.";
            }
        }

        return "Nom d'utilisateur ou mot de passe incorrect.";
    }
}