<?php
require("connection.php");
session_start();
if(!isset($_SESSION['privilleged'])){
   header("location:loginPage.php");
}
$name=$_SESSION['privilleged'];
if(isset($_POST['add'])){
    $sql="INSERT INTO todolist(name,task,date,taskfor) values (:name,:task,:date,:taskfor)";

    $statement=$pdo->prepare($sql);
    $name=$_POST['name'];
    $date=$_POST['date'];
    $task=$_POST['task'];
    $taskfor=$_POST['taskfor'];
    $statement->bindParam(":name",$name,PDO::PARAM_STR);
    $statement->bindParam(":date",$date,PDO::PARAM_STR);
    $statement->bindParam(":task",$task,PDO::PARAM_STR);
    $statement->bindParam(":taskfor",$taskfor,PDO::PARAM_STR);
    $statement->execute();
}
// fetching the tasks of the user with the current session
$tasks = [];
$query = "SELECT * FROM todolist WHERE name = :name ORDER BY date ASC";
$statement = $pdo->prepare($query);
$statement->bindParam(":name", $name, PDO::PARAM_STR);
$statement->execute();
$tasks = $statement->fetchAll(PDO::FETCH_ASSOC);

$pdo=null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet"  href="todoListstyle.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="description" content="Organize tasks related to your baby's and your needs. Prioritize, schedule, and manage tasks effectively on Katkooti.">
    <meta name="keywords" content="Katkooti, task management, to-do list, baby tasks, parent tasks">
    <title>To-Do List</title>
</head>

<body>
    <div class="sidebar">
        <img src="Pictures/chick2Nobckg.png"  alt="Katkooti">
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
            <a href="trackerPage.php">Tracker</a>
            <a href="todoListPage.php" class="active">To-do list</a>
            <a href="developmentPage.php">Development</a>
        </div>
        <div class="theBody">
            <form action="" method="POST">
                <h1>Let's Add Some Tasks!</h1>
                <section class="task-entry"> <!-- A section for task entry -->
                    <p class="whatTask">What is my task?</p>
                    <input type="text" class="task" name="task" id="task" placeholder="ex: Buy sunblock" required>
                    <p class="whenTask">When is it due?</p>
                    <input type="date" id="date" name="date" class="date" required>
                </section>

                <section class="task-assignee"> <!-- A section for selecting who the task is for -->
                    <p class="whoTask">Who is this task for?</p>
                    <div class="mommybaby">
                        <input type="radio" id="Mommy" name="taskfor" value="Mommy">
                        <label for="Mommy">Mommy</label>
                    </div>
                    <div class="mommybaby">
                        <input type="radio" id="Baby" name="taskfor" value="Baby">
                        <label for="Baby">Baby</label>
                    </div>
                </section>

                <input type="hidden" name="name" value="<?php echo $name ?>" required>

                <div class="adding">
                    <button class="Add" name="add">Add to List</button>
                </div>
            </form>

            <div class="theList">
                <h1><?php echo $name ?>'s To-Do List</h1>
                <?php foreach($tasks as $task){ ?>
                    <div class="taskAdded">
                        <input type="checkbox" class="check-todo">
                        <h2 class="taskName"><?php echo $task['task']; ?></h2>
                        <p class="taskFor">Task for: <?php echo $task['taskfor']; ?></p>
                        <p class="taskDueDate"><?php echo $task['date']; ?></p>
                        <div class="editDelete">
                            <a href="deleteTask.php?id=<?php echo $task['id']; ?>"><i class='bx bxs-message-square-x'></i></a>
                            <a href="editTask.php?id=<?php echo $task['id']; ?>"><i class='bx bxs-edit-alt'></i></a>
                        </div>
                        

                        
                    </div>
                <?php }; ?>
            </div>

        </div>
    </div>
    <br>
    <!-- Footer -->
    <footer style="font-size: 15px; text-align: center; padding: 20px 0;  backdrop-filter: blur(30px);
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    border-radius: 20px;">
     <p> By Hala Habboub. Katkooti 2023. All rights reserved.</p>
    </footer>

</body>

</html>