<?php

declare(strict_types=1);

class AccountManager
{
    private const DB_FILE = __DIR__ . "/users.json";

    public static function changePassword(string $username, string $newPassword): bool
    {
        // Lire les données depuis le fichier JSON
        $data = json_decode(file_get_contents(self::DB_FILE), true);
        if (!$data || !isset($data['users'])) {
            return false; // Erreur ou structure incorrecte
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Mettre à jour le mot de passe de l'utilisateur
        $data['users'] = array_map(function ($user) use ($username, $hashedPassword) {
            if ($user['nom'] === $username) {
                $user["mot_de_passe"] = $hashedPassword;
            }
            return $user;
        }, $data['users']);

        // Enregistrer les données mises à jour dans le fichier JSON
        return file_put_contents(self::DB_FILE, json_encode($data, JSON_PRETTY_PRINT)) !== false;
    }
}
