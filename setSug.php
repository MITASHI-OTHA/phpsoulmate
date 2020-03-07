<?php
require('connexion.php');
$email = $_POST['email'];
$e = $db->query("SELECT id FROM utilisateur WHERE utilisateur.email = '$email'");
$e1 = $e->fetch();
$myid = $e1['id'];
$idSuggerer = $_POST['id_pers'];
$sujet = $_POST['sujet'];
$db->query("INSERT INTO suggestions SET id_user = $idSuggerer, id_suggereur= $myid, sujet= $sujet");
?>