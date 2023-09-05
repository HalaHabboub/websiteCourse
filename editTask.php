<?php
require("connection.php");
session_start();
if(!isset($_SESSION['privilleged'])){
   header("location:loginPage.php");
}
$name=$_SESSION['privilleged'];
$id= $_GET['id'];

//when i click the edit button
if(isset($_POST['edit'])){
    $sql = "UPDATE todolist SET task=:task, date=:date, taskfor=:taskfor where id=$id";
    $statement = $pdo->prepare($sql);
    $task = $_POST['task'];
    $date = $_POST['date'];
    $taskfor=$_POST['taskfor'];
    $statement->bindParam(':task',$task, PDO::PARAM_STR);
    $statement->bindParam(':taskfor',$taskfor, PDO::PARAM_STR);
    $statement->bindParam(':date',$date, PDO::PARAM_STR);
    $statement->execute();

    $taskEditMessage="Task Edited Successfully";
}


$sql="SELECT * FROM todolist where id=$id";
$statement=$pdo->prepare($sql);
$statement->execute();
$task=$statement->fetchAll();

foreach($task as $detail){ 
    $task=$detail['task'];
    $taskfor=$detail['taskfor'];
    $date=$detail['date'];
};
$pdo=null;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="editstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="description" content="Edit and update tasks on Katkooti. Make adjustments to your to-do list, ensuring you stay on top of your baby's and your needs.">
    <meta name="keywords" content="Katkooti, edit task, update task, to-do list adjustments, baby tasks">
    <title>To-Do List Task Edit</title>
</head>

<body>
    <div class="sidebar">
        <img src="Pictures/chick2Nobckg.png">
        <ul class="list">
            <li><a href="logoutPage.php">LogOut</a></li>
        </ul>
    </div>
    <div class="mainContainer">
        <div class="dashboard">
            <p>Welcome back, <?php echo $name ?>!</p>
            <h1>Dashboard</h1>
        </div>
        <div class="dashboardNav">
            <a href="todoListPage.php" class="active">Back to my to-do list</a>
        </div>
        <div class=theBody>
            <form action="" method="post">
            <label> Task</label>
            <input type="text" class="task" name="task" value="<?php echo $task?> " required>

            <label> Due date</label>
            <input type="date" class="date" name="date" value="<?php echo $date?>" required>

            <label> Task For</label>
            
            <!-- the thing before the question mark is a condition 
                the first option happens if its true 
                the second one (after the :) happens if its false -->
            <select name="taskfor" class="taskforEdit">
                <option  class="option" value="Baby" <?php echo ($taskfor == 'Baby') ? 'selected' : ''; ?>>Baby</option>
                <option  class="option" value="Mommy" <?php echo ($taskfor == 'Mommy') ? 'selected' : ''; ?>>Mommy</option>
            </select>

            <input type="submit" class="editButton" name="edit" value="Edit Task">
            </form>

             <?php if(!empty($taskEditMessage)): ?>
                <div style="
                            border: 2px dashed #cc3857;
                            color:#cc3857;
                            border-radius: 15px;
                            padding: 5px 10px;
                            margin: 10px 0;
                            text-align: center;
                            width:fit-content
                            font-size: 15px;"><?php echo $taskEditMessage; ?></div>
            <?php endif; ?>
        </div>
    </div>
       
    </div>
    <!-- Footer -->
    <footer style="font-size: 15px; text-align: center; padding: 20px 0;  backdrop-filter: blur(30px);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    border-radius: 20px;">
     <p> By Hala Habboub. Katkooti 2023. All rights reserved.</p>
    </footer>

</body>
<html>