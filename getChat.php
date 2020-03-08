<?php
require('connexion.php');
$myid = $_POST['id'];
$id_autre= $_POST['id_user'];
$e = $db->query("SELECT dates, messages, etat, chaine, photo, nom FROM chat WHERE id_exp = $myid AND id_dest = $id_autre ORDER BY chat.id ASC");

if($e->rowcount() >=1 ) {
    $chat = $e->fetchAll();
} else {
    $chat = [];
}
echo json_encode($chat);
?>