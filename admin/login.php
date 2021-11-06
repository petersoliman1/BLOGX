<?php
    session_start();

    // file database and file the header.
    include 'initialize.php';

    // ["REQUEST_METHOD"] Check on the method that came from the form.
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        // We are also verifying that the POST is of the Login type.
        if(isset($_POST["admin-login"])) {

            // We receive our Super Global data.
            // Return the data in the form.
            $email = $_POST["email"];
            $password = $_POST["password"];
            $hashedpassword = sha1($password);
            /*
                Notes: About Security methods for the password (But I took SHA1).
                // Do not use it because (PASSWORD_DEFAULT) is stronger.
                $hashedpassword1 = sha1($password);
                $hashedpassword2 = MD5($password);
                // This is the most secure type of password (Note: Every time I enter the form, it gets an error, and we don't know why, that's why we worked Hash1).
                $hashedpassword3 = password_hash($password, PASSWORD_BCRYPT); 
            */



            // Check if there is user has data like that in database.
            $check = $connect -> prepare ("SELECT * FROM users WHERE email = ? and password = ?");  
            $check -> execute (array($email,$hashedpassword));
            $checkRow = $check -> rowCount();


            //I make a check in the row count in greater than on Zero will achieve the code and If not, this code will be achieved by else.
            if ($checkRow > 0) {  // The persone exists in database.
                
                // How do I know that this person is admin or user?
                $fetchRole = $check -> fetch();  

                // Note About Role:  This (Role) is written the same way in the Database. Please make sure you typed it in the database
                if ($fetchRole ['Role'] == 'admin') {  // The person is an admin.
                    /* Welcome message before entering the Dashboard Do not use this message because it is not professional.
                    echo "Hello, You are admin. You will redirect to database now.";
                    header ("refresh:3; url=dashboard.php");
                    exit(); */

                    // Name $__SESSION is (Admin).
                    // Note: I will complete this SESSION on the dashboard, Users and all the pages I have.
                    $_SESSION ['Admin'] = $fetchRole ['Email'];

                    // Note: This session is responsible for that the user or the email inside it gets hidden from the users table, meaning all users appear in the table except for the email that the login was used with.
                    $_SESSION ['AdminId'] = $fetchRole ['ID'];

                    header ("location:dashboard.php");
                    exit();
                } else {
                    echo "<p class='note note-danger m-1'><strong>Note danger: </strong>Sorry, You are not admin man</p>";
                }

            } else {
                echo "<p class='note note-danger m-1'><strong>Note danger: </strong>this user does not exists in db</p>";
            }

        }
    }
?>



<div class="login-dark">
    <p class="h1 text-center text-warning pt-3"><strong>Admin Login</strong></p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <h2 class="sr-only">Login Form</h2>

        <div class="illustration"><i class="fas fa-lock"></i></div>

        <div class="form-group">
            <input class="form-control" type="email" name="email" placeholder="Email">
        </div>

        <div class="form-group">
            <input class="form-control" type="password" name="password" placeholder="Password">
        </div>

        <div class="form-group">
            <button type="submit" name="admin-login" class="btn btn-primary btn-block">Log In</button>
        </div>

        <a href="#" class="forgot">Forgot your email or password?</a>
    </form>
</div>