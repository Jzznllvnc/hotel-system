<?php

$Email = $password = "";
$EmailErr = $passwordErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["Email"])) {
        $EmailErr = "Email is Required";
    } else {
        $Email = $_POST["Email"];
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is Required";
    } else {
        $password = $_POST["password"];
    }
    if ($Email && $password) {
        include("connection.php");
        $check_email = mysqli_query($conn, "SELECT * FROM login_tbl WHERE Email = '$Email'");
        $check_email_row = mysqli_num_rows($check_email);
        if ($check_email_row > 0) {
            while ($row = mysqli_fetch_assoc($check_email)) {
                $db_password = $row["password"];
                $db_account_type = $row["account_type"];
                if ($db_password === $password) {
                    if ($db_account_type == "1") {
                        echo "<script>
                                alert('Logged in as admin!');
                                window.location.href = 'admin';
                              </script>";    
                    }
                    else {
                        echo "<script>
                                alert('Logged in as user!');
                                window.location.href = 'user';
                              </script>";
                    }
                } else {
                    $passwordErr = "Password is incorrect";
                }
            }
        }
        else {
            $EmailErr = "Email is not registered";
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jazz Login</title>
    <link rel="stylesheet" href="loginuistyle.css">
</head>
<body>
    <div class="container">
        <div class="main">  
            <input type="checkbox" id="chk" aria-hidden="true">
    
            <div class="login">
                <form class="form" method="post" action="login-ui.php">
                    <label for="chk" aria-hidden="true">Log in</label>
                    <input class="input" type="text" name="Email" placeholder="Email">
                    
                    <?php if (!empty($EmailErr)): ?>
                        <span class="error"><?php echo $EmailErr; ?></span>
                    <?php endif; ?>

                    <input class="input" type="password" name="password" placeholder="Password">
                    
                    <?php if (!empty($passwordErr)): ?>
                        <span class="error"><?php echo $passwordErr; ?></span>
                    <?php endif; ?>
                    
                    <a href="#" class="forgot-password">Forgot Password?</a>
                    <button class="login-button" type="submit">Log in</button>
                </form>
                
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                </form>
            </div>
        </div>
    </div>
</body>
</html>
