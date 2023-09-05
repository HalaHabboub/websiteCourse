<?php
require("connection.php");
session_start();
if(!isset($_SESSION['privilleged'])){
   header("location:loginPage.php");
}
$name=$_SESSION['privilleged'];
if(isset($_POST['add'])){
    $sql="INSERT INTO development(name,head,height,weight,date) values (:name,:head,:height,:weight,:date)";

    $statement=$pdo->prepare($sql);
    $head=$_POST['head'];
    $height=$_POST['height'];
    $weight=$_POST['weight'];
    $date=$_POST['date'];
    $statement->bindParam(":name",$name,PDO::PARAM_STR);
    $statement->bindParam(":head",$head,PDO::PARAM_STR);
    $statement->bindParam(":height",$height,PDO::PARAM_STR);
    $statement->bindParam(":weight",$weight,PDO::PARAM_STR);
    $statement->bindParam(":date",$date,PDO::PARAM_STR);

    $statement->execute();
}
// fetching the tasks of the user with the current session
$values = [];
$query = "SELECT * FROM development WHERE name = :name ORDER BY date DESC";
$statement = $pdo->prepare($query);
$statement->bindParam(":name", $name, PDO::PARAM_STR);
$statement->execute();
$values = $statement->fetchAll(PDO::FETCH_ASSOC);

$pdo=null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="developmentstyle.css">
    <link href="/your-path-to-uicons/css/uicons-[your-style].css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <meta name="description" content="Monitor your baby's growth and development on Katkooti. Track height, weight, and head circumference over time and celebrate milestones.">
    <meta name="keywords" content="Katkooti, baby development, growth tracker, baby milestones, height, weight, head circumference">
    <title>Development</title>
</head>

<body>
    <div class="sidebar">
        <img src="Pictures/chick2Nobckg.png" alt="Katkooti">
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
            <a href="todoListPage.php">To-do list</a>
            <a href="developmentPage.php" class="active">Development</a>
        </div>
        <div class="theBody">
            <form action="" method="POST">
                <h1>Let's see how much we've grown!!</h1>
                <section class="development-entry"> <!-- A section for task entry -->
                    <p class="developmentLabel">Head Circumference</p>
                    <input type="num" class="developmentData" name="head"  placeholder="circumference" required>
                    <p class="developmentLabel">Height</p>
                    <input type="num" class="developmentData" name="height"  placeholder="height" required>
                    <p class="developmentLabel">Weight</p>
                    <input type="num" class="developmentData" name="weight"  placeholder="weight" required>
                    <p class="developmentLabel">When was this measurement taken?</p>
                    <input type="date" id="date" name="date" class="developmentData" required>
                </section>

                </section>

                <input type="hidden" name="name" value="<?php echo $name ?>" required>

                <div class="adding">
                    <button class="Add" name="add">Add to history</button>
                </div>
            </form>

            <div class="theList">
                <h1><?php echo $name ?>'s baby Growth!</h1>
                <?php foreach($values as $value){ ?>
                    <div class="dataAdded">
                        <h2><?php echo "On " . $value['date'] ?></h2>
                        <p class="data"><?php echo "Head size: ". $value['head']; ?></p>
                        <p class="data">Task for: <?php echo "Height: ". $value['height']; ?></p>
                        <p class="data"><?php echo "Weight: ". $value['weight']; ?></p>
                        <div class="editDelete">
                            <a href="deleteValue.php?id=<?php echo $value['id']; ?>"><i class='bx bxs-message-square-x'></i></a>
                        </div>
                    </div>
                <?php }; ?>
            </div>

        </div>
    </div>
    <br>
    <!-- Footer -->
    <footer>
     <p> By Hala Habboub. Katkooti 2023. All rights reserved.</p>
    </footer>

    

</body>

</html>