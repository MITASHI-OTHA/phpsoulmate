<?php
require('connexion.php');
$email = $_POST['email'];
$password = $_POST['password'];
$e = $db->query("SELECT *FROM utilisateur WHERE email = '$email' AND passwords = LOWER('$password')");
if($e->rowcount() >=1) {
    $user = $e->fetchAll();
} else {
    $user = [];
}
echo json_encode($user);
?>