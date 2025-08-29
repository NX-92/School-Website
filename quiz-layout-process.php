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
        if (isset($_POST['Quiz_ID'])) {
            $quiz_id = isset($_POST['Quiz_ID']) ? $_POST['Quiz_ID'] : null;
            $time_taken = isset($_POST['time_taken']) ? $_POST['time_taken'] : 0;
        
            if ($time_taken !== null) {
                $hours = floor($time_taken / 3600);
                $minutes = floor(($time_taken % 3600) / 60);
                $seconds = $time_taken % 60;
                $formatted_time_taken = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
            } else {
                $formatted_time_taken = "00:00:00";
            }
        } else {
            echo "Quiz ID not found.";
        }
    }
    
    $query = "SELECT * FROM question WHERE Quiz_ID = '$quiz_id'";
    $result = mysqli_query($connection, $query);

    $score = 0;
    $totalPoint = 0;

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if ($result) {
            while ($question = mysqli_fetch_assoc($result)) {
                $question_id = $question['Question_ID'];
                $question_correct_answer = $question['Answer'];
                $question_point = $question['Point'];
                $totalPoint += $question_point;
    
                $selected_answer = $_POST["Question_$question_id"] ?? 'No answer selected';
    
                if ($selected_answer === $question_correct_answer) {
                    $score += $question_point;
                }
            }
        }

        $query = "SELECT COUNT(*) AS total FROM attempt";
        $result = mysqli_query($connection, $query);
        $row = mysqli_fetch_assoc($result);
        $newAttemptID = 'A' . str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);

        $attemptDate = date("Y-m-d");

        $totalmarks = ($score / $totalPoint) * 100;

        if ($totalmarks >= 90) {
            $grade = "A";
        } elseif ($totalmarks >= 80) {
            $grade = "B";
        } elseif ($totalmarks >= 70) {
            $grade = "C";
        } elseif ($totalmarks >= 60) {
            $grade = "D";
        } elseif ($totalmarks >= 50) {
            $grade = "E";
        } else {
            $grade = "F";
        }

        if ($role == "Student") {
            $query = "INSERT INTO attempt (Attempt_ID, Student_ID, Lecturer_ID, Quiz_ID, Time_Taken, Attempt_Date, Score, Grade) 
            VALUES
            ('$newAttemptID', '$user_ID', null, '$quiz_id', '$formatted_time_taken', '$attemptDate', '$totalmarks', '$grade')";
        } else {
            $query = "INSERT INTO attempt (Attempt_ID, Student_ID, Lecturer_ID, Quiz_ID, Time_Taken, Attempt_Date, Score, Grade) 
            VALUES
            ('$newAttemptID', null, '$user_ID', '$quiz_id', '$formatted_time_taken', '$attemptDate', '$totalmarks', '$grade')";
        }
        $result = mysqli_query($connection, $query);
        $_SESSION['Total_Point'] = $totalPoint;
        $_SESSION['Score'] = $score;
        echo "<script>
        window.location.href = 'quiz-result.php';
        </script>";

    } else {
        echo "<script>
        alert('Question Error');
        </script>";
        exit();
    }
    mysqli_close($connection);

?>
