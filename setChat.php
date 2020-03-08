<?php
require('connexion.php');
require ('vendor-pusher/autoload.php');
$options = array(
  'cluster' => 'eu',
  'encrypted' => true
);
$pusher = new Pusher\Pusher(
  'cd29f2f1d7ed1ce9bd9c',
  '146eaba15c41a1c1afd9',
  '926631',
  $options
);
if(isset($_POST['message'])) {
    $chaine = $_POST['chaine'];
    $idExp=  $_POST['idExp'];
    $idRecep =  $_POST['idRecep'];
    $message = $_POST['message'];
    $dates = $_POST['dates'];
    $nom = $_POST['nom'];
    $photo = $_POST['photo'];
    $chaineRecep = $_POST['chaineRecep'];
    $dateMoment = $_POST['dateMoment'];
    //Prepare les chats
    $e=$db->prepare("INSERT INTO chat SET id_exp=:exps, id_dest=:desti, dates=:dates, messages=:messages,etat=:etat, chaine=:chaine, photo=:photo, nom=:nom");
    // Prepare la  notif
    $messager= $nom." Vous a ecrit sur le chat. Clic pour voir le message";
    $i = $db->prepare('INSERT INTO notifications SET id_exp=:id_exp, id_desti=:desti, etat=:etat,messages=:messages, nom_exp=:nom_exp, createdAt=:dates, types=:types');

    // Binding de la notif
    $i->bindValue(':id_exp', $idExp);
    $i->bindValue(':desti', $idRecep);
    $i->bindValue(':etat', 0);
    $i->bindValue(':messages', $messager);
    $i->bindValue(':nom_exp', $nom);
    $i->bindValue(':dates', $dateMoment);
    $i->bindValue(':types', 'chat');
    // Binding des chats
    $e->bindValue(':exps', $idExp);
    $e->bindValue(':desti', $idRecep);
    $e->bindValue(':dates', $dates);
    $e->bindValue(':messages', $message);
    $e->bindValue(':etat', 0);
    $e->bindValue(':chaine', $chaine);
    $e->bindValue(':photo', $photo);
    $e->bindValue(':nom', $nom);
    $data = [
        'messages'=> $message,
        'dates'=> $dates,
        'nom'=> $nom,
        'photo'=> $photo,
        'id_exp'=>$idExp
    ];
    $e->execute();
    $i->execute();
    //il faut ajouter 1 chaine pusher 2 (celui du recepteur afin de binder la cloche des notifs)
    $pusher->trigger($chaine, 'my-event', $data);
    // bindNotification($idRecep, $chaineRecep);
}
?>