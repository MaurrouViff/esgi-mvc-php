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

        $userExists = array_filter($data['users'], fn($user) => $user['nom'] === $nom);
        if (!empty($userExists)) {
            return "Cet utilisateur existe déjà.";
        }

        $passwordHashed = password_hash($motDePasse, PASSWORD_DEFAULT);

        $newUser = [
            "id" => count($data['users']) + 1,
            "nom" => $nom,
            "mot_de_passe" => $passwordHashed,
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

        $user = array_reduce($data['users'], function ($carry, $user) use ($nom, $motDePasse) {
            if ($user['nom'] === $nom && password_verify($motDePasse, $user['mot_de_passe'])) {
                $carry = $user;
            }
            return $carry;
        }, null);

        if ($user) {
            return [
                "status" => "success",
                "message" => "Connexion réussie.",
                "user" => $user
            ];
        }

        return [
            "status" => "error",
            "message" => "Nom d'utilisateur ou mot de passe incorrect."
        ];
    }
}