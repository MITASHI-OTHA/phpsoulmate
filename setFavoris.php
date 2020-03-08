<?php
require('connexion.php');
$id_user=$_POST['id'];
$id_son_favoris = $_POST['id_favoris'];
$q30 = $db->query("SELECT *FROM favoris WHERE favoris.id_user = $id_user AND id_son_favoris = $id_son_favoris");
    if($q30->rowcount() >=1) {
        $db->query("DELETE FROM favoris WHERE favoris.id_user = $id_user AND id_son_favoris = $id_son_favoris");
    } else {
       $db->query("INSERT INTO favoris SET id_user = $id_user, id_son_favoris = $id_son_favoris");
    }

?>