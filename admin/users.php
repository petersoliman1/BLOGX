<?php
    session_start();

    // Solve a problem Set configuration and header redirect.
    ob_start() ;

    // Here on the page I will write the Database and Header.
    include 'initialize.php';

    // If you registered through the login page, use this code is under code.
    if (isset($_SESSION['Admin'])) {

    // Include file templates Navbar.
    include 'includes/templates/navbar.php';

    // Sigel page show all row tabel.
    // This code is responsible for changing pages automatically.
    $page = "";
    // If there is a variable of type GET in the URL.
    if (isset($_GET['page'])) {
        // Come and put the value of it called Page.
        $page = $_GET['page'];
    } else {  // else if there is no variable
        // Do a default value for it, in page = "All"
        $page = "AllShowTable";
    }
?>

<div class="" id="user-management">
    <div class="container">

        <!--------------------------------- 
        ---- Show the All row on table ----
        ----------------------------------->
        <!-- Query to display all table Usrs from database. -->
        <?php if ($page == "AllShowTable") {  // Show or Read or Return the data.

            // Return all  (Users table)  rows.
            $statement = $connect -> prepare ("SELECT * FROM users WHERE ID != ?"); 
            // Note:- ($_SESSION ['AdminId']) => This session is responsible for that the user or the email inside it gets hidden from the users table, meaning all users appear in the table except for the email that the login was used with.
            $statement->execute(array($_SESSION ['AdminId']));        // Execute the qeury.
            $allusers = $statement -> fetchAll ();   // To fatch all the data or Show all the data.
            $usersCount = $statement -> rowcount();       // Return number of rows.
        ?>
            <div class="row">
                <div class="col-md-12 col-lg-12">

                    <!--
                        {Example} [href="?page=AddUser&AddParameter2"]
                        The question mark (?) means that it will complete in the page via the URL, But after that, I want to add another parameter, which is a sign and (&).
                        But the first parameter takes a question mark (?)
                    -->
                    <a href="?page=AddUser" class="btn btn-success mb-3">Add New User</a>

                    <!-- Start Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6>User Management 
                                <span class="badge bg-secondary">
                                    <?php echo $usersCount ?>
                                </span> 
                            </h6>
                        </div>
                        <div class="card-body overflow-auto">
                            <!-- Start the table -->

                            <table class="table text-center table-responsive">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Role</th>
                                        <th scope="col">Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Show the table -->
                                    <?php  if ($usersCount > 0) { 
                                        
                                        foreach ($allusers as $user) 
                                    { ?>
                                        
                                    <tr>
                                        <th scope="row"><?php echo $user['ID'] ?></th>
                                        <td><?php echo $user['Username'] ?></td>
                                        <td><?php echo $user['Email'] ?></td>
                                        <td>
                                            <?php
                                                if ($user['Status'] == 0) {
                                                    echo "<span class='badge bg-danger'>Pending</span>";
                                                } else {
                                                    echo "<span class='badge bg-info'>Approved</span>";
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo $user['Role'] ?></td>
                                        <td>
                                            <a title="Show" class="btn btn-outline-primary"  
                                                href='?page=ShowUser&userid=<?php echo $user['ID']?>'>
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <a title="Edit" class="btn btn-outline-success"  
                                                href='?page=ShowOldUser&userid=<?php echo $user['ID']?>'>
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                href='?page=DeleteUser&userid=<?php echo $user['ID']?>'>
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>

                                    <?php 
                                            }
                                        } else {
                                            echo "Empty Table";
                                        }
                                    ?>

                                </tbody>
                            </table>
                            <!-- End the table -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            </div>

        <!------------------------------------- 
        ------- Show Row User --------
        -------------------------------------->
        <?php } elseif ($page == "ShowUser") {
            
            if (isset($_GET['userid']) && !empty($_GET['userid']) && is_numeric($_GET['userid'])) {
                $userid = intval($_GET['userid']);   // intvale = integer value.
            } else {
                $userid = '';       // I am enter ID in URL
            }
            
            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM users WHERE ID = ? ");   //Return user row.
            $check -> execute(array($userid));        // Execute the qeury.
            $userRowCount = $check -> rowcount();       // Return number of rows.

            if ($userRowCount > 0) {

                $userInfo = $check -> fetch();   // To fatch the data or Show one row the data.

                ?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">

                        <!-- Start Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6>User Management 
                                    <span class="badge bg-secondary">
                                        <?php echo $userRowCount ?>
                                    </span> 
                                </h6>
                            </div>
                            <div class="card-body overflow-auto">

                                <!-- Start the table -->
                                <table class="table table-responsive">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Username</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Role</th>
                                            <th scope="col">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Show the table -->
                                        <?php  if ($userInfo > 0) { 
                                            
                                        { ?>
                                            
                                        <tr>
                                            <th scope="row"><?php echo $userInfo['ID'] ?></th>
                                            <td><?php echo $userInfo['Username'] ?></td>
                                            <td><?php echo $userInfo['Email'] ?></td>
                                            <td>
                                                <?php
                                                    if ($userInfo['Status'] == 0) {
                                                        echo "<span class='badge bg-danger'>Pending</span>";
                                                    } else {
                                                        echo "<span class='badge bg-info'>Approved</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td><?php echo $userInfo['Role'] ?></td>
                                            <td>
                                                <a title="Update" class="btn btn-outline-success"  
                                                    href='?page=ShowOldUser&userid=<?php echo $userInfo['ID']?>'>
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                    href='?page=DeleteUser&userid=<?php echo $userInfo['ID']?> '>
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>

                                        <?php 
                                                }
                                            } else {
                                                echo "Empty Table";
                                            }
                                        ?>

                                    </tbody>
                                </table>
                                <!-- End the table -->
                            </div>
                        </div>
                    <!-- End Card -->
                    </div>
                </div>

            <?php } 
        
        /*------------------------------------- 
        ------- Show Old Data Row User --------
        -------------------------------------- */
        } elseif ($page == "ShowOldUser") {

            if (isset($_GET['userid']) && !empty($_GET['userid']) && is_numeric($_GET['userid'])) {
                $userid = intval($_GET['userid']);   // intvale = integer value.
            } else {
                $userid = '';       // I am enter ID in URL
            }
            
            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM users WHERE ID = ? ");   //Return user row.
            $check -> execute(array($userid));        // Execute the qeury.
            $rowsShowOldCount = $check -> rowcount();       // Return number of rows.

            if ($rowsShowOldCount > 0) {

                $OldUser = $check -> fetch();   // To fatch the data or Show one row the data.
            } ?>

            <div class="row">
                <div class="col-md-12 col-lg-12">

                    <!-- Start Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Update User 
                                <span class="badge bg-secondary">
                                    <?php echo $rowsShowOldCount ?>
                                </span> 
                            </h6>
                        </div>
                        <div class="card-body overflow-auto">
                            <!-- Start the Form -->

                            <div class="bgAddUser mt-2">
                                <form action="?page=UpdateUser" method="POST">
                                    <!-- UserName input -->
                                    <div class="form-outline mb-4">
                                        <input value="<?php echo $OldUser['Username'] ?>" name="username" type="text" id="form6Example1" class="form-control" />
                                        <label class="form-label" for="form6Example1">Username</label>
                                    </div>

                                    <!-- Email input -->
                                    <div class="form-outline mb-4">
                                        <input value="<?php echo $OldUser['Email'] ?>" name="email" type="email" id="form6Example2" class="form-control" />
                                        <label class="form-label" for="form6Example2">Email</label>
                                    </div>


                                    <!-- To make an update for data. -->
                                    <input type="hidden" name="userid" value="<?php echo $OldUser['ID'] ?>">

                                    <!-- Role input -->
                                    <div class="form-outline mb-4">
                                        <select name="role" id="form6Example4" class="form-control" >
                                            <option readOnly>-- Choose Role</option>
                                            <!-- If Role is Admin, select  -->
                                            <option <?php 
                                            if ($OldUser['Role'] === 'admin') {
                                                echo 'selected';
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                            value="admin">Admin
                                            </option>

                                            <!-- If Role is User, select  -->
                                            <option <?php 
                                            if ($OldUser['Role'] === 'user') {
                                                echo 'selected';
                                            } else {
                                                echo '';
                                            }
                                            ?>
                                            value="user">User
                                            </option>
                                        </select>
                                        <label class="form-label" for="form6Example4">-- Choose Role</label>
                                    </div>

                                    <!-- Status input -->
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form6Example3">Status</label>
                                        <br>
                                        <input
                                        <?php 
                                            if ($OldUser['Status'] === '0') {
                                                echo 'checked';
                                            } else {
                                                echo '';
                                            }
                                        ?>
                                        type="radio" name="status" value="0" id="form6Example3" /> Pending

                                        <input
                                        <?php 
                                            if ($OldUser['Status'] === '1') {
                                                echo 'checked';
                                            } else {
                                                echo '';
                                            }
                                        ?>
                                        type="radio" name="status" value="1" id="form6Example3" /> Approved
                                    </div>

                                    <!-- Submit button -->
                                    <input type="submit" class="btn btn-primary btn-block mb-4" name="save-user" value="Save User" />
                                </form>
                            </div>

                            <!-- End The Form -->
                        </div>
                    </div>
                    <!-- End Card -->
                </div>
            </div>

        <!------------------------------- 
        ------- Update Row User --------
        --------------------------------->
        <?php } elseif ($page == "UpdateUser") {
                
            // Update Query.
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                // Receiving data from the form and entering it into variables.
                $username = $_POST["username"];
                $email = $_POST["email"];
                $role = $_POST["role"];
                $status = $_POST["status"];
                $userid = $_POST["userid"];


                // Query to Update database.
                //Return update row.
                $updatestetment = $connect -> prepare ("UPDATE users SET `Username`= ?, `Email`=?, `Role`=?, `Status`=?, `Updated_at`=now() WHERE ID=? ");
                $updatestetment -> execute(array($username, $email, $role, $status, $userid));        // Execute the qeury.
                $updateRow = $updatestetment -> rowCount();

                if ($updateRow > 0) {
                    echo "<p class='note note-success m-1'><strong>Note success: </strong>User Has Been Update Successfully</p>";
                    header ("refresh:3; url=users.php");
                    exit();
                }
            }

        /* ----------------------------- 
        ------- Delete Row User --------
        ------------------------------- */
        } elseif ($page == "DeleteUser") {
            
            if (isset($_GET['userid']) && !empty($_GET['userid']) && is_numeric($_GET['userid'])) {
                $userid = intval($_GET['userid']);   // intvale = integer value.
            } else {
                $userid = '';       // I am enter ID in URL
            }
            

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM users WHERE ID = ? ");   //Return user row.
            $check -> execute (array($userid));        // Execute the qeury.
            $userRowCount = $check -> rowcount();       // Return number of rows.
            
            // So he found the id => Delete query
            if ($userRowCount > 0) {
                $deleteStatement = $connect -> prepare ("DELETE FROM users WHERE ID = ? ");  
                $deleteStatement -> execute (array($userid));
                $deleteRow = $deleteStatement -> rowcount();       // Return number of rows.

                // Has user has been deleted.
                if ($deleteRow > 0) { 
                    //Delete at the same moment and Redirect to page after query executed.
                    header ("location:users.php");
                    exit();
                }
                

            } else {
                echo "Can not delete this id!";
            }
        
        /* --------------------------- 
        ------- Add New User --------
        ----------------------------- */
        } elseif ($page == "AddUser") { ?>
            
            <div class="bgAddUser mt-2">
                <h2 class="hedingAddUser">Add New User</h2>
                <p>Let's create your account!</p>
                
                <form action="?page=SaveUser" method="POST">
                    <!-- UserName input -->
                    <div class="form-outline mb-4">
                        <input name="username" type="text" id="form6Example1" class="form-control" />
                        <label class="form-label" for="form6Example1">Username</label>
                    </div>

                    <!-- Email input -->
                    <div class="form-outline mb-4">
                        <input name="email" type="email" id="form6Example2" class="form-control" />
                        <label class="form-label" for="form6Example2">Email</label>
                    </div>

                    <!-- Password input -->
                    <div class="form-outline mb-4">
                        <input name="password" type="password" id="form6Example3" class="form-control" />
                        <label class="form-label" for="form6Example3">Password</label>
                    </div>

                    <!-- Role input -->
                    <div class="form-outline mb-4">
                        <select name="role" id="form6Example4" class="form-control" >
                            <option readonly>-- Choose Role</option>
                            <option value="admin">Admin</option>
                            <option value="user">User</option>
                        </select>
                        <label class="form-label" for="form6Example4">-- Choose Role</label>
                    </div>

                    <!-- Submit button -->
                    <input type="submit" class="btn btn-primary btn-block mb-4" name="save-user" value="Save User" />
                </form>
            </div>

        
        <!--------------------------- 
        --------- Save User ---------
        ----------------------------->
        <?php 
        } elseif ($page == "SaveUser") { 
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // name="save-user" back to input (submit) he send check from daata.
                if(isset($_POST["save-user"])) {

                    // Variables for hold error.
                    $usernameError = $emailError = $passwordError = $roleError = "";

                    // Receiving data from the form and entering it into variables.
                    $username = $_POST["username"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    $hashedpassword = sha1($password);
                    $role = $_POST["role"];

                    ///////////// Username Validation //////////////////////////
                    // If the input username is empty or error write this.
                    if (empty($_POST["username"])) {
                        $usernameError = "Plaese insert your username.";
                    } elseif (strlen($_POST["username"]) < 4) {
                        $usernameError = "Your username needs to have a minimum of 5 letters.";
                    } else {
                        $username = filter_var($_POST["username"], FILTER_SANITIZE_STRING);
                    }

                    ////////////////// Email Validation ///////////////////////////////
                    // If the input email is empty or error write this.
                    if (empty($_POST["email"])) {
                        $emailError = "Plaese insert your Email is required.";
                    } else {
                        $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);
                    }

                    //////////////////// Password Validation ///////////////////////////////
                    // If the input password is empty or error write this.
                    if (empty($_POST["password"])) {
                        $passwordError = "Plaese insert your password.";
                    } elseif (strlen($_POST["password"]) < 6) {
                        $passwordError = "Use 6 characters or more for your password.";
                    } else {
                        $password = filter_var($_POST["password"], FILTER_SANITIZE_STRING);
                    }

                    //////////////////// Rol Validation ///////////////////////////////
                    // If the input role is empty or error write this.
                    if (empty($_POST["role"])) {
                        $roleError = "Please choose any one.";
                    } else {
                        $role = filter_var($_POST["role"], FILTER_SANITIZE_STRING);
                    }

                    // Check if there is no errors.
                    if (empty($usernameError) && empty($emailError) && empty($passwordError) && empty($roleError)) {

                        // Insert into data.
                        $statement = $connect -> prepare
                        ("INSERT INTO `users`(`Username`, `Email`, `Password`, `Status`, `Role`, `Created_at`)
                        VALUES (:zusername, :zemail, :zpassword, :zstatus, :zrole, now())
                        ");

                        $statement -> execute (array(
                            'zusername' => $username ,
                            'zemail' => $email ,
                            'zpassword' => $hashedpassword ,
                            'zstatus' => '0' ,
                            'zrole' => $role
                        ));

                        // The rowcount to verify that the data is correct.
                        if ($statement -> rowcount() > 0) {
                            echo "<p class='note note-success m-1'><strong>Note success: </strong>User Has Been Created Successfully</p>";
                            header ("refresh:3; url=users.php");
                            exit();
                        }

                    
                    } else {
                        echo "<p class='note note-danger m-1'><strong>Note danger: </strong>Sorry, Please enter the data</p>";
                        header ("location:users.php?page=AddUser");
                        exit();
                    }

                }
            }
            

        }?>

    </div>
</div>

<?php
    // Include file templates footer.
    include 'includes/templates/footer.php';
    // Solve a problem Set configuration and header redirect.
    ob_end_flush() ;

    // If you entered the page via a URL, execute this code.
    } else {
        echo "<p class='note note-danger m-5'><strong>Note danger: </strong>You are not Authenticated</p>";
        header ("refresh:3;url=login.php");
        exit();
    }
?>