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
    
    session_start();
    $username = $_SESSION['Login_Username'];
    $contact_Number = $_SESSION['Login_Contact_Number'];
    $email_Address = $_SESSION['Login_Email_Address'];
    $role = $_SESSION['Login_Role'];
    $IC_Number = $_SESSION['Login_IC_Number'];
    $password = $_SESSION['Login_Password'];
    $register_Date = $_SESSION['Login_Register_Date'];
    if ($role == "Student") {
        $user_ID = $_SESSION['Login_Student_ID'];
        $supervisor_Name = $_SESSION['Login_Student_Supervisor_Name'];
        $supervisor_Email_Address = $_SESSION['Login_Student_Supervisor_Email_Address'];
        $supervisor_Contact_Number = $_SESSION['Login_Student_Supervisor_Contact_Number'];
    } else {
        $user_ID = $_SESSION['Login_Lecturer_ID'];
    }

    $query = "SELECT * FROM module";
    $result = mysqli_query($connection, $query);
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="css/lecturer-profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Profile</title>
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
                <h2>Profile</h2>
            </div>
            <div class="profile">
                <img src="css/Images/profile.jpeg" alt="Profile Picture">
            </div>
            <div class="welcome">
                <h2>Welcome, <?php echo htmlspecialchars($username); ?>!</h2>
            </div>
            <div class="student-id">
                <p><?php echo htmlspecialchars($user_ID); ?></p>
            </div>
            <?php if ($role == 'Student'): ?>
                <div class="information">
                    <h3>Information</h3>
                    <hr>
                    <h3>Your Mentor</h3>
                </div>
                <div class="mentor-container">
                    <div class="mentor-profile">
                        <img src="css/Images/profile.jpeg" alt="Profile Picture">
                    </div>
                    <div class="mentor-name">

                        <div class="mentor-role">
                            <h3><?php echo htmlspecialchars($supervisor_Name); ?></h3>
                            <p>Programme Leader</p>
                        </div>
                    </div>
                    <div class="profile-menu">
                        <div class="box1"><p><?php echo htmlspecialchars($supervisor_Email_Address); ?></p></div>
                        <div class="box2"><p><?php echo htmlspecialchars($supervisor_Contact_Number); ?></p></div>
                    </div>
                </div>
            <?php elseif ($role == 'Lecturer'): ?>
                <div class="information">
                <h3>Information</h3>
                <hr>
            </div>
            <div class="dashboard-container">
                <div class="container">
                    <div class="box">
                        <a href="quiz-form.php">
                            <div class="clickbox">
                                <h3>Create Quiz</h3>
                            </div>
                        </a>
                    </div>
                    <div class="box">
                        <a href="quiz-remove.php">
                            <div class="clickbox">
                                <h3>Remove Quiz</h3>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <div class="information">
                <h3>Modules</h3>
                <hr>
            </div>
            <div class="module-container">
            <?php
                if (mysqli_num_rows($result) > 0) {
                    while ($learning_Material_Data = mysqli_fetch_assoc($result)) {
                        ?>
                        <form id="form-<?php echo $learning_Material_Data['Module_ID']; ?>" action="module.php" method="POST">
                            <input type="hidden" name="Module_ID" value="<?php echo $learning_Material_Data['Module_ID']; ?>">
                        </form>

                        <div class="module-box1" onclick="document.getElementById('form-<?php echo $learning_Material_Data['Module_ID']; ?>').submit(); return false;">
                            <div class="box-img">
                                <img src="<?php echo $learning_Material_Data['Image']; ?>" alt="Module Image">
                            </div>
                            <div class="box-text">
                                <p><?php echo $learning_Material_Data['Module_Title']; ?></p>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
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