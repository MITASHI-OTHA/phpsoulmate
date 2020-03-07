<?php
$id_user = $_POST['id'];
$image = $_POST['image'];
require('connexion.php');
$e = $db->prepare("INSERT INTO album SET id_user=:id, photo=:images");

$e->bindValue(":id", $id_user);
$e->bindValue(':images', $image);
$e->execute();

?>