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

<div class="" id="post-management">
    <div class="container">

        <!--------------------------------- 
        ---- Show the All row on table ----
        ----------------------------------->
        <!-- Query to display all table Usrs from database. -->
        <?php if ($page == "AllShowTable") {  // Show or Read or Return the data.

            // Return all  (Posts table)  rows.
            $statement = $connect -> prepare 
            ("SELECT posts.* , users.Username AS Username , 
            users.ID AS USERID , categories.Title AS CategoriesTitle
            FROM posts 
            INNER JOIN users 
            ON posts.User_ID = users.ID
            INNER JOIN categories
            ON posts.Category_ID = Categories.ID
            ");

            // Note:- ($_SESSION ['AdminId']) => This session is responsible for that the user or the email inside it gets hidden from the users table, meaning all users appear in the table except for the email that the login was used with.
            $statement->execute(array($_SESSION ['AdminId']));        // Execute the qeury.
            $allposts = $statement -> fetchAll ();   // To fatch all the data or Show all the data.
            $postsCount = $statement -> rowcount();       // Return number of rows.
        ?>
            <div class="row">
                <div class="col-md-12 col-lg-12">

                    <!--
                        {Example} [href="?page=AddUser&AddParameter2"]
                        The question mark (?) means that it will complete in the page via the URL, But after that, I want to add another parameter, which is a sign and (&).
                        But the first parameter takes a question mark (?)
                    -->
                    <a href="?page=AddPost" class="btn btn-success mb-3">Add New Post</a>

                    <!-- Start Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Posts Management 
                                <span class="badge bg-secondary">
                                    <?php echo $postsCount ?>
                                </span> 
                            </h6>
                        </div>
                        <div class="card-body overflow-auto">
                            <!-- Start the table -->

                            <table class="table text-center table-responsive">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Title</th>
                                        <th scope="col">Description</th>
                                        <th scope="col">Image</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Category</th>
                                        <th scope="col">Publisher</th>
                                        <th scope="col">Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Show the table -->
                                    <?php  if ($postsCount > 0) { 
                                        
                                        foreach ($allposts as $post) 
                                    { ?>
                                        
                                    <tr>
                                        <th scope="row"><?php echo $post['ID'] ?></th>
                                        <td><?php echo $post['Title'] ?></td>
                                        <td><?php echo $post['Description'] ?></td>
                                        <td>
                                            <?php
                                                echo "<a target='_blank' href='uploads/posts/" . $post['Image'] . "'>";
                                                echo "<img style='width:60px; height:60px; border-radius:5px' src='uploads/posts/" . $post['Image'] . " ' alt=''>";
                                                echo "</a>";
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                if ($post['Status'] == 0) {
                                                    echo "<span class='badge bg-danger'>Hidden</span>";
                                                } else {
                                                    echo "<span class='badge bg-info'>Visible</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <?php
                                                echo '<a href="categories.php?page=ShowCategory&categoryid=' . $post['Category_ID'] . '">';
                                                echo $post['CategoriesTitle'];
                                                echo '</a>';
                                            ?>
                                        </td>
                                        <td>
                                            <?php 
                                                echo '<a href="users.php?page=ShowUser&userid=' . $post['USERID'] . '">';
                                                echo $post['Username'];
                                                echo '</a>';
                                            ?>
                                        </td>
                                        <td>
                                            <a title="Show" class="btn btn-outline-primary"  
                                                href='?page=ShowPost&postid=<?php echo $post['ID']?>'>
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <a title="Edit" class="btn btn-outline-success"  
                                                href='?page=ShowOldPost&postid=<?php echo $post['ID']?>'>
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <br>

                                            <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                href='?page=DeletePost&postid=<?php echo $post['ID']?>'>
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
        ------------- Show Row Post -----------
        -------------------------------------->
        <?php } elseif ($page == "ShowPost") {

            if (isset($_GET['postid']) && !empty($_GET['postid']) && is_numeric($_GET['postid'])) {
                $postid = intval($_GET['postid']);   // intvale = integer value.
            } else {
                $postid = '';       // I am enter ID in URL
            }

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM posts");   //Return user row.
            $check -> execute(array($postid));        // Execute the qeury.
            $postRowCount = $check -> rowcount();       // Return number of rows.

            if ($postRowCount > 0) {
                $postInfo = $check -> fetch();   // To fatch the data or Show one row the data.

                ?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">

                        <!-- Start Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6>User Management 
                                    <span class="badge bg-secondary">
                                        <?php echo $postRowCount ?>
                                    </span> 
                                </h6>
                            </div>
                            <div class="card-body overflow-auto">

                                <!-- Start the table -->
                                <table class="table table-responsive">
                                    <thead class="table-dark">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Title</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Image</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Show the table -->
                                        <?php  if ($postInfo > 0) { 
                                            
                                        { ?>
                                            
                                        <tr>
                                            <th scope="row"><?php echo $postInfo['ID'] ?></th>
                                            <td><?php echo $postInfo['Title'] ?></td>
                                            <td><?php echo $postInfo['Description'] ?></td>
                                            <td>
                                                <?php
                                                    echo "<a target='_blank' href='uploads/posts/" . $postInfo['Image'] . "'>";

                                                    echo "<img style='width:60px; height:60px; border-radius:5px' src='uploads/posts/" . $postInfo['Image'] . " ' alt=''>";

                                                    echo "</a>";
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                    if ($postInfo['Status'] == 0) {
                                                        echo "<span class='badge bg-danger'>Hidden</span>";
                                                    } else {
                                                        echo "<span class='badge bg-info'>Visible</span>";
                                                    }
                                                ?>
                                            </td>
                                            <td>
                                                <a title="Edit" class="btn btn-outline-success"  
                                                    href='?page=ShowOldPost&postid=<?php echo $postInfo['ID']?>'>
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                    href='?page=DeletePost&postid=<?php echo $postInfo['ID']?>'>
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


        /* ------------------------------------- 
        ----------- Delete Row Post ------------
        ------------------------------------- */
        } elseif ($page == "DeletePost") {
                
            if (isset($_GET['postid']) && !empty($_GET['postid']) && is_numeric($_GET['postid'])) {
                $postid = intval($_GET['postid']);   // intvale = integer value.
            } else {
                $postid = '';       // I am enter ID in URL
            }
            

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM posts WHERE ID = ? ");   //Return user row.
            $check -> execute (array($postid));        // Execute the qeury.
            $postRowCount = $check -> rowcount();       // Return number of rows.
            
            // So he found the id => Delete query
            if ($postRowCount > 0) {

                $deleteStatement = $connect -> prepare ("DELETE FROM posts WHERE ID = ? ");  
                $deleteStatement -> execute (array($postid));
                $deleteRow = $deleteStatement -> rowcount();       // Return number of rows.

                // Has user has been deleted.
                if ($deleteRow > 0) { 
                    //Delete at the same moment and Redirect to page after query executed.
                    header ("location:posts.php");
                    exit();
                }
                

            } else {
                echo "Can not delete this id!";
            }
        
        /* ------------------------------------- 
        ------------ Add New Post --------------
        ------------------------------------- */
        }  elseif ($page == "AddPost") { ?>

            <div class="bgAddUser mt-2">
                <h2 class="hedingAddUser">Add New Post</h2>
                <p>Let's create your Post!</p>
                
                <form action="?page=SavePost" method="POST" enctype="multipart/form-data">

                    <!-- Title input -->
                    <div class="form-outline mb-4">
                        <input name="title" type="text" id="form6Example1" class="form-control" />
                        <label class="form-label" for="form6Example1">Title</label>
                    </div>

                    <!-- Description input -->
                    <div class="form-outline mb-4">
                        <textarea name="description" id="form6Example2" class="form-control" cols="30" rows="5"></textarea>
                        <label class="form-label" for="form6Example2">Description</label>
                    </div>

                    <!-- Image input -->
                    <div class="form-outline mb-4">
                        <input name="postImage" type="file" id="form6Example3" class="form-control" />
                        <label class="form-label" for="form6Example3">Post Image</label>
                    </div>

                    <!-- Status input -->
                    <!-- <div class="form-outline mb-4">
                        <label class="form-label" for="form6Example4">Status</label>
                        <br>
                        <input 
                        type="radio" name="status" value="0" id="form6Example4"> Hidden
                        <input 
                        type="radio" name="status" value="1" id="form6Example4"> Visible
                    </div> -->

                    <!-- Category_ID input -->
                    <div class="form-outline mb-4">
                        <select name="category_id" id="form6Example5" class="form-control">
                            <option readOnly>-- Choose Category</option>
                            <?php
                                $selectCategory = $connect -> prepare ("SELECT * FROM categories");
                                $selectCategory -> execute();        
                                $allCategories = $selectCategory -> fetchAll();    // retern all the data.
                                
                                foreach ($allCategories as $Category) {
                                    // I sold the ID Category and Title.
                                    echo "<option value='". $Category['ID'] ."'>" . $Category['ID'] . "- " . $Category['Title'] ."</option>";
                                }
                            ?>
                        </select>
                        <label class="form-label" for="form6Example5">-- Choose Category</label>
                    </div>

                    <!-- User_ID input -->
                    <div class="form-outline">
                        <!-- The person who made the login is the one who will download the post. -->
                        <input value="<?php echo $_SESSION['AdminId']?>" name="user_id" type="hidden" class="form-control" />
                    </div>

                    <!-- Submit button -->
                    <input type="submit" class="btn btn-primary btn-block mb-4" name="save-post" value="Save Post" />
                </form>
            </div>
            
        <!------------------------------------- 
        -------------- Save Post --------------
        -------------------------------------->
        <?php } elseif ($page == "SavePost") { 
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                // name="save-user" back to input (submit) he send check from daata.
                if(isset($_POST["save-post"])) {

                    // Variables for hold error.
                    $postFormErrors = array();

                    // Receiving data from the form and entering it into variables.
                    $title = $_POST["title"];
                    $description = $_POST["description"];
                    // $status = $_POST["status"];
                    $category_id = $_POST["category_id"];
                    $user_id = $_POST["user_id"];

                    // Image          // variable of file type.
                    // $testImage = $_FILES["postImage"];          // Test Image only.
                    $imageName = $_FILES["postImage"] ["name"];    // 2.png = Name photo.
                    $imageSize = $_FILES["postImage"] ["size"];    // Image size in bits.
                    $imageTmp = $_FILES["postImage"] ["tmp_name"]; // Temporay path.
                    $imageType = $_FILES["postImage"] ["type"];    // Image/png.
                    
                    // Name and Extension Photo.
                    // explode => In order to separate a String and transform it into an Array, it consists of two parts, the first part through the Separator and the second part is an input String.
                    $imageExtension1 = explode('.', $imageName);
                    // strtolwer => Because it is case sensitive, so if the extension is uppercase, it must be rejected. Lowercase 
                    // end => This is the last part, which is the image extension.
                    $imageExtension2 = strtolower(end($imageExtension1));
                    
                    // photo extension.
                    $allowedExtensions = array("jpeg", "jpg", "png", "gif");   

                    ///////////// Title Validation //////////////////////////
                    // If the input title is empty or error write this.
                    if (!empty($title)) {
                        $title = filter_var($title, FILTER_SANITIZE_STRING);
                    } else {
                        $postFormErrors[] = "Title is required";
                    }

                    ///////////// Description Validation //////////////////////////
                    // If the input description is empty or error write this.
                    if (!empty($description)) {
                        $description = filter_var($description, FILTER_SANITIZE_STRING);
                    } else {
                        $postFormErrors[] = "Description is required";
                    }

                    ///////////// Image Validation //////////////////////////
                    // If the image extension is not available .
                    if (!in_array($imageExtension2, $allowedExtensions)) {
                        $postFormErrors[] = "This extension photo is not allowed to upload, Please the extension must be (jpeg, jpg, png, gif)";
                    }

                    // If the image size is larger than the size I entered.
                    if ($imageSize > 1048576) {    // 1 MB = 1024 * 1024 KB
                        $postFormErrors[] = "Your image is grater then 1 MB";
                    }

                    ///////////// Status Validation //////////////////////////
                    // If the input status is empty or error write this.
                    // if (!empty($status)) {
                    //     // Work on it override
                    //     // $status = $status;
                    //     $status = filter_var($status, FILTER_SANITIZE_NUMBER_INT);
                    // } else {
                    //     $postFormErrors[] = "Status is required";
                    // }
                    
                    ///////////// category_id Validation //////////////////////////
                    // If the input category_id is empty or error write this.
                    if (!empty($category_id)) {
                        // Work on it override
                        $category_id = $category_id;
                    } else {
                        $postFormErrors[] = "categoryId is required";
                    }

                    ///////////// user_id Validation //////////////////////////
                    // If the input user_id is empty or error write this.
                    if (!empty($user_id)) {
                        // Work on it override
                        $user_id = $user_id;
                    } else {
                        $postFormErrors[] = "userId is required";
                    }

                    // Check if there is no errors.
                    if (empty($postFormErrors)) {

                        // Change image name to avoid duplicates.
                        // Move image from temp path to new path.
                        // Take image name to insert query.
                        $finalImage = rand(0, 1000) . "_" . $imageName;

                        // to move file form place to another (tmp path, new path, new image name). 
                        move_Uploaded_File($imageTmp, "uploads/posts/". $finalImage);

                        // Insert into data.
                        $statement = $connect -> prepare
                        ("INSERT INTO `posts`(`Title`, `Description`, `Image`, `Status`, `User_ID`, `Category_ID`, `Created_at`)
                        VALUES (:ztitle, :zdescription, :zimage, :zstatus, :zuserid,  :zcategoryid , now())
                        ");

                        $statement -> execute (array(

                            'ztitle' => $title ,
                            'zdescription' => $description ,
                            'zimage' => $finalImage ,
                            'zstatus' => "1" ,
                            'zuserid' => $user_id ,
                            'zcategoryid' => $category_id
                        ));

                        // The rowcount to verify that the data is correct.
                        if ($statement -> rowcount() > 0) {
                            echo "<p class='note note-success m-1'><strong>Note success: </strong>post Has Been Created Successfully</p>";
                            header ("refresh:3; url=posts.php");
                            exit();
                        }

                    } else {
                        echo "<p class='note note-danger m-5'><strong>Note danger: </strong>There Are Errors, Please enter all the fields because it is required</p>";
                        print_r($postFormErrors);
                    }
                }
            }
        /* -------------------------------------- 
        ------- Show Old Data Row Posts ---------
        -------------------------------------- */
        } elseif ($page == "ShowOldPost") {
        
            if (isset($_GET['postid']) && !empty($_GET['postid']) && is_numeric($_GET['postid'])) {
                $postid = intval($_GET['postid']);   // intvale = integer value.
            } else {
                $postid = '';       // I am enter ID in URL
            }
        
            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM posts WHERE ID = ? ");   //Return user row.
            $check -> execute(array($postid));        // Execute the qeury.
            $rowsShowOldCount = $check -> rowcount();       // Return number of rows.

            if ($rowsShowOldCount > 0) {
                $OldPost = $check -> fetch();   // To fatch the data or Show one row the data.
            } ?>

            <div class="row">
                <div class="col-md-12 col-lg-12">

                    <!-- Start Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Update Post 
                                <span class="badge bg-secondary">
                                    <?php echo $rowsShowOldCount ?>
                                </span> 
                            </h6>
                        </div>
                        <div class="card-body overflow-auto">
                            <!-- Start the Form -->

                            <div class="bgAddUser mt-2">
                                <form action="?page=UpdatePost" method="POST" enctype="multipart/form-data">

                                    <!-- Title input -->
                                    <div class="form-outline mb-4">
                                        <input value="<?php echo $OldPost['Title'] ?>" name="title" type="text" id="form6Example1" class="form-control" />
                                        <label class="form-label" for="form6Example1">Title</label>
                                    </div>

                                    <!-- Description input -->
                                    <div class="form-outline mb-4">
                                        <textarea name="description" id="form6Example2" class="form-control" cols="30" rows="5" type="text"><?php echo $OldPost['Description'] ?></textarea>
                                        <label class="form-label" for="form6Example2">Description</label>
                                    </div>

                                    <!-- Image input -->
                                    <div class="form-outline mb-4">
                                        <input name="postImageUpdate" type="file" id="form6Example3" class="form-control" />
                                        <label class="form-label" for="form6Example3">Image</label>
                                    </div>

                                    <!-- Status input -->
                                    <div class="form-outline mb-4">
                                        <label class="form-label" for="form6Example4">Status</label>
                                        <br>
                                        <input
                                        <?php 
                                            if ($OldPost['Status'] === '0') {
                                                echo 'checked';
                                            } else {
                                                echo '';
                                            }
                                        ?>
                                        type="radio" name="status" value="0" id="form6Example4" /> Hidden

                                        <input
                                        <?php 
                                            if ($OldPost['Status'] === '1') {
                                                echo 'checked';
                                            } else {
                                                echo '';
                                            }
                                        ?>
                                        type="radio" name="status" value="1" id="form6Example4" /> Visible
                                    </div>

                                    <!-- Category_ID input -->
                                    <div class="form-outline mb-4">
                                        <select name="category_id" id="form6Example5" class="form-control">
                                            <option readOnly>-- Choose Category</option>
                                            <?php
                                                $selectCategory = $connect -> prepare ("SELECT * FROM categories");
                                                $selectCategory -> execute();        
                                                $allCategories = $selectCategory -> fetchAll();    // retern all the data.

                                                $selectPost = $connect -> prepare ("SELECT Category_ID FROM Posts WHERE ID = {$postid}");
                                                $selectPost -> execute();        
                                                $post = $selectPost -> fetch();    // retern all the data.
                                                
                                                foreach ($allCategories as $Category) {
                                                    // I sold the ID Category and Title.
                                                    ?>
                                                    <option value =" <?php echo $Category['ID'] ; ?>" <?php 
                                                    if($post['Category_ID'] == $Category['ID']) {echo 'selected';} ?>><?php echo $Category['Title']; ?> </option>
                                                    <?php
                                                }
                                            ?>
                                        </select>
                                        <label class="form-label" for="form6Example5">-- Choose Category</label>
                                    </div>

                                    <!-- To make an update for data. == User_ID == Hidden-->
                                    <input type="hidden" name="postid" value="<?php echo $OldPost['ID'] ?>">

                                    <!-- Submit button -->
                                    <input type="submit" class="btn btn-primary btn-block mb-4" name="save-post" value="Save Post" />
                                </form>
                            </div>

                            <!-- End The Form -->
                        </div>
                    </div>
                <!-- End Card -->
                </div>
            </div>

        <!--------------------------------- 
        --------- Update Row Post ---------
        ----------------------------------->
        <?php } elseif ($page == "UpdatePost") {
            
            // Update Query.
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                // Receiving data from the form and entering it into variables.
                $title = $_POST["title"];
                $description = $_POST["description"];
                $status = $_POST["status"];
                $categoryid = $_POST["category_id"];
                $postid = $_POST["postid"];

                // Image          // variable of file type.
                // $testImage = $_FILES["postImageUpdate"];          // Test Image only.
                $imageName = $_FILES["postImageUpdate"] ["name"];    // 2.png = Name photo.
                $imageSize = $_FILES["postImageUpdate"] ["size"];    // Image size in bits.
                $imageTmp = $_FILES["postImageUpdate"] ["tmp_name"]; // Temporay path.
                $imageType = $_FILES["postImageUpdate"] ["type"];    // Image/png.
                
                // Name and Extension Photo.
                // explode => In order to separate a String and transform it into an Array, it consists of two parts, the first part through the Separator and the second part is an input String.
                $imageExtension1 = explode('.', $imageName);
                // strtolwer => Because it is case sensitive, so if the extension is uppercase, it must be rejected. Lowercase 
                // end => This is the last part, which is the image extension.
                $imageExtension2 = strtolower(end($imageExtension1));
                
                // photo extension.
                $allowedExtensions = array("jpeg", "jpg", "png", "gif");   

                // Change image name to avoid duplicates.
                // Move image from temp path to new path.
                // Take image name to insert query.
                $finalImage = rand(0, 1000) . "_" . $imageName;

                // to move file form place to another (tmp path, new path, new image name). 
                move_Uploaded_File($imageTmp, "uploads/posts/". $finalImage);



                // Query to Update database.
                //Return update row.
                $updatestetment = $connect -> prepare ("UPDATE posts SET `Title`= ?, `Description`=?, `Image`=?, `Status`=?, `Category_ID`=?, `Updated_at`=now() WHERE ID=? ");
                $updatestetment -> execute(array($title, $description, $finalImage, $status, $categoryid, $postid));        // Execute the qeury.
                $updateRow = $updatestetment -> rowCount();

                if ($updateRow > 0) {
                    echo "<p class='note note-success m-1'><strong>Note success: </strong>Post Has Been Update Successfully</p>";
                    header ("refresh:3; url=posts.php");
                    exit();
                }
            }
        
        } ?>
        
        




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