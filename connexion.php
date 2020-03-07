<?php
    try {
        $db = new PDO('mysql: host=localhost;dbname=soulmate', "root", "");
            } catch(Exception $e) {
                $db = new PDO('mysql: host=localhost;dbname=soulmate', "Mitashi", "Moise@2020");
    }
?>