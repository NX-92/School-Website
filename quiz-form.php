<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "school_system";

    $connection = mysqli_connect($server, $user, $password, $database);
    if (!$connection) {
        die('Connection Failed! ' . mysqli_connect_error());
    }

    $query = "SELECT * FROM module";
    $result = mysqli_query($connection, $query);

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
    <link rel="stylesheet" href="css/quiz-form.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Quiz Form</title>
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
            <br>
            <form action="quiz-form-process.php" method="POST">
                <div class="module-selection-box">
                    <label for="multi-select"><h3>Select Module:</h3></label><br>
                    <select id="module" name="module_selected">
                        <?php
                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<option value='" . $row['Module_ID'] . "'>" . $row['Module_Title'] . "</option>";
                            }
                        } else {
                            echo "<option>No modules available</option>";
                        }
                        ?>
                    </select>
                </div>
                <br><br>
                <div class="module-selection-box">
                    <label for="multi-select"><h3>Select TIme Limit:</h3></label><br>
                    <select id="multi-select" name="time_limit">
                        <option value="20">20 minutes</option>
                        <option value="15">15 minutes</option>
                        <option value="10">10 minutes</option>
                    </select>
                 </div>

                <div class="title">
                    <h2>Quiz Title:</h2>
                    <input type="text" name="quiz-title" placeholder="Enter your quiz title here..." id="quiz-title" class="quiz-title" required>
                </div>
                <h3>Enter quiz description here:</h3>
                <br>
                <textarea name="Description" class="description-text" cols="15" rows="5" 
                        placeholder="Enter your quiz description here.." required 
                        oninput="checkWordLimit(this, 50)"></textarea>
                <p id="wordCountMsg" style="color: red;"></p>

                <script>
                function checkWordLimit(textarea, maxWords) {
                    let words = textarea.value.trim().split(/\s+/);
                    let wordCount = words.filter(word => word.length > 0).length;

                    if (wordCount > maxWords) {
                        document.getElementById("wordCountMsg").textContent = "Word limit exceeded! Max " + maxWords + " words allowed.";
                        textarea.value = words.slice(0, maxWords).join(" ");
                    } else {
                        document.getElementById("wordCountMsg").textContent = "";
                    }
                }
                </script>
                <br><br><br>
                <div class="module-selection-box">
                
                <?php for ($i = 1; $i <= 10; $i++): ?>
                <div class="question-container">
                    <div class="question-label"><h3>Question <?php echo $i; ?></h3></div>
                    <div class="question-detail">
                        <div class="question">
                            <div class="question-text">
                            <textarea name="question<?php echo $i; ?>" cols="15" rows="5" 
                                    placeholder="Enter your question here.." required 
                                    oninput="checkWordLimit(this, 30)"></textarea>
                            <p id="wordCountMsg<?php echo $i; ?>" style="color: red;"></p>
                            </div>
                            <input type="number" name="points<?php echo $i; ?>" 
                                id="points<?php echo $i; ?>" 
                                placeholder="Points..." class="point" 
                                min="1" max="20" required
                                oninput="validatePoints(this)">
                                
                            <script>
                            function validatePoints(input) {
                                if (input.value < 1) input.value = 1;
                                if (input.value > 20) input.value = 20;
                            }
                            </script>
                        </div>
                        <div class="question-box">
                            <div class="box">
                                <input type="text" name="answer<?php echo $i; ?>1" 
                                    id="answer<?php echo $i; ?>1" 
                                    placeholder="Answer 1" class="answers" 
                                    minlength="1" maxlength="20" required 
                                    oninput="validateTextLength(this)">
                            </div>
                            <div class="box">
                                <input type="text" name="answer<?php echo $i; ?>2" 
                                    id="answer<?php echo $i; ?>2" 
                                    placeholder="Answer 2" class="answers" 
                                    minlength="1" maxlength="20" required 
                                    oninput="validateTextLength(this)">
                            </div>
                            <div class="box">
                                <input type="text" name="answer<?php echo $i; ?>3" 
                                    id="answer<?php echo $i; ?>3" 
                                    placeholder="Answer 3" class="answers" 
                                    minlength="1" maxlength="20" required 
                                    oninput="validateTextLength(this)">
                            </div>
                            <div class="box">
                                <input type="text" name="answer<?php echo $i; ?>4" 
                                    id="answer<?php echo $i; ?>4" 
                                    placeholder="Answer 4" class="answers" 
                                    minlength="1" maxlength="20" required 
                                    oninput="validateTextLength(this)">
                            </div>
                        </div>

                        <script>
                        function validateTextLength(input) {
                            if (input.value.length > 20) {
                                input.value = input.value.substring(0, 20);
                            }
                        }
                        </script>
                        <div class="correct-option-box">
                            <label for="multi-select-<?php echo $i; ?>">Choose your correct answer:</label><br>
                            <select name="correct-answer<?php echo $i; ?>" id="multi-select-<?php echo $i; ?>" required>
                                <option value="Answer1">Answer 1</option>
                                <option value="Answer2">Answer 2</option>
                                <option value="Answer3">Answer 3</option>
                                <option value="Answer4">Answer 4</option>
                            </select>
                        </div>
                    </div>
                </div>
                <br><br>
                <?php endfor; ?>

                <div class="button-box">
                    <button type="submit" class="next-btn">Submit Quiz</button>
                </div>
            </form>
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