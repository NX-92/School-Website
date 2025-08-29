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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/aboutus.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>About Us</title>
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
                <h2>About Us</h2>
            </div>
            <div class="information">
                <h3>Why here?</h3>
                <p>
                    <span class="bold-font">Welcome to Abbott University</span>,
                    <br>
                    a premier institution dedicated to excellence in programming and software development. 
                    Our university specializes in cutting-edge education in Java, SQL, and Python, equipping students with the skills 
                    needed to thrive in the ever-evolving tech industry.
                </p>
            </div>
            <div class="more-information">
                <div class="info-img">
                    <img src="css/Images/about-us-img (3).jpeg" alt="About Us">
                </div>
                <div class="info-text">
                    <h1 class="h1-1">
                        AI Technology,
                    </h1>
                    <br>
                    <h1 class="h1-2">
                        Seamingless Vision,
                    </h1>
                    <br>
                    <h1 class="h1-3">
                        Code Within Your Creativity.
                    </h1>
                </div>
            </div>
            <div class="information">
                <h3>Our services</h3>
            </div>
            <div class="services-container">
                <div class="box">
                    <div class="img-box">
                        <img src="css/Images/cpu.png" alt="java programming">
                    </div>
                    <div class="text-box">
                        <h3>Java Programming Courses</h3>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="css/Images/sql.png" alt="sql database">
                    </div>
                    <div class="text-box">
                        <h3>SQL & Database Management</h3>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="css/Images/artificial-intelligence.png" alt="ai python">
                    </div>
                    <div class="text-box">
                        <h3>Python Programming & AI Development</h3>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="css/Images/certificate.png" alt="certs">
                    </div>
                    <div class="text-box">
                        <h3>Career-Focused Training & Certifications</h3>
                    </div>
                </div>
                <div class="box">
                    <div class="img-box">
                        <img src="css/Images/training.png" alt="training">
                    </div>
                    <div class="text-box">
                        <h3>Career-Focused Training</h3>
                    </div>
                </div>
            </div>
            <div class="information">
                <h3>Our history</h3>
            </div>
            <div class="img-container">
                <img src="css/Images/abous-us-img (2).jpeg" alt="History">
            </div>
            <div class="information">
                <p>
                    <span class="bold-font">Abbott University</span> was founded with a vision to empower aspiring developers, data analysts, 
                    and software engineers through high-quality education in Java, SQL, and Python programming. 
                    Established in the early 2000s, the university started as a small training institute focused 
                    on software development and database management. Initially, Abbott University offered short-term 
                    courses in Java programming and SQL database management, catering to students and professionals 
                    looking to advance their skills in the rapidly growing IT sector. With the rise of web technologies 
                    and automation, the institution expanded its curriculum to include Python programming, 
                    recognizing its increasing importance in data science, artificial intelligence, and automation.
                </p>
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