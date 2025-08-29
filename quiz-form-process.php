<?php
    $server = "localhost";
    $user = "root";
    $password = "";
    $database = "school_system";

    $connection = mysqli_connect($server, $user, $password, $database);
    if (!$connection) {
        die('Connection Failed! ' . mysqli_connect_error());
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
    } else {
        $user_ID = $_SESSION['Login_Lecturer_ID'];
    }

    $timeLimit = $_POST['time_limit'];

    if ($timeLimit == 20) {
        $timeString = "00:20:00";
    } elseif ($timeLimit == 15) {
        $timeString = "00:15:00";
    } elseif ($timeLimit == 10) {
        $timeString = "00:10:00";
    } else {
        $timeString = "Unknown time limit";
    }
    
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $isFilled = true;
    for ($i = 1; $i <= 10; $i++) {
        if (
            !isset($_POST["question$i"], $_POST["points$i"], $_POST["answer{$i}1"], 
                   $_POST["answer{$i}2"], $_POST["answer{$i}3"], $_POST["answer{$i}4"], 
                   $_POST["correct-answer$i"]) 
            || empty($_POST["question$i"]) || empty($_POST["points$i"]) 
            || empty($_POST["answer{$i}1"]) || empty($_POST["answer{$i}2"]) 
            || empty($_POST["answer{$i}3"]) || empty($_POST["answer{$i}4"]) 
            || empty($_POST["correct-answer$i"])
        ) {
            $isFilled = false;
            break;
        }        
    }

    if (!$isFilled) {
        echo "Error: All fields must be filled!";
        exit();
    }

    $quizTitle = htmlspecialchars($_POST["quiz-title"]);
    $description = htmlspecialchars($_POST["Description"]);
    if (isset($_POST['module_selected'])) {
        $module_id = $_POST['module_selected'];
    }
    $questions = [];
    $answers = [];
    $correctAnswers = [];
    $points = [];
    $totalPoints = 0;

    for ($i = 1; $i <= 10; $i++) {
        $questions[$i] = htmlspecialchars($_POST["question$i"]);
        $points[$i] = intval($_POST["points$i"]);
        $totalPoints += $points[$i];
        $answers[$i] = [
            htmlspecialchars($_POST["answer{$i}1"]),
            htmlspecialchars($_POST["answer{$i}2"]),
            htmlspecialchars($_POST["answer{$i}3"]),
            htmlspecialchars($_POST["answer{$i}4"]),
        ];
        $correctAnswers[$i] = $_POST["correct-answer$i"];
    }

    $query = "SELECT COUNT(*) AS total FROM quiz";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($result);
    $newQuizID = 'Q' . str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);
    $query = "INSERT INTO quiz (Quiz_ID, Quiz_Name, Description, Module_ID, Lecturer_ID, Total_Marks, Time_Limit) VALUES ('$newQuizID', '$quizTitle', '$description', '$module_id', '$user_ID', '$totalPoints', '$timeString')";

    $_SESSION['Quiz_Name'] = $quizTitle;

    $result = mysqli_query($connection, $query);

    if (!$result) {
        echo "Error inserting quiz: " . mysqli_error($connection);
        exit();
    }

    for ($i = 1; $i <= 10; $i++) {
        $query = "SELECT COUNT(*) AS total FROM question";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $newQuestionID = 'QS' . str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);

        $query =  "INSERT INTO question (Question_ID, Question_Title, Point, Answer1, Answer2, Answer3, Answer4, Answer, Quiz_ID) 
        VALUES ('$newQuestionID', '$questions[$i]', '{$points[$i]}', '{$answers[$i][0]}', '{$answers[$i][1]}', '{$answers[$i][2]}', '{$answers[$i][3]}', '{$correctAnswers[$i]}', '$newQuizID')";

        $result = mysqli_query($connection, $query);

        if (!$result) {
            echo "Error inserting question: " . mysqli_error($connection);
            exit();
        }
    }
    mysqli_close($connection);

    echo "<script>
    alert('Quiz submitted successfully!');
    window.location.href = 'quiz-created.php';
    </script>";
}
?>