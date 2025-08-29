<?php
    session_start();
    $username = $_SESSION['Login_Username'];
    $contact_Number = $_SESSION['Login_Contact_Number'];
    $email_Address = $_SESSION['Login_Email_Address'];
    $role = $_SESSION['Login_Role'];
    $IC_Number = $_SESSION['Login_IC_Number'];
    $password = $_SESSION['Login_Password'];
    if ($role == "Student") {
        $user_ID = $_SESSION['Login_Student_ID'];
    } else {
        $user_ID = $_SESSION['Login_Lecturer_ID'];
    }

    $server = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'school_system';
    $connection = mysqli_connect($server, $user, $password, $database);

    date_default_timezone_set('Asia/Kuala_Lumpur');

    $query = "SELECT * FROM module";
    $moduleQuizResult = mysqli_query($connection, $query);
    $moduleMaterialResult = mysqli_query($connection, $query);

    if (!$moduleQuizResult) {
    die('Error retrieving event data: ' . mysqli_error($connection));
    }

    $newData = null;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['new_id'])) {
            $new_id = $_POST['new_id'];
            
            $query = "SELECT * FROM new WHERE New_ID = '$new_id'";
            $result = mysqli_query($connection, $query);
    
            if (!$result) {
                die('Error retrieving event data: ' . mysqli_error($connection));
            }

            $newData = mysqli_fetch_assoc($result);

        } else {
            echo "New ID not received!";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['commentSubmit'])) {
        $new_id = $_POST['new_id'];
        $Date_Time = date('Y-m-d H:i:s');
        $message = $_POST['Description1'] ?? '';
    
        $query = "SELECT COUNT(*) AS total FROM comment";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $comment_id = 'C' . str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);
    
        if ($role == "Student") {
            $sql = "INSERT INTO comment (Comment_ID, New_ID, Student_ID, Lecturer_ID, Date_Time, Description) 
                    VALUES ('$comment_id','$new_id','$user_ID', NULL, '$Date_Time','$message')";
        } else {
            $sql = "INSERT INTO comment (Comment_ID, New_ID, Student_ID, Lecturer_ID, Date_Time, Description) 
                    VALUES ('$comment_id','$new_id', NULL, '$user_ID', '$Date_Time','$message')";
        }
    
        if (mysqli_query($connection, $sql)) {
            echo "<script>alert('Comment added successfully!');</script>";
        } else {
            echo "Error: " . mysqli_error($connection);
        }
    }

    function getComments($connection, $new_id) { 
        $sql = "SELECT * FROM comment
                LEFT JOIN student ON student.Student_ID = comment.Student_ID
                LEFT JOIN lecturer ON lecturer.Lecturer_ID = comment.Lecturer_ID
                WHERE New_ID = '$new_id'
                ORDER BY Date_Time DESC";
        $result = mysqli_query($connection, $sql);
    
        if (!$result) {
            die('Error retrieving comments: ' . mysqli_error($connection));
        }
    
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='comment-box'>";
            
            if ($row['Student_ID'] == null) { 
                echo "<div class='bold-comment-font'>" . $row['Lecturer_ID'] . ' - ' . $row['Lecturer_First_Name'] . ' ' . $row['Lecturer_Last_Name'] . " (Lecturer)</div><br>";
            } else {
                echo "<div class='bold-comment-font'>" . $row['Student_ID'] . ' - ' . $row['Student_First_Name'] . ' ' . $row['Student_Last_Name'] . " (Student)</div><br>";
            }
    
            echo "<div class='bold-comment-font'>".$row['Date_Time']."</div><br>";
            echo nl2br($row['Description']);
            echo "</div>";
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/news-desc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title><?php echo $newData['Title']; ?></title>
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
                <h2>News & Notices</h2>
            </div>
            <div class="information">
                <h3><?php echo $newData['Title']; ?></h3>
                <hr>
            </div>
            <div class="img-container">
                <img src="<?php echo $newData['Picture']; ?>" alt="<?php echo $newData['Title']; ?>">
            </div>
            <div class="information">
                <p>
                    <span class="bold-font"><?php echo $newData['Spam_Description']; ?></span>
                    <?php echo $newData['Description']; ?>
                </p>
            </div>            
            <div class="information">
                <h3>Comments</h3>
                <hr>
            </div>
            <div class="comment-container">
                <div class="comment-details">
                    <?php
                    getComments($connection, $newData['New_ID']);
                    ?>
                </div>
                <div class="comment-subject">
                <form method='POST' action='particular-new.php'>
                    <input type='hidden' name='new_id' value='<?php echo $new_id ?>'>
                    <textarea name="Description1" id="description" placeholder="Enter your description" 
                            oninput="checkWordLimit(this, 20)"></textarea>
                    <p id="descWordCountMsg" style="color: red;"></p>

                    <script>
                    function checkWordLimit(textarea, maxWords) {
                        let words = textarea.value.trim().split(/\s+/);
                        let wordCount = words.filter(word => word.length > 0).length;

                        if (wordCount > maxWords) {
                            document.getElementById("descWordCountMsg").textContent = 
                                "Word limit exceeded! Max " + maxWords + " words allowed.";
                            textarea.value = words.slice(0, maxWords).join(" ");
                        } else {
                            document.getElementById("descWordCountMsg").textContent = "";
                        }
                    }
                    </script>
                    <div class='button-box'>
                        <button class='comment-btn' type='submit' name='commentSubmit' id='comment'><h3>Comment</h3></button>
                    </div>
                </form>
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