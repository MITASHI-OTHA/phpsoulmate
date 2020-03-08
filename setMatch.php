<?php
require("connexion.php");
$id = $_POST['id'];
$id_flasheur = $_POST['id_flasheur'];
$etat = $_POST['etat'];
$e= $db->query("SELECT COUNT(`id_flasheur`) AS nbreFlash FROM flash WHERE `id_flasheur`= $id_flasheur AND id_flasher = $id");
if($e->rowcount() >= 1) {
    $db->query("UPDATE flash SET reponse = $etat WHERE `id_flasheur`= $id_flasheur AND id_flasher = $id");
} else {
    $db->query("INSERT INTO flash SET reponse = $etat, `id_flasheur`= $id_flasheur,  id_flasher = $id");
}
?>