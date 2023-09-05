<?php
    $host="localhost";
    $dbName="katkooti";
    $user="root";
    $password="";


// $host='192.168.0.100';
// $dbName='katkooti_katkooti';
// $user='katkooti_katkooti';
// $password='Katkooti123';
try {
   $pdo = new PDO("mysql:host=$host;dbname=$dbName", $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
//to allow errors to pass
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>
