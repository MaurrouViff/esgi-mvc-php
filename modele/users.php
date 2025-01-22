<?php
declare(strict_types=1);
class Users {
    private $data;
    private static function getFilePath(): string {
        return dirname(__FILE__) . '/users.json';
    }
    public function __construct(int $userId) {
        $filePath = self::getFilePath();
        $this->data = json_decode(file_get_contents($filePath), true);
        if (file_exists($filePath) or !isset($userId)) {
            if ($userId !== 0 ) {
                foreach ($this->data['users'] as &$user) {
                    if ($user['id'] == $userId) {
                        $this->data = $user;
                    }
                }
            }
            
            
        } else {
            $this->data = [];
        }
    }

    public function recupUserById(int $id) : array|null {
        $data = json_decode(file_get_contents(self::getFilePath()), true);
        foreach ($data["users"] as $user) {
            if ($user['id'] == $id) {
                return $user;
            }
        }
        return null;
    }
    /**
     * Accept a friend request by updating the user's friends list.
     *
     * @param int $userId The ID of the user accepting the friend request.
     * @param int $friendId The ID of the friend to be accepted.
     * @return array An array containing the success status and a message.
     */
    public function acceptFriend(int $userId, int $friendId): array {
        // Load all user data from the JSON file
        $alldata = json_decode(file_get_contents(self::getFilePath()), true);
        if(is_null($alldata)){
            return ['success' => false, 'message' => 'Failed to open file'];
        }
        // Iterate through all users to find the user with the given userId
        foreach ($alldata["users"] as &$user) {
            if ($user['id'] == $userId) {
                // Search for the friendId in the user's friend requests
                $key = array_search($friendId, $user["friends_request_id"]);
                
                // If the friendId is found in the friend requests
                if ($key !== false) {
                    // Add the friendId to the user's friends list
                    $user["friends_id"][] = $friendId;
                    // Add the userId to the friend's friends list
                    foreach ($alldata["users"] as &$friend) {
                        if ($friend['id'] == $friendId) {
                            $friend["friends_id"][] = $userId;
                            break;
                        }
                    }
                    // Remove the friendId from the friend requests
                    unset($user["friends_request_id"][$key]);
                    
                    // Save the updated data back to the JSON file
                    file_put_contents(self::getFilePath(), json_encode($alldata, JSON_PRETTY_PRINT));
                    
                    // Update the session with the new user data
                    $_SESSION['user'] = $user;
                    
                    // Return success message
                    return ['success' => true, 'message' => 'Friend accepted'];
                }
            }
        }
        
        // Return failure message if the friend request was not found
        return ['success' => false, 'message' => 'Failed to accept friend'];
    }

    public function getAllUsersNotFriend(int $userId): array {
        $filePath = self::getFilePath();
        $alldata = json_decode(file_get_contents($filePath), true);
        $notFriends = [];
        if(is_null($alldata)){
            return ['success' => false, 'message' => 'Failed to open file'];
        }
        foreach ($alldata['users'] as $user) {
            // ajoute tout les id utilisateur
            $notFriends[] = $user;
        }
        // retire l'utilisateur connecté et ses amies de la liste
        foreach ($alldata['users'] as $key => $user) {
            if ($user['id'] == $userId) {
                unset($notFriends[$key]);
            }
            if (in_array($user['id'], $this->recupUserById($userId)['friends_id'])) {
                unset($notFriends[$key]);
            }
        }
        

        return $notFriends;
    }
    public function addFriend(int $userId, int $friendId): array {
        // Load all user data from the JSON file
        $alldata = json_decode(file_get_contents(self::getFilePath()), true);
        if(is_null($alldata)){
            return ['success' => false, 'message' => 'Failed to open file'];
        }
        // Iterate through all users to find the user with the given friendId
        foreach ($alldata["users"] as &$user) {
            if ($user['id'] == $friendId) {
                // Check if the userId is already in the friend's friends list
                if (!empty($user["friends_id"])) {
                    if (in_array($userId, $user["friends_id"])) {
                        return ['success' => false, 'message' => 'Already friends'];
                    }
                }
                

                // Check if the userId is already in the friend's friend requests
                if (!empty($user["friends_request_id"])) {
                    if (in_array($userId, $user["friends_request_id"])) {
                        return ['success' => false, 'message' => 'Friend request already sent'];
                    }
                }
                // Add the userId to the friend's friend requests
                $user["friends_request_id"][] = $userId;

                // Save the updated data back to the JSON file
                file_put_contents(self::getFilePath(), json_encode($alldata, JSON_PRETTY_PRINT));

                
                // Return success message
                return ['success' => true, 'message' => 'Friend request sent'];
            }
            if($user['id']== $userId){
                $_SESSION['user'] = $user;
            }
        }

        // Return failure message if the friend was not found
        return ['success' => false, 'message' => 'User not found'];
    }
    public function rejectFriendRequest(int $userId, int $friendId): array {
        // Load all user data from the JSON file
        $alldata = json_decode(file_get_contents(self::getFilePath()), true);
        if(is_null($alldata)){
            return ['success' => false, 'message' => 'Failed to open file'];
        }
        // Iterate through all users to find the user with the given userId
        foreach ($alldata["users"] as &$user) {
            if ($user['id'] == $userId) {
                // Search for the friendId in the user's friend requests
                $key = array_search($friendId, $user["friends_request_id"]);

                // If the friendId is found in the friend requests
                if ($key !== false) {
                    // Remove the friendId from the friend requests
                    unset($user["friends_request_id"][$key]);

                    // Save the updated data back to the JSON file
                    file_put_contents(self::getFilePath(), json_encode($alldata, JSON_PRETTY_PRINT));

                    // Update the session with the new user data
                    $_SESSION['user'] = $user;

                    // Return success message
                    return ['success' => true, 'message' => 'Friend request rejected'];
                }
            }
        }

        // Return failure message if the friend request was not found
        return ['success' => false, 'message' => 'Failed to reject friend request'];
    }
private function saveData(): void {
    $filePath = self::getFilePath();
    file_put_contents($filePath, json_encode($this->data, JSON_PRETTY_PRINT));
}
}