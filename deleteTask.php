<?php
    require 'connection.php';
    $sql="DELETE FROM todoList WHERE id=:id";

    $statement=$pdo->prepare($sql);
    $id=$_GET['id'];
    $statement->bindParam(":id",$id, PDO::PARAM_STR);
    $statement->execute();
    $pdo=null;

    header("location:todoListPage.php");
?>