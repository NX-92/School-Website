<?php
    session_start();

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
    }

    $query = "SELECT * FROM quiz INNER JOIN module on quiz.Module_ID = module.Module_ID Where Quiz_ID = '$quiz_id'";
    $result = mysqli_query($connection, $query);

    $quiz = mysqli_fetch_assoc($result);

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
    <link rel="stylesheet" href="css/quiz-layout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Quiz Layout</title>
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
                <div class="title-name">
                    <h2><?php echo $quiz['Quiz_Name'] . " (" . $quiz['Module_Title'] . ") "; ?></h2>
                </div>
                <div id= "timer" class="timestamp">
                    <h2></h2>
                    <script>
                        let timeLeft;
                        let totalDuration;

                        function startCountdown(durationInSeconds) {
                            let timerElement = document.getElementById("timer");
                            timeLeft = durationInSeconds;
                            totalDuration = durationInSeconds;

                            function updateTimer() {
                                let hours = Math.floor(timeLeft / 3600);
                                let minutes = Math.floor((timeLeft % 3600) / 60);
                                let seconds = timeLeft % 60;

                                let formattedTime = 
                                    String(hours).padStart(2, '0') + ":" +
                                    String(minutes).padStart(2, '0') + ":" +
                                    String(seconds).padStart(2, '0');

                                timerElement.textContent = formattedTime;

                                document.getElementById("timeTaken").value = totalDuration - timeLeft;

                                if (timeLeft > 0) {
                                    timeLeft--;
                                    setTimeout(updateTimer, 1000);
                                } else {
                                    alert("Time-up!");
                                    document.querySelector("form[action='quiz-layout-process.php']").submit();
                                }
                            }

                            updateTimer();
                        }

                        function updateTimeTaken() {
                            document.getElementById("timeTaken").value = totalDuration - timeLeft;
                        }

                        function timeToSeconds(timeString) {
                            let parts = timeString.split(":");
                            return (parseInt(parts[0], 10) * 3600) + (parseInt(parts[1], 10) * 60) + parseInt(parts[2], 10);
                        }

                        let totalSeconds = timeToSeconds("<?php echo $quiz['Time_Limit']; ?>");
                        window.onload = function() {
                            startCountdown(totalSeconds);
                        };
                    </script>
                </div>
            </div>
            <form id="timeUpForm" method="POST" action="quiz-layout-process.php">
                <input type="hidden" name="Quiz_ID" value="<?php echo $quiz['Quiz_ID']; ?>">
                <input type="hidden" name="time_taken" id="timeTaken" value="">
                <?php
                if ($quiz_id) {
                    echo '<input type="hidden" name="Quiz_ID" value="' . $quiz_id . '">';

                    $query = "SELECT * FROM question WHERE Quiz_ID = '$quiz_id'";
                    $result = mysqli_query($connection, $query);

                    if ($result && mysqli_num_rows($result) > 0) {
                        $question_number = 1;
                        while ($question = mysqli_fetch_assoc($result)) {
                            $question_id = $question['Question_ID'];
                            ?>
                            
                            <div class="question-container">
                                <div class="question-label">
                                    <h3>Question <?php echo $question_number; ?></h3>
                                </div>
                                <div class="question-detail">
                                    <div class="question">
                                        <div class="question-text">
                                            <p><?php echo htmlspecialchars($question['Question_Title']); ?></p>
                                        </div>
                                        <div class="point">
                                            <h3><?php echo htmlspecialchars($question['Point']); ?> point(s)</h3>
                                        </div>
                                    </div>
                                    <div class="question-box">
                                    <?php
                                        for ($i = 1; $i <= 4; $i++) {
                                            $answer = htmlspecialchars($question["Answer$i"]);
                                            $id = "option_{$question_number}_$i";
                                            ?>
                                            <div class="box">
                                                <input type="radio" name="Question_<?php echo $question_id; ?>" id="<?php echo $id; ?>" value="Answer<?php echo $i; ?>" hidden>
                                                <label for="<?php echo $id; ?>" class="answers"><?php echo $answer; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $question_number++;
                        }
                    } else {
                        echo "No questions found for this quiz.";
                    }
                } else {
                    echo "Invalid quiz ID.";
                }
                ?>
                <div class="button-box">
                <button type="submit" onclick="updateTimeTaken()" form="timeUpForm" class="next-btn">Submit</button>
                </div>
            </form>
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