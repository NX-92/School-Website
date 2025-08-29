<?php
session_start();

$server = 'localhost';
$user = 'root';
$password = '';
$database = 'school_system';

$connection = mysqli_connect($server, $user, $password, $database);
if (!$connection) {
    die('Connection Failed! ' . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($connection, trim($_POST['Login_Email_Address']));
    $password = trim($_POST['Login_Password']);

    $studentQuery = "SELECT * FROM student WHERE Email_Address = '$email'";
    $lecturerQuery = "SELECT * FROM lecturer WHERE Email_Address = '$email'";

    $studentResult = mysqli_query($connection, $studentQuery);
    $lecturerResult = mysqli_query($connection, $lecturerQuery);

    if ($studentRow = mysqli_fetch_assoc($studentResult)) {
        if ($password === $studentRow['Password']) {
            $_SESSION['Login_Student_ID'] = $studentRow['Student_ID'];
            $_SESSION['Login_Username'] = $studentRow['Student_First_Name'] . ' ' . $studentRow['Student_Last_Name'];
            $_SESSION['Login_Contact_Number'] = $studentRow['Contact_Number'];
            $_SESSION['Login_Email_Address'] = $studentRow['Email_Address'];
            $_SESSION['Login_Role'] = 'Student';
            $_SESSION['Login_IC_Number'] = $studentRow['IC_Number'];
            $_SESSION['Login_Password'] = $studentRow['Password'];
            $_SESSION['Login_Register_Date'] = $studentRow['Register_Date'];
            $studentCourseQuery = "
            SELECT course.Course_Name, 
                   lecturer.Lecturer_First_Name, 
                   lecturer.Lecturer_Last_Name,
                   lecturer.Email_Address AS Lecturer_Email_Address,
                   lecturer.Contact_Number AS Lecturer_Contact_Number
            FROM student
            INNER JOIN course ON student.Course_ID = course.Course_ID
            INNER JOIN lecturer ON course.Lecturer_ID = lecturer.Lecturer_ID
            WHERE student.Student_ID = '".$_SESSION['Login_Student_ID']."'";

            $courseResult = mysqli_query($connection, $studentCourseQuery);

            if ($courseRow = mysqli_fetch_assoc($courseResult)) {
                $_SESSION['Login_Student_Supervisor_Name'] = $courseRow['Lecturer_First_Name'] . ' ' . $courseRow['Lecturer_Last_Name'];
                $_SESSION['Login_Student_Supervisor_Email_Address'] = $courseRow['Lecturer_Email_Address'];
                $_SESSION['Login_Student_Supervisor_Contact_Number'] = $courseRow['Lecturer_Contact_Number'];
            }
            echo "<script>
                    alert('Login Successful! Welcome!!!');
                    window.location.href = 'home.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Login Failed! Incorrect Password.');
                    window.location.href = 'login-page.html';
                  </script>";
        }
    } elseif ($lecturerRow = mysqli_fetch_assoc($lecturerResult)) {
        if ($password === $lecturerRow['Password']) {
            $_SESSION['Login_Lecturer_ID'] = $lecturerRow['Lecturer_ID'];
            $_SESSION['Login_Username'] = $lecturerRow['Lecturer_First_Name'] . ' ' . $lecturerRow['Lecturer_Last_Name'];
            $_SESSION['Login_Contact_Number'] = $lecturerRow['Contact_Number'];
            $_SESSION['Login_Email_Address'] = $lecturerRow['Email_Address'];
            $_SESSION['Login_Role'] = 'Lecturer';
            $_SESSION['Login_IC_Number'] = $lecturerRow['IC_Number'];
            $_SESSION['Login_Password'] = $lecturerRow['Password'];
            $_SESSION['Login_Register_Date'] = $lecturerRow['Register_Date'];
            echo "<script>
                    alert('Login Successful! Welcome!!!');
                    window.location.href = 'home.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Login Failed! Incorrect Password.');
                    window.location.href = 'login-page.html';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Login Failed! Email not found.');
                window.location.href = 'login-page.html';
              </script>";
    }
}

mysqli_close($connection);
?>
