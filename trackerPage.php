<?php
require("connection.php");
session_start();
if(!isset($_SESSION['privilleged'])){
   header("location:loginPage.php");
}
$name=$_SESSION['privilleged'];


//add food
if(isset($_POST['addfood'])){
    $sql="INSERT INTO food(name,datetime,foodtype) values (:name,:datetime,:foodtype)";

    $statement=$pdo->prepare($sql);
    $uname=$name;
    $datetime=$_POST['datetime'];
    $foodtype=$_POST['foodtype'];

    $statement->bindParam(":name",$uname,PDO::PARAM_STR);
    $statement->bindParam(":datetime",$datetime,PDO::PARAM_STR);
    $statement->bindParam(":foodtype",$foodtype,PDO::PARAM_STR);

    $statement->execute();
}
//add milk
if(isset($_POST['addmilk'])){
    $sql="INSERT INTO milk(name,datetime,milktype,amount) values (:name,:datetime,:milktype,:amount)";

    $statement=$pdo->prepare($sql);
    $uname=$name;
    $datetime=$_POST['datetime'];
    $milktype=$_POST['milktype'];
    $amount=$_POST['amount'];

    $statement->bindParam(":name",$uname,PDO::PARAM_STR);
    $statement->bindParam(":datetime",$datetime,PDO::PARAM_STR);
    $statement->bindParam(":milktype",$milktype,PDO::PARAM_STR);
    $statement->bindParam(":amount",$amount,PDO::PARAM_INT);
    $statement->execute();
}
//add nap
if(isset($_POST['addnap'])){
    $sql="INSERT INTO nap(name,datetime,duration) values (:name,:datetime,:duration)";

    $statement=$pdo->prepare($sql);
    $uname=$name;
    $datetime=$_POST['datetime'];
    $duration=$_POST['duration'];

    $statement->bindParam(":name",$uname,PDO::PARAM_STR);
    $statement->bindParam(":datetime",$datetime,PDO::PARAM_STR);
    $statement->bindParam(":duration",$duration,PDO::PARAM_INT);
    $statement->execute();
}
//add wash
if(isset($_POST['addwash'])){
    $sql="INSERT INTO wash(name,datetime) values (:name,:datetime)";

    $statement=$pdo->prepare($sql);
    $uname=$name;
    $datetime=$_POST['datetime'];

    $statement->bindParam(":name",$uname,PDO::PARAM_STR);
    $statement->bindParam(":datetime",$datetime,PDO::PARAM_STR);
    $statement->execute();
}
//add diaper
if(isset($_POST['adddiaper'])){
    $dirty = isset($_POST['dirty']) ? 1 : 0;
    $wet = isset($_POST['wet']) ? 1 : 0;

    $sql="INSERT INTO diaper(name,datetime,dirty,wet) values (:name,:datetime,:dirty,:wet)";

    $statement=$pdo->prepare($sql);
    $uname=$name;
    $datetime=$_POST['datetime'];


    $statement->bindParam(":name",$uname,PDO::PARAM_STR);
    $statement->bindParam(":datetime",$datetime,PDO::PARAM_STR);
    $statement->bindParam(":dirty",$dirty,PDO::PARAM_INT);
    $statement->bindParam(":wet",$wet,PDO::PARAM_INT);
    $statement->execute();
}

//_________________________________________________________________________________________________________________________
//displaying the tracked actions

date_default_timezone_set("Asia/Amman");
$inputDate = date('Y-m-d');

if(isset($_GET['track'])){
    $inputDate = $_GET['date'];
}




//fetching from food table
$queryFood = "SELECT 'food' as source, datetime, foodtype, idFood FROM food WHERE DATE(datetime) = :inputDate AND name = :name";
$statementFood = $pdo->prepare($queryFood);
$statementFood->bindParam(":inputDate", $inputDate, PDO::PARAM_STR);
$statementFood->bindParam(":name", $name, PDO::PARAM_STR);
$statementFood->execute();
$foodActions = $statementFood->fetchAll(PDO::FETCH_ASSOC);

//fetching from milk table
$queryMilk = "SELECT 'milk' as source, datetime, milktype, amount, idMilk FROM milk WHERE DATE(datetime) = :inputDate AND name = :name";
$statementMilk = $pdo->prepare($queryMilk);
$statementMilk->bindParam(":inputDate", $inputDate, PDO::PARAM_STR);
$statementMilk->bindParam(":name", $name, PDO::PARAM_STR);
$statementMilk->execute();
$milkActions = $statementMilk->fetchAll(PDO::FETCH_ASSOC);

//fetching from nap table
$queryNap = "SELECT 'nap' as source, datetime, duration, idNap FROM nap WHERE DATE(datetime) = :inputDate AND name = :name";
$statementNap = $pdo->prepare($queryNap);
$statementNap->bindParam(":inputDate", $inputDate, PDO::PARAM_STR);
$statementNap->bindParam(":name", $name, PDO::PARAM_STR);
$statementNap->execute();
$napActions = $statementNap->fetchAll(PDO::FETCH_ASSOC);

//fetching from wash table
$queryWash = "SELECT 'wash' as source, datetime, idWash FROM wash WHERE DATE(datetime) = :inputDate AND name = :name";
$statementWash = $pdo->prepare($queryWash);
$statementWash->bindParam(":inputDate", $inputDate, PDO::PARAM_STR);
$statementWash->bindParam(":name", $name, PDO::PARAM_STR);
$statementWash->execute();
$washActions = $statementWash->fetchAll(PDO::FETCH_ASSOC);

//fetching from diaper table
$queryDiaper = "SELECT 'diaper' as source, datetime, dirty, wet, idDiaper FROM diaper WHERE DATE(datetime) = :inputDate AND name = :name";
$statementDiaper = $pdo->prepare($queryDiaper);
$statementDiaper->bindParam(":inputDate", $inputDate, PDO::PARAM_STR);
$statementDiaper->bindParam(":name", $name, PDO::PARAM_STR);
$statementDiaper->execute();
$diaperActions = $statementDiaper->fetchAll(PDO::FETCH_ASSOC);

//merging all data fetched
$allActions = array_merge($foodActions, $milkActions, $napActions, $washActions, $diaperActions);

/*
 * The `usort` function is used to sort the $allActions array. The sorting is based on the 'datetime'.
 * Inside the function, 'datetime' values are converted to UNIX timestamps using `strtotime`.
 * The sort order is in ascending order, meaning earlier dates/times come before later ones in the sorted array.
 * If the returned value is:
 *      A negative number if $a['datetime'] is earlier than $b['datetime']
        Zero if $a['datetime'] and $b['datetime'] are the same
        A positive number if $a['datetime'] is later than $b['datetime']
 */
usort($allActions, function($a, $b) {
    return strtotime($a['datetime']) - strtotime($b['datetime']);
});



$pdo=null;



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="trackStyle.css">
    <link href="/your-path-to-uicons/css/uicons-[your-style].css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="//glenthemes.github.io/iconsax/geticons.js"></script>
    <link href="//glenthemes.github.io/iconsax/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <meta name="description" content="Log daily baby activities on Katkooti, from feeding times to diaper changes. Review past activities and understand your baby's routine.">
    <meta name="keywords" content="Katkooti, activity tracker, baby routine, feeding, diaper changes, daily activities">
    <script src="https://kit.fontawesome.com/db17a600ee.js" crossorigin="anonymous"></script>


    <title>Tracker</title>

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
            <a href="trackerPage.php"class="active">Tracker</a>
            <a href="todoListPage.php" >To-do list</a>
            <a href="developmentPage.php">Development</a>
        </div>
        <div class="theBody">
            <h1>What did my baby do today?</h1>
            <div class="actionOptions">
                <div class="Action">
                    <div class="ActionHeader" onclick="toggleAction(this)">
                        <h2 class="actionName">Food</h2>
                        <i class="fa-solid fa-utensils"></i>
                    </div>

                </div>
                <div class="Action">
                    <div class="ActionHeader" onclick="toggleAction(this)">
                        <h2 class="actionName">Milk</h2>
                        <i class="fa-solid fa-cow"></i>
                    </div>
                </div>
                <div class="Action">
                    <div class="ActionHeader" onclick="toggleAction(this)">
                        <h2 class="actionName">Nap</h2>
                        <i class="fas fa-bed"></i>
                    </div>
                </div>
                <div class="Action">
                    <div class="ActionHeader" onclick="toggleAction(this)">
                        <h2 class="actionName">Wash</h2>
                        <i class="fa-solid fa-bath"></i>
                    </div>
                </div>
                <div class="Action">
                    <div class="ActionHeader" onclick="toggleAction(this)">
                        <h2 class="actionName">Diaper</h2>
                        <i class="fa-solid fa-poop"></i>
                    </div>
                </div>

                <div class="ActionContent" id="foodContent">
                    <h1>Food</h1>
                    <form action="" method="POST">
                        <label for="datetime">Date & Time</label>
                        <input  name="datetime" type="datetime-local" id="datetime" class="datetime">
                        <br>
                        <label for="foodType">Food Type</label>
                        <input name=foodtype type="text" id="foodType" class="foodType">
                        <button name="addfood" class="AddTracker">Add to Tracker</button>
                    </form>
                </div>

                <div class="ActionContent" id="milkContent">
                    <h1>Milk</h1>
                    <form action="" method="POST">
                        <label for="datetime">Date & Time</label>
                        <input  name="datetime" type="datetime-local" id="datetime" class="datetime">
                        <br>
                        <div class="milkType">
                            <p>Milk Type</p>
                            <input type="radio" id="Formula" name="milktype" value="formula" class="Formula">
                            <label for="Formula">Formula</label>

                            <input type="radio" id="breastMilk" name="milktype" value="breast" class="breast">
                            <label for="breastMilk">Breast Milk</label>
                        </div>
                        <br>
                        <label for="amount">Milk Amount</label>
                        <input type="number" name="amount"class="amount" id="amount" placeholder="Milk Amount in ml">

                        <button name="addmilk" class="AddTracker">Add to Tracker</button>
                    </form>
                </div>

                <div class="ActionContent" id="napContent">
                    <h1>Nap</h1>
                    <form action="" method="POST">
                        <label for="datetime">Date & Time</label>
                        <input  name="datetime" type="datetime-local" id="datetime" class="datetime">
                        <br>
                        <label for="duration">Duration</label>
                        <input type="number" name="duration" id="duration" class="duration" placeholder="time in hours">
                        <button name="addnap" class="AddTracker">Add to Tracker</button>
                    </form>
                </div>

                <div class="ActionContent" id="washContent">
                    <h1>Wash</h1>
                    <form action="" method="POST">
                        <label for="datetime">Date & Time</label>
                        <input  name="datetime" type="datetime-local" id="datetime" class="datetime">
                        <button name="addwash" class="AddTracker">Add to Tracker</button>
                    </form>
                </div>

                <div class="ActionContent" id="diaperContent">
                    <h1>Diaper</h1>
                    <form action="" method="POST">
                        <label for="datetime">Date & Time</label>
                        <input  name="datetime" type="datetime-local" id="datetime" class="datetime">
                        <br>
                        <div class="diaperType">
                            <input type="checkbox" id="Dirty" name="dirty" value="Dirty" class="Dirty" <?php= $row['dirty'] == 1 ? 'checked' : ''; ?>>
                            <label for="Dirty">Dirty</label>

                            <input type="checkbox" id="Wet" name="wet" value="Wet" class="Wet" <?php= $row['wet'] == 1 ? 'checked' : ''; ?>>
                            <label for="Wet">Wet</label>
                        </div>

                        <button name="adddiaper" class="AddTracker">Add to Tracker</button>
                    </form>
                </div>
            </div>

        </div>


    </div>

    <script>
        function toggleAction(header) {
            // Get the name of the action from the header
            const actionName = header.querySelector('.actionName').textContent.toLowerCase();

            // Find the corresponding ActionContent using the action name
            const content = document.querySelector(`#${actionName}Content`);
            // Check if this ActionContent is already active
            if (content.classList.contains('active')) {
                content.classList.remove('active');
            } else {
                // First close all active ActionContents
                const activeContents = document.querySelectorAll('.ActionContent.active');
                activeContents.forEach(activeContent => {
                    activeContent.classList.remove('active');
                });

                // Then activate the clicked ActionContent
                content.classList.add('active');
            }
        }

    </script>

    <br>
  <div class="mainContainer">
        <div class="theBody">

            <h1>Day-to-Day Tracker</h1>

            <form action="" method="get">
                <label for="date">Date:</label>
                <input name="date" type="date" id="date" class="date">
                <button  name="track" id="track" class="track">Track !</button>
            </form>

            


            <div class="actionsList">
    <h1>Actions List</h1>
    <?php foreach($allActions as $action) { ?>
        <div class="actionItem">
            <h2 class="actionSource">Action: <?php echo ucfirst($action['source']); ?> </h2>
            <p class="actionDateTime">DateTime: <?php echo $action['datetime']; ?> </p>
            <p class="actionDescription">
                <?php 
                    switch($action['source']) {
                        case 'food':
                            if(isset($action['foodtype'])){
                                echo "Food Type: " . $action['foodtype'];
                            }
                            echo '<a href="deleteAction.php?source=food&id=' . $action['idFood'] . '"><i class="bx bxs-message-square-x"></i></a>';
                            //sending the source = food and the id is the idFood of this action to the delete action list throught GET
                            break;
                        case 'milk':
                            if(isset($action['milktype'])){
                                echo "Milk Type: " . $action['milktype'] . " (" . $action['amount'] . " ml)";
                            }
                            echo '<a href="deleteAction.php?source=milk&id=' . $action['idMilk'] . '"><i class="bx bxs-message-square-x"></i></a>';
                            break;
                        case 'nap':
                            if(isset($action['duration'])){
                            echo  "Nap Duration: " . $action['duration'] . " hours" ;
                            }
                            echo '<a href="deleteAction.php?source=nap&id=' . $action['idNap'] . '"><i class="bx bxs-message-square-x"></i></a>';
                            break;
                        case 'wash':
                            echo '<a href="deleteAction.php?source=wash&id=' . $action['idWash'] . '"><i class="bx bxs-message-square-x"></i></a>';
                            break;
                        case 'diaper':
                            $desc = [];
                            if(isset($action['dirty']) && $action['dirty']) {
                                $desc[] = 'Dirty';
                            }
                            if(isset($action['wet']) && $action['wet']) {
                                $desc[] = 'Wet';
                            }
                            if(!empty($desc))  echo implode(", ", $desc) ;
                // implode() is a PHP function that takes all the elements of an array and joins (or "implodes") them into a single string.
                            echo '<a href="deleteAction.php?source=diaper&id=' . $action['idDiaper'] . '"><i class="bx bxs-message-square-x"></i></a>';
                            break;
                        default:
                            echo "Not available";
                    }
                ?>
            </p>
        </div>
    <?php } ?>
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

</html>