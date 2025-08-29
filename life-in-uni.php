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
    <link rel="stylesheet" href="css/life-in-uni.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Life in University</title>
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
                <h2>Life in University</h2>
            </div>
            <div class="information">
                <p>
                    <span class="bold-font">Life at Abbott University</span> is an enriching and dynamic experience, supported by state-of-the-art facilities 
                    that enhance both academic and social growth. The university boasts modern lecture halls, high-tech computer labs, 
                    and specialized programming hubs for students pursuing Java, Python, and SQL. The library is fully equipped 
                    with digital and physical resources, ensuring students have access to the latest research materials. 
                    Beyond academics, Abbott University offers comfortable student lounges, sports complexes, and collaborative 
                    study spaces, fostering both productivity and relaxation. With a vibrant cafeteria, recreational areas, and 
                    career development centers, students can enjoy a well-balanced university life, making Abbott University 
                    an ideal place for both learning and personal growth.
                </p>
            </div>
            <div class="information">
                <h3>Indoor Facilities</h3>
                <hr>
            </div>
            <div class="slider-container">
                <div class="img-slider">
                    <img src="css/Images/indoor (1).jpeg" alt="INDOOR">
                    <img src="css/Images/indoor (2).jpeg" alt="INDOOR">
                    <img src="css/Images/indoor (3).jpeg" alt="INDOOR">
                    <img src="css/Images/indoor (4).jpeg" alt="INDOOR">
                </div>
            </div>
            <div class="img-title">
                <h3>Music Hall, Auditoriums & Libraries</h3>
            </div>
            <div class="information">
                <p>
                    The Music Hall serves as a creative hub for aspiring musicians, featuring advanced sound systems, practice rooms, 
                    and performance stages. The university's auditoriums are equipped with cutting-edge audio-visual technology, 
                    hosting seminars, guest lectures, and cultural events throughout the year. Meanwhile, the library offers a vast 
                    collection of books, digital resources, and private study areas, providing students with an ideal environment 
                    for research and learning. These facilities ensure that Abbott University students have access to top-tier 
                    resources for both academic excellence and artistic expression.
                </p>
            </div>
            <div class="information">
                <h3>Outdoor Facilities</h3>
                <hr>
            </div>
            <div class="slider-container">
                <div class="img-slider">
                    <img src="css/Images/outdoor (1).jpeg" alt="OUTDOOR">
                    <img src="css/Images/outdoor (2).jpeg" alt="OUTDOOR">
                    <img src="css/Images/outdoor (3).jpeg" alt="OUTDOOR">
                    <img src="css/Images/outdoor (4).jpeg" alt="OUTDOOR">
                </div>
            </div>
            <div class="img-title">
                <h3>Outdoor Campus</h3>
            </div>
            <div class="information">
                <p>
                    Abbott University boasts a vibrant outdoor campus, designed to promote both academic and recreational experiences. 
                    The spacious green lawns and outdoor seating areas provide students with a refreshing space to study, relax, 
                    and collaborate. The sports complex includes well-maintained football fields, basketball courts, and jogging 
                    tracks, encouraging an active lifestyle. Additionally, the outdoor amphitheater hosts cultural performances, 
                    open-air lectures, and student gatherings, creating a dynamic social atmosphere. With beautifully landscaped 
                    gardens and walking paths, Abbott University’s outdoor facilities enhance the overall student experience, 
                    making campus life both enjoyable and engaging.
                </p>
            </div>
        </div>
        <script type="text/javascript" src="search-bar.js"></script>
        <script src="open-close.js" defer></script>
        <?php mysqli_close($connection); ?>
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