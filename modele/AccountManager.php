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

        // Parcourir les utilisateurs et mettre à jour le mot de passe
        foreach ($data['users'] as &$user) {
            if ($user['nom'] === $username) {
                $user["mot_de_passe"] = $newPassword;

                // Enregistrer les données mises à jour dans le fichier JSON
                return file_put_contents(self::DB_FILE, json_encode($data, JSON_PRETTY_PRINT)) !== false;
            }
        }
        return false;
    }
}