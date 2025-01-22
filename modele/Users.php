<?php
declare(strict_types=1);
class Users {
    private mixed $data;
    private static function getFilePath(): string {
        return dirname(__FILE__) . '/users.json';
    }
    public function __construct() {
        $filePath = self::getFilePath();
        if (file_exists($filePath)) {
            $this->data = json_decode(file_get_contents($filePath), true);
        } else {
            $this->data = [];
        }
    }

    public function recupUserById($id): mixed {
        foreach ($this->data['users'] as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    public function acceptFriend($userId, $friendId): bool
    {
    foreach ($this->data['users'] as &$user) {
        if ($user['id'] == $userId) {
            if (!isset($user['friends'])) {
                $user['friends'] = [];
            }
            if (!in_array($friendId, $user['friends'])) {
                $user['friend_id'][] = $friendId;
                $user['friend_request_id'] = array_diff($user['friend_request_id'], [$friendId]);
                $this->saveData();
                return true;
            }
        }
    }
    return false;
}

private function saveData(): void
{
    $filePath = self::getFilePath();
    file_put_contents($filePath, json_encode($this->data, JSON_PRETTY_PRINT));
}
}