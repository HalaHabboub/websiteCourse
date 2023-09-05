<?php
require("connection.php");


 try{
    if($_SERVER['REQUEST_METHOD']=='POST'){
    $sql="INSERT INTO users (name,email,password) values (:name,:email,:password)";
    $statement=$pdo->prepare($sql);
    $name=$_POST['name'];
    $email=$_POST['email'];
    $password=$_POST['password'];

    $statement->bindParam(":name",$name,PDO::PARAM_STR);
    $statement->bindParam(":email",$email,PDO::PARAM_STR);
    $statement->bindParam(":password",$password,PDO::PARAM_STR);
    $statement->execute();
    $userCreatedMessage= 'User Created Successfully! Go to the login page ';

}
 }

//I specified in the db users table that the email and username MUST be unique
catch (PDOException $e) {
    $errorMessage = "The email or username you entered is already registered. Please use a different email.";
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="registerstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="description" content="Register on Katkooti to start tracking and organizing your baby's activities, milestones, and tasks. Join our community of dedicated caretakers.">
    <meta name="keywords" content="Katkooti, baby tracker, register, baby milestones, caretaker, parent platform">
    <title>Register</title>
</head>

<body>
    <div class="wrapper">
        <form action="" method="POST">
            <h1>Register</h1>
            <div class="inputBox">
                <input type="text" name="name" placeholder="Username" required>
                <i class='bx bxs-user'></i>
            </div>
            <div class="inputBox">
                <input type="email" name="email" placeholder="Email" required>
                <i class='bx bxs-envelope'></i>
            </div>
            <div class="inputBox">
                <input type="password"  name="password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            <button class="button" name="register" type="submit">Register</button>
            <div class="register">
            <!-- The Error Message: -->
            <?php if (!empty($errorMessage)) : ?>
                    <div style="
                            border: 2px solid #cc3857;
                            background-color:#cc3857;
                            border-radius: 15px;
                            padding: 5px 10px;
                            margin: 10px 0;
                            text-align: center;
                            width:100%;
                            font-size: 15px;
                            color: white;"><?php echo $errorMessage; ?> </div>
               <?php endif; ?>
            <!-- User created successfully message -->
            <?php if(!empty($userCreatedMessage)): ?>
                <div style="
                            border: 2px solid #cc3857;
                            background-color:#cc3857;
                            border-radius: 15px;
                            padding: 5px 10px;
                            margin: 10px 0;
                            text-align: center;
                            width:100%;
                            font-size: 15px;
                            color: white;"><?php echo $userCreatedMessage; ?></div>
            <?php endif; ?>
            
                <p>Already have an account? <a href="index.php">Login Now</a></p>
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