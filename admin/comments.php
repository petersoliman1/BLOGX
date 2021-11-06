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

            // Return all  (Category table)  rows.
            $statement = $connect -> prepare 
            ("SELECT comments.* , users.Username AS Username , 
            users.ID AS USERID , posts.Title AS PostsTitle
            FROM comments 
            INNER JOIN users 
            ON comments.User_ID = users.ID
            INNER JOIN posts
            ON comments.post_ID = Posts.ID
            "); 

            // Note:- ($_SESSION ['AdminId']) => This session is responsible for that the user or the email inside it gets hidden from the users table, meaning all users appear in the table except for the email that the login was Category with.
            $statement->execute(array($_SESSION ['AdminId']));        // Execute the qeury.
            $allComments = $statement -> fetchAll ();   // To fatch all the data or Show all the data.
            $commentsCount = $statement -> rowcount();       // Return number of rows.
        ?>
            <div class="row">
                <div class="col-md-12 col-lg-12">

                    <!--
                        {Example} [href="?page=AddUser&AddParameter2"]
                        The question mark (?) means that it will complete in the page via the URL, But after that, I want to add another parameter, which is a sign and (&).
                        But the first parameter takes a question mark (?)
                    -->
                    <a href="?page=AddComment" class="btn btn-success mb-3">Add New Comments</a>

                    <!-- Start Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Comments Management 
                                <span class="badge bg-secondary">
                                    <?php echo $commentsCount ?>
                                </span> 
                            </h6>
                        </div>
                        <div class="card-body overflow-auto">
                            <!-- Start the table -->

                            <table class="table text-center table-responsive">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Comment</th>
                                        <th scope="col">Publisher</th>
                                        <th scope="col">Post</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Show the table -->
                                    <?php  if ($commentsCount > 0) { 
                                        
                                        foreach ($allComments as $comment) 
                                    { ?>
                                        
                                    <tr>
                                        <th scope="row"><?php echo $comment['ID'] ?></th>
                                        <td><?php echo $comment['Comment'] ?></td>
                                        <td>
                                            <?php 
                                                echo '<a href="users.php?page=ShowUser&userid=' . $comment['USERID'] . '">';
                                                echo $comment['Username'];
                                                echo '</a>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                echo '<a href="posts.php?page=ShowPost&postid=' . $comment['Post_ID'] . '">';
                                                echo $comment['PostsTitle'];
                                                echo '</a>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($comment['Status'] == 0) {
                                                    echo "<span class='badge bg-danger'>Hidden</span>";
                                                } else {
                                                    echo "<span class='badge bg-info'>Visible</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a title="Show" class="btn btn-outline-primary"  
                                                href='?page=ShowComment&commentid=<?php echo $comment['ID']?>'>
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <a title="Edit" class="btn btn-outline-success"  
                                                href='?page=ShowOldComment&commentid=<?php echo $comment['ID']?>'>
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                href='?page=DeleteComment&commentid=<?php echo $comment['ID']?>'>
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
        --------- Show Row commment -----------
        -------------------------------------->
        <?php } elseif ($page == "ShowComment") {
            
            if (isset($_GET['commentid']) && !empty($_GET['commentid']) && is_numeric($_GET['commentid'])) {
                $commentid = intval($_GET['commentid']);   // intvale = integer value.
            } else {
                $commentid = '';       // I am enter ID in URL
            }
            
            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM comments WHERE ID = ? ");   //Return user row.
            $check -> execute(array($commentid));        // Execute the qeury.
            $commentRowCount = $check -> rowcount();       // Return number of rows.

            if ($commentRowCount > 0) {

                $commentInfo = $check -> fetch();   // To fatch the data or Show one row the data.

                ?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">

                        <!-- Start Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6>Comment Management 
                                    <span class="badge bg-secondary">
                                        <?php echo $commentRowCount ?>
                                    </span> 
                                </h6>
                            </div>
                            <div class="card-body overflow-auto">

                                <!-- Start the table -->
                                <table class="table table-responsive">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Comment</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Show the table -->
                                        <?php  if ($commentInfo > 0) { 
                                            
                                        { ?>
                                            
                                        <tr>
                                            <th scope="row"><?php echo $commentInfo['ID'] ?></th>
                                            <td><?php echo $commentInfo['Comment'] ?></td>
                                            <td>
                                                <?php
                                                    if ($commentInfo['Status'] == 0) {
                                                        echo "<span class='badge bg-danger'>Hidden</span>";
                                                    } else {
                                                        echo "<span class='badge bg-info'>Visible</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <a title="Edit" class="btn btn-outline-success"  
                                                    href='?page=ShowOldComment&commentid=<?php echo $commentInfo['ID']?>'>
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                    href='?page=DeleteComment&commentid=<?php echo $commentInfo['ID']?>'>
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
        
        /*----------------------------------------- 
        ------- Show Old Data Row Category --------
        ----------------------------------------- */
        } elseif ($page == "ShowOldComment") {

            if (isset($_GET['commentid']) && !empty($_GET['commentid']) && is_numeric($_GET['commentid'])) {
                $commentid = intval($_GET['commentid']);   // intvale = integer value.
            } else {
                $commentid = '';       // I am enter ID in URL
            }

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM comments WHERE ID = ? ");   //Return user row.
            $check -> execute(array($commentid));        // Execute the qeury.
            $rowsShowOldCount = $check -> rowcount();       // Return number of rows.

            if ($rowsShowOldCount > 0) {

                $Oldcomment = $check -> fetch();   // To fatch the data or Show one row the data.
            } ?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">

                        <!-- Start Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6>Update Category 
                                    <span class="badge bg-secondary">
                                        <?php echo $rowsShowOldCount ?>
                                    </span> 
                                </h6>
                            </div>
                            <div class="card-body overflow-auto">
                                <!-- Start the Form -->

                                <div class="bgAddCategory mt-2">
                                    <form action="?page=UpdateComment" method="POST">

                                        <!-- Comment input -->
                                        <div class="form-outline mb-4">
                                            <textarea name="comment" id="form6Example2" class="form-control" cols="30" rows="5" type="text"><?php echo $Oldcomment['Comment'] ?></textarea>
                                            <label class="form-label" for="form6Example2">Comment</label>
                                        </div>

                                        <!-- Status input -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form6Example3">Status</label>
                                            <br>
                                            <input
                                            <?php 
                                                if ($Oldcomment['Status'] === '0') {
                                                    echo 'checked';
                                                } else {
                                                    echo '';
                                                }
                                            ?>
                                            type="radio" name="status" value="0" id="form6Example3" /> Pending

                                            <input
                                            <?php 
                                                if ($Oldcomment['Status'] === '1') {
                                                    echo 'checked';
                                                } else {
                                                    echo '';
                                                }
                                            ?>
                                            type="radio" name="status" value="1" id="form6Example3" /> Approved
                                        </div>

                                        <!-- To make an update for data. -->
                                        <input type="hidden" name="commentid" value="<?php echo $Oldcomment['ID'] ?>">

                                        <!-- Submit button -->
                                        <input type="submit" class="btn btn-primary btn-block mb-4" name="save-comment" value="Save comment" />
                                    </form>
                                </div>

                                <!-- End The Form -->
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
        
        <!------------------------------- 
        ------- Update Row Category --------
        --------------------------------->
        <?php } elseif ($page == "UpdateComment") {
            
            // Update Query.
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                // Receiving data from the form and entering it into variables.
                $comment = $_POST["comment"];
                $status = $_POST["status"];
                $commentid = $_POST["commentid"];

                // Query to Update database.
                //Return update row.
                $updatestetment = $connect -> prepare ("UPDATE comments SET `Comment`=?, `Status`=?, `Updated_at`=now() WHERE ID=? ");
                $updatestetment -> execute(array($comment, $status, $commentid));        // Execute the qeury.
                $updateRow = $updatestetment -> rowCount();

                if ($updateRow > 0) {
                    echo "<p class='note note-success m-1'><strong>Note success: </strong>Comment Has Been Update Successfully</p>";
                    header ("refresh:3; url=comments.php");
                    exit();
                }
            } 
        
        /* ----------------------------------------- 
        ----------- Delete Row Category ------------
        ------------------------------------------ */
        } elseif ($page == "DeleteComment") {
                
            if (isset($_GET['commentid']) && !empty($_GET['commentid']) && is_numeric($_GET['commentid'])) {
                $commentid = intval($_GET['commentid']);   // intvale = integer value.
            } else {
                $commentid = '';       // I am enter ID in URL
            }
            

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM comments WHERE ID = ? ");   //Return user row.
            $check -> execute (array($commentid));        // Execute the qeury.
            $commentRowCount = $check -> rowcount();       // Return number of rows.
            
            // So he found the id => Delete query
            if ($commentRowCount > 0) {
                $deleteStatement = $connect -> prepare ("DELETE FROM comments WHERE ID = ? ");  
                $deleteStatement -> execute (array($commentid));
                $deleteRow = $deleteStatement -> rowcount();       // Return number of rows.

                // Has user has been deleted.
                if ($deleteRow > 0) { 
                    //Delete at the same moment and Redirect to page after query executed.
                    header ("location:comments.php");
                    exit();
                }
                

            } else {
                echo "Can not delete this id!";
            }
        
        
            /* ----------------------------------------- 
        ------------- Add New Comment --------------
        ------------------------------------------ */
        } elseif ($page == "AddComment") { ?>
            
            <div class="bgAddUser mt-2">
                <h2 class="hedingAddUser">Add New Comment</h2>
                
                <form action="?page=SaveComment" method="POST">

                    <!-- Comment input -->
                    <div class="form-outline mb-4">
                        <textarea name="comment" id="form6Example1" class="form-control" cols="30" rows="5"></textarea>
                        <label class="form-label" for="form6Example1">Comment</label>
                    </div>

                    <!-- User_ID input -->
                    <div class="form-outline">
                        <!-- The person who made the login is the one who will download the post. -->
                        <input value="<?php echo $_SESSION['AdminId']?>" name="user_id" type="hidden" class="form-control" />
                    </div>

                    <!-- Post_ID input -->
                    <div class="form-outline mb-4">
                        <select name="post_id" id="form6Example2" class="form-control">
                            <option readOnly>-- Choose Category</option>
                            <?php
                                $selectPosts = $connect -> prepare ("SELECT * FROM posts");
                                $selectPosts -> execute();        
                                $allPosts = $selectPosts -> fetchAll();    // retern all the data.
                                
                                foreach ($allPosts as $Post) {
                                    // I sold the ID Category and Title.
                                    echo "<option value='". $Post['ID'] ."'>" . $Post['ID'] . "- " . $Post['Title'] ."</option>";
                                }
                            ?>
                        </select>
                        <label class="form-label" for="form6Example2">-- Choose Category</label>
                    </div>

                    <!-- Submit button -->
                    <input type="submit" class="btn btn-primary btn-block mb-4" name="saveComment1" value="Save Comment" />
                </form>
            </div>

        
        <!------------------------------- 
        --------- Save Comments ---------
        --------------------------------->
        <?php } elseif ($page == "SaveComment") { 
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // name="save-user" back to input (submit) he send check from daata.
                if(isset($_POST["saveComment1"])) {

                    // Variables for hold error.
                    $commentError = $postIdErrors = "";

                    // Receiving data from the form and entering it into variables.
                    $comment = $_POST["comment"];
                    $post_id = $_POST["post_id"];
                    $user_id = $_POST["user_id"];

                    ///////////// Comment Validation //////////////////////////
                    // If the input description is empty or error write this.
                    if (!empty($comment)) {
                        $comment = filter_var($comment, FILTER_SANITIZE_STRING);
                    } else {
                        $commentError[] = "Comment is required";
                    }

                    ///////////// User_id Validation //////////////////////////
                    // If the input user_id is empty or error write this.
                    // if (!empty($user_id)) {
                    //     // Work on it override
                    //     $user_id = $user_id;
                    // } else {
                    //     $userIdErrors[] = "UserId is required";
                    // }

                    ///////////// Post_id Validation //////////////////////////
                    // If the input category_id is empty or error write this.
                    if (!empty($post_id)) {
                        // Work on it override
                        $post_id = $post_id;
                    } else {
                        $postIdErrors[] = "PostID is required";
                    }

                    // Check if there is no errors.
                    if (empty($commentError) && empty($postIdErrors)) {

                        // Insert into data.
                        $statement = $connect -> prepare
                        ("INSERT INTO `comments`(`Comment`, `User_ID`, `Post_ID`, `Status`, `Created_at`)
                        VALUES (:zcomment, :zuserid, :zpostid, :zstatus, now())
                        ");

                        $statement -> execute (array(
                            'zcomment' => $comment ,
                            'zuserid' => $user_id ,
                            'zpostid' => $post_id ,
                            'zstatus' => '1'
                        ));

                        // The rowcount to verify that the data is correct.
                        if ($statement -> rowcount() > 0) {
                            echo "<p class='note note-success m-1'><strong>Note success: </strong>Comment Has Been Created Successfully</p>";
                            header ("refresh:3; url=comments.php");
                            exit();
                        }

                    
                    } else {
                        echo "<p class='note note-danger m-1'><strong>Note danger: </strong>Sorry, Please enter the data</p>";
                        header ("location:categories.php?page=AddComment");
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