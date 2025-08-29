<?php
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'school_system';

    $connection = mysqli_connect($server, $user, $password, $database);
    if (!$connection) {
        die('Connection Failed! ' . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['Module_ID'])) {
        $module_id = $_POST['Module_ID'];

        $query = "SELECT * FROM module WHERE Module_ID = '$module_id'";
        $result = mysqli_query($connection, $query);

        if (!$result) {
        die('Error retrieving event data: ' . mysqli_error($connection));
        }

        $query = "SELECT * FROM module";
        $moduleQuizResult = mysqli_query($connection, $query);
        $moduleMaterialResult = mysqli_query($connection, $query);
    
        if (!$moduleQuizResult) {
        die('Error retrieving event data: ' . mysqli_error($connection));
        }

        $module_Data = mysqli_fetch_assoc($result);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/module desc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title><?php echo $module_Data['Module_Title']; ?></title>
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
                                <a href="materials.php"><span>materials</span></a>
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
                <h2><?php echo $module_Data['Module_Title']; ?></h2>
            </div>
            <div class="information">
                <h3>Brief Description</h3>
            </div>
            <div class="img-container">
                <img src="<?php echo $module_Data['Image']; ?>" alt="Java Programming">
            </div>
            <div class="information">
                <p>
                    <span class="bold-font"><?php echo $module_Data['Module_Name']; ?></span> <?php echo $module_Data['Description']; ?>
                </p>
            </div>
            <div class="information">
                <h3>Related Materials</h3>
            </div>
            <div class="material-container">
                <?php
                    $module_id = $_POST['Module_ID'];
                    $query = "SELECT * FROM learning_material WHERE Module_ID = '$module_id'";
                    $result = mysqli_query($connection, $query);

                    if (!$result) {
                        die('Error retrieving learning material: ' . mysqli_error($connection));
                    }

                    if (mysqli_num_rows($result) > 0) {
                        while ($learning_Material_Data = mysqli_fetch_assoc($result)) {
                    ?>
                            <div class="material-box">
                                <div class="material-context">
                                    <div class="context1">
                                        <h3><?php echo htmlspecialchars($learning_Material_Data['Learning_Material_Name']); ?> </h3>
                                        <p>
                                            <?php echo htmlspecialchars($learning_Material_Data['Description']); ?>
                                        </p>
                                    </div>
                                    <div class="context2">
                                        <a href="<?php echo $learning_Material_Data['Source']?>" download="<?php echo $learning_Material_Data['Learning_Material_Name']?>">
                                        <h3>Download</h3>
                                        </a>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    } else {
                        echo '<p>No learning materials found.</p>';
                    }

                ?>
            </div>
            <div class="information">
                <h3>Quizzes</h3>
            </div>
            <div class="quiz-container">
            <?php 
            $query = "SELECT * FROM quiz WHERE Module_ID = '$module_id'";
            $result = mysqli_query($connection, $query);

            if (mysqli_num_rows($result) > 0) {
                while ($quiz = mysqli_fetch_assoc($result)) {
                    ?>
                    <div class="quiz-box">
                        <div class="quiz-context">
                            <div class="quiz-text">
                                <h3>
                                    <form id="form-<?php echo $quiz['Quiz_ID']; ?>" action="quiz-page.php" method="post">
                                        <input type="hidden" name="Quiz_ID" value="<?php echo $quiz['Quiz_ID']; ?>">
                                        <button type="submit" style="all:unset; cursor:pointer; text-decoration:underline;">
                                            <?php echo htmlspecialchars($quiz['Quiz_Name']); ?>
                                        </button>
                                    </form>
                                </h3>
                                <p>
                                    <?php echo htmlspecialchars($quiz['Description']); ?>
                                </p>
                            </div>
                            <div class="<?php 
                                $quiz_id = $quiz['Quiz_ID'];
                                $query = "SELECT * FROM attempt WHERE Quiz_ID = '$quiz_id' ORDER BY Score DESC LIMIT 1";
                                
                                $classResult = mysqli_query($connection, $query);

                                $class = "results-fail";

                                if ($classResult && mysqli_num_rows($classResult) > 0) {
                                    $attemptResult = mysqli_fetch_assoc($classResult);
                                    $score = $attemptResult['Score'];

                                    if ($score >= 50) {
                                        $class = "results-pass";
                                    }
                                } else {
                                    $score = "N/A";
                                }

                                echo $class;
                            ?>">
                                <h2>
                                    <?php echo is_numeric($score) ? $score . "/100" : "N/A/100"; ?>
                                </h2>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<p>No quizzes found.</p>';
            }

                mysqli_close($connection);
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
</body>
</html>