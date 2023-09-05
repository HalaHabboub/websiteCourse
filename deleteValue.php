<?php
    require 'connection.php';
    $sql="DELETE FROM development WHERE id=:id";

    $statement=$pdo->prepare($sql);
    $id=$_GET['id'];
    $statement->bindParam(":id",$id, PDO::PARAM_STR);
    $statement->execute();
    $pdo=null;

    header("location:developmentPage.php");
?>