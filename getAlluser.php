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
    //recuperation de la blacklist
    $blacklist = false;
    $b = $db->query("SELECT *FROM blacklist WHERE id_user = $myid AND id_blocker= $id_user");
    if($b->rowcount() >=1) {
        $blacklist = true;
    }
    //fin
    //recuperation des matchs
    $match = false;
    $m = $db->query("SELECT id, id_flasher, id_flasheur,reponse FROM flash WHERE flash.id_flasheur = $id_user AND flash.id_flasher = $myid AND flash.reponse = 2 OR flash.id_flasheur = $myid  AND flash.id_flasher = $id_user AND flash.reponse = 2");
    if($m->rowcount() >= 1) {
        $match = true;
    }
    //fin
    //Recuperation des visite reçu
    $ma_visite = false;
    $v = $db->query("SELECT *FROM visitefaite WHERE id_user = $myid AND id_visiteur = $id_user");
    if($v->rowcount() >=1 ) {
        $ma_visite = true;
    }
    //fin
    //Recuperation des visites faites
    $jai_visite = false;
    $v2 = $db->query("SELECT *FROM visitefaite WHERE id_user =$id_user AND id_visiteur = $myid");
    if($v2->rowcount() >=1 ) {
        $jai_visite = true;
    }
    //fin
    //Recuperation des flashs faites
    $jai_flasher = false;
    $f = $db->query("SELECT *FROM flash WHERE id_flasher =$id_user AND id_flasheur = $myid");
    if($f->rowcount() >=1 ) {
        $jai_flasher = true;
    }
    //fin
    //Recuperation des flashs recu
    $il_ma_flasher = false;
    $f2 = $db->query("SELECT *FROM flash WHERE id_flasher = $myid AND id_flasheur = $id_user");
    if($f2->rowcount() >=1 ) {
        $il_ma_flasher = true;
    }
    //fin
    //Recuperation des suggetions faites
    $jai_suggerer = ["etat"=>false, "type"=>  0];
    $s = $db->query("SELECT *FROM suggestions WHERE id_suggereur = $myid AND id_user = $id_user");
    if($s->rowcount() >=1 ) {
        $il = $s->fetch();
        $jai_suggerer = ["etat"=>true, "type"=> $il['sujet']];
    }
    //fin
    //Recuperation des suggetions reçus
    $il_ma_suggerer = ["etat"=>false, "type"=>  0];
    $s2 = $db->query("SELECT *FROM suggestions WHERE id_suggereur = $id_user AND id_user = $myid");
    if($s2->rowcount() >=1 ) {
        $il_ma_suggerer = ["etat"=>false, "type"=>  $i['sujet']];
    }
    //fin
    //recuperation des chats
    $chat = [];
    $c = $db->query("SELECT id, id_exp, id_dest, dates,messages, etat, chaine,photo, nom FROM chat WHERE id_exp = $id_user AND id_dest = $myid OR id_exp = $myid AND id_dest = $id_user");
    if($c->rowcount() >=1 ) {
        $chat = $c->fetchAll();
    }
    //
    // recuperation des users photo dans l'album
    $q = $db->query("SELECT photo FROM album WHERE album.id_user = $id_user");
    $ei= $q->rowcount();
     // recuperation des coup de coeur
   $qp= $db->query("SELECT id, id_flasher, id_flasheur,reponse FROM flash WHERE flash.id_flasheur = $id_user AND flash.id_flasher = $myid");
   $eii= $qp->rowcount();
   if($eii >= 1) {
       $ii = $qp->fetch();
       $flash = ["id"=>$ii['id'], "etat"=> true, "reponse"=> $ii['reponse']];
   } else {
        $ii = $qp->fetch();
       $flash = ["id"=>$ii['id'], "etat"=> false, "reponse"=> null];
   }
   $mytab = [];
    if($ei >= 1) {
    $mytab = $q->fetchAll();
    }
    $p = array_merge($i, ['album'=>  $mytab, 'flash'=> $flash, "interets"=>$interets,"mode"=> $mode,'kilometre'=> $distance, 'suggetion'=> $suggetion, 'favoris'=>$favoris, 'blacklist'=> $blacklist, 'match'=> $match, "ma_visite"=> $ma_visite, "jai_visite"=>$jai_visite, "jai_flasher"=>$jai_flasher, "il_ma_flasher"=>$il_ma_flasher, "jai_suggerer"=>$jai_suggerer, "il_ma_suggerer"=> $il_ma_suggerer, "chat"=> $chat]);
       array_push($tab, $p);
}
echo json_encode($tab);
?>