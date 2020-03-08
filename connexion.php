<?php
    try {
        $db = new PDO('mysql: host=localhost;dbname=soulmate', "root", "");
            } catch(Exception $e) {
                $db = new PDO('mysql: host=localhost;dbname=soulmate', "anthony", "Moise@2020");
    }
?>