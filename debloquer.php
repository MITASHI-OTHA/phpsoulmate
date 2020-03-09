<?php
require("connexion.php");
$id = $_POST['id'];
$id_debloquer = $_POST['id_deb'];
$db->query("DELETE FROM blacklist WHERE id_user = $id AND id_blocker = $id_debloquer");
?>