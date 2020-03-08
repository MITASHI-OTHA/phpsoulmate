<?php
require('connexion.php');
$email = $_POST['email'];
$d = $db->query("SELECT *FROM utilisateur WHERE email = '$email'");
if($d->rowcount() >= 1) {
    $user = $d->fetchAll();
} else {
    $user = [];
}
echo json_encode($user);
?>