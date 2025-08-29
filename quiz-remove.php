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

    $module_data = mysqli_query($connection, $query); 

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
    <link rel="stylesheet" href="css/quiz-remove.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Remove Quiz</title>
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
                <h2>Remove Quiz</h2>
            </div>
            <div class="information">
                <h3>Quizzes</h3>
            </div>

            <?php
            if (mysqli_num_rows($module_data) > 0) {
                while ($learning_Module_Data = mysqli_fetch_assoc($module_data)) {
                    $Module_ID = $learning_Module_Data['Module_ID'];
            ?>
                    <div class="material-container">
                        <div class="material-box">
                            <div class="material-context">
                                <div class="context1">
                                    <h3><?php echo htmlspecialchars($learning_Module_Data['Module_Title']); ?></h3>
                                </div>
                            </div>
                        </div>

                        <?php
                        $query = "SELECT * FROM quiz WHERE Module_ID = '$Module_ID'";
                        $result = mysqli_query($connection, $query);

                        if (!$result) {
                            die("Query Failed: " . mysqli_error($connection));
                        }

                        if (mysqli_num_rows($result) > 0) {
                            echo '<div class="materials-exp">';
                            while ($learning_Quiz_Data = mysqli_fetch_assoc($result)) {
                                $quiz_ID = $learning_Quiz_Data['Quiz_ID'];
                        ?>
                                <div class="materials-context">
                                    <h3 class="quiz-title">
                                        <a href="quiz.php?id=<?php echo urlencode($quiz_ID); ?>">
                                            <?php echo htmlspecialchars($learning_Quiz_Data['Quiz_Name']); ?>
                                        </a>
                                    </h3>
                                    <div class="details">
                                        <?php
                                        $queryCount = "SELECT COUNT(*) as total FROM question WHERE Quiz_ID = '$quiz_ID'";
                                        $resultCount = mysqli_query($connection, $queryCount);

                                        if (!$resultCount) {
                                            die("Count Query Failed: " . mysqli_error($connection));
                                        }

                                        $totalQuestion = mysqli_fetch_assoc($resultCount);
                                        ?>
                                        <p>Total: <?php echo htmlspecialchars($totalQuestion['total']); ?> Questions</p>
                                    </div>
                                    <div class="remove-button">
                                        <form action="remove-confirmation.php" method="POST">
                                            <input type="hidden" name="quiz_ID" value="<?php echo htmlspecialchars($quiz_ID); ?>">
                                            <button type="submit" class="remove-btn">Remove</button>
                                        </form>
                                    </div>
                                </div>
                        <?php
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
            <?php
                }
            }
            ?>
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