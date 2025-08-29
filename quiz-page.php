<?php
    session_start();
    $role = $_SESSION['Login_Role'];
    if ($role == "Student") {
        $user_ID = $_SESSION['Login_Student_ID'];
    } else {
        $user_ID = $_SESSION['Login_Lecturer_ID'];
    }

    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "school_system";

    $connection = mysqli_connect($server, $user, $password, $database);
    if (!$connection) {
        die('Connection Failed! ' . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $quiz_id = $_POST['Quiz_ID'];
    
        if (!$quiz_id) {
            echo "Invalid request.";
            exit;
        }

        $query = "SELECT * FROM quiz WHERE Quiz_ID = '$quiz_id'";
        $result = mysqli_query($connection, $query);
        $quiz = mysqli_fetch_assoc($result);

        if (!$quiz) {
            echo "Quiz not found.";
            exit;
        }

        $query = "SELECT * FROM quiz INNER JOIN module on quiz.Module_ID = module.Module_ID";
        $result = mysqli_query($connection, $query);
        $module = mysqli_fetch_assoc($result);

        if ($role == "Student") {
            $attemptQuery = "SELECT Attempt_ID, Time_Taken, Score FROM attempt WHERE Quiz_ID = '$quiz_id' AND Student_ID = '$user_ID' ORDER BY Attempt_ID DESC LIMIT 3";
        } else {
            $attemptQuery = "SELECT Attempt_ID, Time_Taken, Score FROM attempt WHERE Quiz_ID = '$quiz_id' AND Lecturer_ID = '$user_ID' ORDER BY Attempt_ID DESC LIMIT 3";
        }

        $attemptResult = mysqli_query($connection, $attemptQuery);
    
        if (!$attemptResult) {
            die('Error retrieving attempt data: ' . mysqli_error($connection));
        }
    }

    $query = "SELECT * FROM module";
    $moduleQuizResult = mysqli_query($connection, $query);
    $moduleMaterialResult = mysqli_query($connection, $query);

    if (!$moduleQuizResult) {
    die('Error retrieving event data: ' . mysqli_error($connection));
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/quiz-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Quiz</title>
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
                <h2><?php echo $quiz['Quiz_Name']; ?></h2>
            </div>
            <div class="information">
                <h3>Summary of Quiz</h3>
            </div>
            <div class="img-container">
                <img src="<?php echo $module['Image']; ?>" alt="About Us">
            </div>
            <div class="summary-container">
                <div class="summary-context">
                    <h3>Attempt:</h3>
                    <h3>Time Taken:</h3>
                    <h3>Mark (Grade):</h3>
                    <div class="summary-details">
                        <?php 
                            if (mysqli_num_rows($attemptResult) > 0) {
                                $Count = 1;
                                while ($attemptRow = mysqli_fetch_assoc($attemptResult)) { ?>
                                    <div class="log">
                                        <p><?php echo $Count; ?></p>
                                        <p><?php echo gmdate($attemptRow['Time_Taken']); ?></p>
                                        <p><?php echo $attemptRow['Score']; ?>/100</p>
                                    </div>
                                <?php 
                                $Count++;
                                }
                            } else { ?>
                                <div class="log">
                                <p><?php echo ('No attempts recorded yet.'); ?></p>
                            </div>
                            <?php } ?>
                    </div>
                </div>
            </div>
            <div class="start-button">
                <form id="startForm-<?php echo $quiz['Quiz_ID']; ?>" action="quiz-layout.php" method="POST">
                    <input type="hidden" name="Quiz_ID" value="<?php echo $quiz['Quiz_ID']; ?>">
                </form>
                
                <a href="#" class="start-btn" onclick="document.getElementById('startForm-<?php echo $quiz['Quiz_ID']; ?>').submit(); return false;">
                    <h3>Start</h3>
                </a>
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