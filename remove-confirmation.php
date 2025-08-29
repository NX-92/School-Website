<?php
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'school_system';
    
    $connection = mysqli_connect($server, $user, $password, $database);
    if (!$connection) {
        die('Connection Failed! ' . mysqli_connect_error());
    }

    $query = "SELECT * FROM module";
    $moduleQuizResult = mysqli_query($connection, $query);
    $moduleMaterialResult = mysqli_query($connection, $query);

    if (!$moduleQuizResult) {
    die('Error retrieving event data: ' . mysqli_error($connection));
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['message'])) {
        $message = mysqli_real_escape_string($connection, $_POST['message']);
    
        $query = "DELETE FROM attempt WHERE Quiz_ID = '$message'";
        mysqli_query($connection, $query);
    
        $query = "DELETE FROM question WHERE Quiz_ID = '$message'";
        mysqli_query($connection, $query);
    
        $query = "DELETE FROM quiz WHERE Quiz_ID = '$message'";
        mysqli_query($connection, $query);
    
        mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=0");
    
        $query = "SELECT Attempt_ID FROM attempt ORDER BY Attempt_ID ASC";
        $result = mysqli_query($connection, $query);
    
        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $old_attempt_id = $row['Attempt_ID'];
            $new_attempt_id = 'A' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    
            $update_query = "UPDATE attempt SET Attempt_ID = '$new_attempt_id' WHERE Attempt_ID = '$old_attempt_id'";
            mysqli_query($connection, $update_query);
    
            $counter++;
        }
    
        $query = "SELECT Question_ID FROM question ORDER BY Question_ID ASC";
        $result = mysqli_query($connection, $query);
    
        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $old_question_id = $row['Question_ID'];
            $new_question_id = 'QS' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    
            $update_query = "UPDATE question SET Question_ID = '$new_question_id' WHERE Question_ID = '$old_question_id'";
            mysqli_query($connection, $update_query);
    
            $counter++;
        }
    
        $query = "SELECT Quiz_ID FROM quiz ORDER BY Quiz_ID ASC";
        $result = mysqli_query($connection, $query);
    
        $counter = 1;
        while ($row = mysqli_fetch_assoc($result)) {
            $old_quiz_id = $row['Quiz_ID'];
            $new_quiz_id = 'Q' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    
            $update_query = "UPDATE quiz SET Quiz_ID = '$new_quiz_id' WHERE Quiz_ID = '$old_quiz_id'";
            mysqli_query($connection, $update_query);

            $update_query = "UPDATE attempt SET Quiz_ID = '$new_quiz_id' WHERE Quiz_ID = '$old_quiz_id'";
            mysqli_query($connection, $update_query);
    
            $update_query = "UPDATE question SET Quiz_ID = '$new_quiz_id' WHERE Quiz_ID = '$old_quiz_id'";
            mysqli_query($connection, $update_query);
    
            $counter++;
        }
    
        mysqli_query($connection, "SET FOREIGN_KEY_CHECKS=1");
                
        if ($result) {
            echo 
            "<script>
            alert('Quiz deleted successfully!');
            window.location.href = 'quiz-remove.php'; 
            </script>";
        } else {
            echo
            "<script>
            alert('Error deleting quiz: " . mysqli_error($connection) . "');
            window.location.href = 'quiz-remove.php'; 
            </script>";
        }
    } else {
        $quiz_ID = $_POST['quiz_ID'];
        $query = "SELECT * FROM quiz INNER JOIN module on quiz.Module_ID = module.Module_ID WHERE Quiz_ID = '$quiz_ID'";
        $result = mysqli_query($connection, $query);
    
        $quiz_data = mysqli_fetch_assoc($result);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/remove-confirmation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Quiz Results</title>
</head>
<body>
    
    <header class="header">
        <div class="container">
            <div class="logo">
                <h2><a href="home.php">Abbott University</a></h2>
            </div>
            <nav class="menu">
                <div class="head">
                    <div class="logo">
                        <h2>Abbott University</h2>
                    </div>
                    <button type="button" class="close-menu-btn"></button>
                </div>
                <ul>
                    <li class="dropdown">
                        <a href="aboutus.php">about us</a>
                        <i class="fa-solid fa-chevron-down"></i>
                        <ul class="sub-menu">
                            <li><a href="achievements.php"><span>achievements</span></a></li>
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="study areas.php">study areas</a>
                        <i class="fa-solid fa-chevron-down"></i>
                        <ul class="sub-menu">
                            <li class="dropdown">
                                <a href="quizzes.php"><span>quiz</span></a>
                                <i class="fa-solid fa-chevron-down"></i>
                                <ul class="sub-menu sub-menu-right">
                                    <?php while ($moduleRow = mysqli_fetch_assoc($moduleQuizResult)) { ?>
                                        <li class="dropdown">
                                            <form id="form-<?php echo $moduleRow['Module_ID']; ?>" action="module.php" method="post">
                                                <input type="hidden" name="Module_ID" value="<?php echo $moduleRow['Module_ID']; ?>">
                                            </form>
                                            <a href="#" onclick="document.getElementById('form-<?php echo $moduleRow['Module_ID']; ?>').submit(); return false;">
                                                <span><?php echo $moduleRow['Module_Title']; ?></span>
                                            </a>
                                            <i class="fa-solid fa-chevron-down"></i>
                                            <ul class="sub-menu sub-menu-right">
                                            <?php
                                                $moduleID = $moduleRow['Module_ID']; 
                                                $quizQuery = "SELECT * FROM quiz WHERE Module_ID = '$moduleID'";
                                                $quizResult = mysqli_query($connection, $quizQuery);
                                                
                                                if (mysqli_num_rows($quizResult) > 0) {
                                                    while ($quizRow = mysqli_fetch_assoc($quizResult)) { ?>
                                                        <li>
                                                            <form id="form-<?php echo $quizRow['Quiz_ID']; ?>" action="quiz-page.php" method="post">
                                                                <input type="hidden" name="Quiz_ID" value="<?php echo $quizRow['Quiz_ID']; ?>">
                                                            </form>
                                                            
                                                            <a href="#" onclick="document.getElementById('form-<?php echo $quizRow['Quiz_ID']; ?>').submit(); return false;">
                                                                <span><?php echo $quizRow['Quiz_Name']; ?></span>
                                                            </a>
                                                        </li>
                                                    <?php } 
                                                } else { ?>
                                                    <li><a href="#"><span>No quizzes available</span></a></li>
                                                <?php } ?>
                                        </ul>
                                    </li>
                                <?php } ?>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="materials.php"><span>Materials</span></a>
                                <i class="fa-solid fa-chevron-down"></i>
                                <ul class="sub-menu sub-menu-right">
                                    <?php while ($moduleRow = mysqli_fetch_assoc($moduleMaterialResult)) { ?>
                                        <li>
                                            <form id="form-<?php echo $moduleRow['Module_ID']; ?>" action="module.php" method="post">
                                                <input type="hidden" name="Module_ID" value="<?php echo $moduleRow['Module_ID']; ?>">
                                            </form>
                                            <a href="#" onclick="document.getElementById('form-<?php echo $moduleRow['Module_ID']; ?>').submit(); return false;">
                                                <span><?php echo $moduleRow['Module_Title']; ?></span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a href="news.php">news</a></li>
                    <li><a href="life-in-uni.php">life in university</a></li>
                    <li><a href="logout.php">logout</a></li>
                </ul>
            </nav>
            <div class="header-right">
            <div class="header-profile"><a href="profile.php"><img src="css/Images/profile.jpeg" alt="Profile"></a></div>
                <button type="button" class="open-menu-btn">
                    <span class="line line-1"></span>
                    <span class="line line-2"></span>
                    <span class="line line-3"></span>
                </button>
            </div>
        </div>
        <hr>
    </header>


    <section class="section">
        <div class="container">
            <div class="title">
                <h2>Hold On!</h2>
            </div>
            <div class="remove-container">
                <div class="remove-bg">
                    <div class="texts">
                        <h2>Are you sure?</h2>
                        <br>
                        <p>Action:</p>
                        <p>Remove <?php echo $quiz_data['Quiz_Name'] . " (" . $quiz_data['Module_Title'] . ") "; ?></p>
                    </div>
                    <div class="exit-button">
                        <button class="exit-btn" id="Exit"><h3>Back</h3></button>
                        
                        <button class="yes-btn" id="Yes"><h3>Yes, I am Sure</h3></button>

                        <form id="deleteQuizForm" action="remove-confirmation.php" method="POST">
                            <input type="hidden" name="message" id="quizMessage">
                        </form>
                    </div>

                    <script>
                        document.getElementById("Exit").addEventListener("click", function() {
                            window.location.href = "quiz-remove.php";
                        });

                        let quizID = "<?php echo isset($quiz_ID) ? htmlspecialchars($quiz_ID, ENT_QUOTES, 'UTF-8') : ''; ?>";

                        document.getElementById("Yes").addEventListener("click", function () {
                            if (!quizID) {
                                alert("Error: Quiz ID is missing!");
                                return;
                            }

                            if (confirm("Are you sure you want to delete this quiz?")) {
                                document.getElementById("quizMessage").value = quizID;
                                document.getElementById("deleteQuizForm").submit();
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="search-bar.js"></script>
        <script src="open-close.js" defer></script>
    </section>


    <footer class="footer">
        <div class="footer-container">
            <div class="footer-logo">
                <h1><a href="#">Ab.Uni.</a></h1>
                <div class="footer-address">
                    <ol>Address:</ol>
                    <ol>123, Main Street, City</ol>
                    <ol>State Province, Country</ol>
                </div>
            </div>
        </div>
        <div class="footer-links">
            <br>
            <h6>2024. Abbott University. All Rights Reserved.</h6>
        </div>
    </footer>
    <?php mysqli_close($connection); ?>
</body>
</html>