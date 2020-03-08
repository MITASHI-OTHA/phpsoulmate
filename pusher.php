<?php
require ('vendor/autoload.php');
//require ('connexion.php');
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
if(isset($_POST['data'])) {
    $data['message'] = $_POST['data'];
    $pusher->trigger('my-channel', 'my-event', $data);
}
    



?>