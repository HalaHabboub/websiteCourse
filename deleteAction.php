<?php
//this specific deleting file is made to adapt to all tables (nap,wash,diaper,milk,food) instead of making 5 files

require 'connection.php';

//sent in the URL
$source = $_GET['source'];
$id = $_GET['id'];

switch($source) {
    //if the source is food save this values so i can use them when deleting
    case 'food':
        $table = "food";
        $idColumn = "idFood";
        break;
    case 'milk':
        $table = "milk";
        $idColumn = "idMilk";
        break;
    case 'nap':
        $table = "nap";
        $idColumn = "idNap";
        break;
    case 'wash':
        $table = "wash";
        $idColumn = "idWash";
        break;
    case 'diaper':
        $table = "diaper";
        $idColumn = "idDiaper";
        break;
    default:
        // handle invalid source
        die("Invalid source.");
}

$sql = "DELETE FROM $table WHERE $idColumn = :id";

$statement = $pdo->prepare($sql);
$statement->bindParam(":id", $id, PDO::PARAM_INT);
$statement->execute();

$pdo = null;

header("location:trackerPage.php");
?>