<?php
require('connexion.php');
$id = $_POST['id'];
$id_pers_flasher = $_POST['id_pers'];
$e = $db->query("SELECT *FROM flash WHERE flash.id_flasheur = $id AND flash.id_flasher = $id_pers_flasher");
if($e->rowcount() <=0) {
    $db->query("INSERT INTO flash SET id_flasheur = $id,  id_flasher = $id_pers_flasher, reponse= 0");
}
?>