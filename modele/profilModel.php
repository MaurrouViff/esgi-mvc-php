<?php


// if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//     echo "1";   
//     $data = json_decode(file_get_contents('php://input'), true);
//     if ($data['action'] === 'acceptFriend') {
//         acceptFriend($data['userId'], $data['requestId']);
//     }
// }
// echo $_SERVER['REQUEST_METHOD'];
// echo "2";
// function acceptFriend($userId, $requestId) {
//         var_dump($userId);
//         var_dump($requestId);
//         $classUsers = new Users($userId);
//         $message = $classUsers->acceptFriend($userId, $requestId);
//         return $message;
// }
