<?php

session_start();
require("connection.php");
$error="";
if(isset($_POST['login'])){
    $sql="SELECT * from users where name=:name and password=:password";
    $statement=$pdo->prepare($sql);
    $name=$_POST['name'];
    $password=$_POST['password'];

    $statement->bindParam(":name",$name,PDO::PARAM_STR);
    $statement->bindParam(":password",$password,PDO::PARAM_STR);
    $statement->execute();
    $count=$statement->rowCount();
    if($count==1){
        $_SESSION['privilleged']=$name;
        header("location:todoListPage.php");
    }else{
        $error="Invalid Username or Password";
    }
    $pdo=null;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="description" content="Login to your Katkooti account to access, track, and manage your baby's daily activities, tasks, and development milestones.">
    <meta name="keywords" content="Katkooti, baby tracker, login, daily activities, baby milestones">
    <title>Login to Katkooti</title>
</head>

<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Login</h1>
            <div class="inputBox">
                <input type="text" name="name" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="inputBox">
                <input type="password" name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <?php if(!empty($error)): ?>
                <div style="
                            border: 2px dashed #cc3857;
                            border-radius: 15px;
                            padding: 5px 10px;
                            margin: 10px 0;
                            text-align: center;
                            font-size: 15px;
                            color: white;"><?php echo $error; ?></div>
            <?php endif; ?>
            <button class="button" name="login" type="submit">Login</button>
            <div class="register">
                <p>Don't have an account? <a href="register.php">Register Now</a></p>
            </div>
        </form>
    </div>
    <br>
    <!-- Footer -->
    <footer>
     <p> By Hala Habboub. Katkooti 2023. All rights reserved.</p>
    </footer>
</body>

</html>

