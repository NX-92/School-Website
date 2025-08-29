<?php
    $server = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'school_system';

    $connection = mysqli_connect($server, $user, $password, $database);

    if (!$connection) {
        die('Connection Failed! ' . mysqli_connect_error());
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $firstName = mysqli_real_escape_string($connection, trim($_POST['Register_First_Name']));
        $lastName = mysqli_real_escape_string($connection, trim($_POST['Register_Last_Name']));
        $contactNumber = mysqli_real_escape_string($connection, trim($_POST['Register_Contact_Number']));
        $email = mysqli_real_escape_string($connection, trim($_POST['Register_Email_Address']));
        $IC_Number = mysqli_real_escape_string($connection, trim($_POST['Register_IC_Number']));
        $password = trim($_POST['Register_Password']);
        $register_date = date("Y-m-d");
        $role = mysqli_real_escape_string($connection, trim($_POST['Register_Role']));

        if (strlen($firstName) < 1 || strlen($firstName) > 20 || strlen($lastName) < 1 || strlen($lastName) > 20) {
            die("<script>alert('First and Last Name must be between 1 and 20 characters.'); window.location.href='register-page.html';</script>");
        }

        if (!preg_match('/^\d{3}-\d{7,8}$/', $contactNumber)) {
            die("<script>alert('Invalid Contact Number format. Use xxx-xxxxxxx or xxx-xxxxxxxx'); window.location.href='register-page.html';</script>");
        }

        if (strlen($password) < 1 || strlen($password) > 20) {
            die("<script>alert('Password must be between 1 and 20 characters.'); window.location.href='register-page.html';</script>");
        }

        if (!preg_match('/^\d{6}-\d{2}-\d{4}$/', $IC_Number)) {
            die("<script>alert('Invalid IC Number format. Use xxxxxx-xx-xxxx'); window.location.href='register-page.html';</script>");
        }

        $query = "SELECT Email_Address, IC_Number FROM student WHERE Email_Address = '$email' OR IC_Number = '$IC_Number'
        UNION
        SELECT Email_Address, IC_Number FROM lecturer WHERE Email_Address = '$email' OR IC_Number = '$IC_Number'";
        
        $result = mysqli_query($connection, $query);

        if (mysqli_num_rows($result) > 0) {
            die("<script>alert('Email or IC Number already exists. Please use a different one.'); window.location.href = 'register-page.html';</script>");
        }

        if ($role == '1') {
            $query = "SELECT COUNT(*) AS total FROM student";
            $result = mysqli_query($connection, $query);
            $row = mysqli_fetch_assoc($result);
            $newStudentID = 'S' . str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);

            $query = "INSERT INTO student (Student_ID, Student_First_Name, Student_Last_Name, Email_Address, Contact_Number, IC_Number, Password, Register_Date, Course_ID) 
                VALUES ('$newStudentID', '$firstName', '$lastName', '$email', '$contactNumber', '$IC_Number', '$password', '$register_date', 'C001')";
        } else {
            $query = "SELECT COUNT(*) AS total FROM lecturer";
            $result = mysqli_query($connection, $query);
            $row = mysqli_fetch_assoc($result);
            $newLecturerID = 'L' . str_pad($row['total'] + 1, 3, '0', STR_PAD_LEFT);

            $query = "INSERT INTO Lecturer (Lecturer_ID, Lecturer_First_Name, Lecturer_Last_Name, Email_Address, Contact_Number, IC_Number, Password, Register_Date) 
                VALUES ('$newLecturerID', '$firstName', '$lastName', '$email', '$contactNumber', '$IC_Number', '$password', '$register_date')";
        }

        if (mysqli_query($connection, $query)) {
            echo "<script>
                    alert('User Registered Successfully!');
                    window.location.href = 'login-page.html';
                </script>";
        } else {
            echo "<script>
                    alert('Error: Could not register user. Please try again.');
                    window.location.href = 'register-page.html';
                </script>";
        }
    }

    mysqli_close($connection);
?>
