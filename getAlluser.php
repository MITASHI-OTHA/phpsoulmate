<?php
require('connexion.php');
require('vendor/autoload.php');
$email = $_POST['email'];
$x = $db->query("SELECT id, latitude,longitude FROM utilisateur WHERE utilisateur.email = '$email'");
$xx = $x->fetch();
$myid = $xx['id'];
$mylatitude = $xx['latitude'];
$mylongitude = $xx['longitude'];
$e = $db->query("SELECT *FROM utilisateur WHERE email !='$email'");
$tab = [];
while($i = $e->fetch()) {
    //calcule de la distance en kms
    $id_user = $i['id'];
    $latitude = $i['latitude'];
    $longitude = $i['longitude'];
    $response =  \GeometryLibrary\SphericalUtil::computeDistanceBetween(
        ['lat' => $mylatitude, 'lng' => $mylongitude],
        ['lat' => $latitude, 'lng' => $longitude]);
    $distance = $response/1000;
    //fin
    // recuperation du centre d'interet des users
    $q3 = $db->query("SELECT music, film, passeTemps, sport, aime, deteste FROM centreinteret WHERE centreinteret.id_user = $id_user");
    $e3= $q3->rowcount();
    if($e3 >= 1) {
       $ex = $q3->fetch();
        $interets = ['music'=> $ex['music'], 'film'=> $ex['film'], 'passeTemps'=> $ex['passeTemps'], 'sport'=> $ex['sport'], 'aime'=> $ex['aime'], 'deteste'=> $ex['deteste']];
    } else {
        $interets = [];
    }
    // Recuperation du mode de vie
    $q4 = $db->query("SELECT *FROM modevie WHERE modevie.id_user = $id_user");
    $e4= $q4->rowcount();
    if($e4 >= 1) {
        $e10 = $q4->fetch();
        $mode = ['Habitation'=> $e10['habitation'], 'situation'=> utf8_encode($e10['situation']),'Enfant(s)'=> $e10['enfants'], 'Profession'=>$e10['profession'], 'Apparence'=>$e10['apparence'], 'Taille'=> $e10['taille'], 'Poids'=> $e10['poids']];
    } else {
        $mode = [];
    }
    //fin
    // Recuperation des suggetions
    $q40 = $db->query("SELECT *FROM suggestions WHERE suggestions.id_user = $id_user AND suggestions.id_suggereur = $myid");
    $e40= $q40->rowcount();
    if($e40 >= 1) {
        $suggetion = $q40->fetchAll();
    } else {
        $suggetion = [];
    }
    //fin
    //Recuperation des favoris
    $q30 = $db->query("SELECT *FROM favoris WHERE favoris.id_user = $myid AND id_son_favoris = $id_user");
    if($q30->rowcount() >=1) {
        $favoris = true;
    } else {
        $favoris = false;
    }
    //fin
    // recuperation des users photo dans l'album
    $q = $db->query("SELECT photo FROM album WHERE album.id_user = $id_user");
    $ei= $q->rowcount();
     // recuperation des coup de coeur
   $qp= $db->query("SELECT id_flasher, id_flasheur,reponse FROM flash WHERE flash.id_flasheur = $id_user AND flash.id_flasher = $myid");
   $eii= $qp->rowcount();
   if($eii >= 1) {
       $ii = $qp->fetch();
       $flash = ["etat"=> true, "reponse"=> $ii['reponse']];
   } else {
        $ii = $qp->fetch();
       $flash = ["etat"=> false, "reponse"=> null];
   }
    if($ei >= 1) {
       $p = array_merge($i, ['album'=> $q->fetchAll(), 'flash'=> $flash, "interets"=>$interets,"mode"=> $mode,'kilometre'=> $distance, 'suggetion'=> $suggetion, 'favoris'=>$favoris]);
       array_push($tab, $p);
    } else {
        $p = array_merge($i, ['album'=> [], 'flash'=> $flash,"interets"=>$interets,"mode"=> $mode, 'kilometre'=> $distance, 'suggetion'=> $suggetion, 'favoris'=>$favoris]);
        array_push($tab, $p);
    }
  
}
echo json_encode($tab);
?>