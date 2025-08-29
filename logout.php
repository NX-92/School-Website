<?php
    session_start();

    $_SESSION = array();
    
    session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/logout.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <title>Logout</title>
</head>
<body>

    <header class="header">
        <div class="container">
            <div class="logo">
                <h2>Abbott University</h2>
            </div>
        </div>
        <hr>
    </header>

    <section class="section">
        <div class="container">
            <div class="title">
                <h2>Successfully Logged Out!</h2>
            </div>
            <div class="button-box">
                <button class="login-btn" id="login"><h3>Back to Login</h3></button>
            </div>
        </div>
    </section>

    <script>
        document.getElementById("login").addEventListener("click", function() {
            window.location.href = "login-page.html";
        });
    </script>
</body>
</html>
