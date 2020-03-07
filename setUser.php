<?php
require 'connexion.php';
$photo = '';
$password = '';
$numero = '';
if(isset($_POST['tel'])) {
    $numero = $_POST['tel'];
}
$type = $_POST['type'];
$email = '';
if(isset($_POST['email'])) {
   $email = $_POST['email']; 
}
$adresse = $_POST['lieux']; 
$conf = 0;
if($type == 'formulaire') {
    $conf = 0;
}
$datesInsc = $_POST['dateInscri'];
$datenaiss = $_POST['datenaiss'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
function generateRandomString($length = 50) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

$chaineNotif = generateRandomString();
$chaineMessage = generateRandomString();
if(isset($_POST['password'])) {
    $password = $_POST['password'];
}
if(isset($_POST['image'])) {
    $photo = $_POST['image'];
}
$nom =  $_POST['nom'];
$genre =  $_POST['genre'];

$password = SHA1($password);
 if(isset($_POST['email'])) {
    $p = $db->query("SELECT *FROM utilisateur WHERE email = '$email'");
}

$k = $p->fetch(PDO::FETCH_OBJ);
$p = $p->rowcount();
if ($p<=0) {
    $q = $db->prepare("INSERT INTO utilisateur SET nom=:nom, passwords =:pass, dateInscri =:creates, confirm=:conf,email=:email, images=:photo, `insc_type`=:types, chaine_notif=:chainenotif, numero=:numero, genre=:genre, datenaiss=:dateNaiss, adresse=:adresse, latitude=:latitude, longitude=:longitude");

    $q->bindValue(':nom', $nom);
    $q->bindValue(':genre', $genre);
    $q->bindValue(':dateNaiss', $datenaiss);
    $q->bindValue(':email', $email);
    $q->bindValue(':adresse', $adresse);
    $q->bindValue(':pass', $password);
    $q->bindValue(':photo', $photo);
    $q->bindValue(':creates', $datesInsc);
    $q->bindValue(':types', $type);
    $q->bindValue(':chainenotif', $chaineNotif);
    $q->bindValue(':conf', $conf);
    $q->bindValue(':numero', $numero);
    $q->bindValue(':latitude', $latitude);
    $q->bindValue(':longitude', $longitude);
    $q->execute();
    echo json_encode(['nom'=> $nom, 'genre'=> $genre, 'dateNaiss'=>$datenaiss, 'email'=> $email, 'adresse'=> $adresse, 'password'=> $password, 'photo'=> $photo, 'dateInsc'=> $datesInsc, 'type'=> $type, 'chainenotif'=> $chaineNotif, 'conf'=> $conf, 'numero'=> $numero]);
} else {
        echo json_encode(['type'=> $type,'response'=> 'Cette personne existe deja. ', 'couleur'=> 'danger', 'tab'=> $k]);
}

?>