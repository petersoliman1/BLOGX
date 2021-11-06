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
            $statement = $connect -> prepare ("SELECT * FROM categories WHERE ID != ?"); 
            // Note:- ($_SESSION ['AdminId']) => This session is responsible for that the user or the email inside it gets hidden from the users table, meaning all users appear in the table except for the email that the login was Category with.
            $statement->execute(array($_SESSION ['AdminId']));        // Execute the qeury.
            $allcategories = $statement -> fetchAll ();   // To fatch all the data or Show all the data.
            $categoriesCount = $statement -> rowcount();       // Return number of rows.
        ?>
            <div class="row">
                <div class="col-md-12 col-lg-12">

                    <!--
                        {Example} [href="?page=AddUser&AddParameter2"]
                        The question mark (?) means that it will complete in the page via the URL, But after that, I want to add another parameter, which is a sign and (&).
                        But the first parameter takes a question mark (?)
                    -->
                    <a href="?page=AddCategory" class="btn btn-success mb-3">Add New Categories</a>

                    <!-- Start Card -->
                    <div class="card">
                        <div class="card-header">
                            <h6>Categories Management 
                                <span class="badge bg-secondary">
                                    <?php echo $categoriesCount ?>
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
                                        <th scope="col">Status</th>
                                        <th scope="col">Controls</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Show the table -->
                                    <?php  if ($categoriesCount > 0) { 
                                        
                                        foreach ($allcategories as $category) 
                                    { ?>
                                        
                                    <tr>
                                        <th scope="row"><?php echo $category['ID'] ?></th>
                                        <td><?php echo $category['Title'] ?></td>
                                        <td><?php echo $category['Description'] ?></td>
                                        <td>
                                            <?php
                                                if ($category['Status'] == 0) {
                                                    echo "<span class='badge bg-danger'>Pending</span>";
                                                } else {
                                                    echo "<span class='badge bg-info'>Approved</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a title="Show" class="btn btn-outline-primary"  
                                                href='?page=ShowCategory&categoryid=<?php echo $category['ID']?>'>
                                                <i class="far fa-eye"></i>
                                            </a>

                                            <a title="Edit" class="btn btn-outline-success"
                                                href='?page=ShowOldCategory&categoryid=<?php echo $category['ID']?>'>
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a title="Delete" class="btn btn-outline-danger confirmDelete"
                                                href='?page=DeleteCategory&categoryid=<?php echo $category['ID']?>'>
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
        --------- Show Row Category -----------
        -------------------------------------->
        <?php } elseif ($page == "ShowCategory") {
            
            if (isset($_GET['categoryid']) && !empty($_GET['categoryid']) && is_numeric($_GET['categoryid'])) {
                $categoryid = intval($_GET['categoryid']);   // intvale = integer value.
            } else {
                $categoryid = '';       // I am enter ID in URL
            }
            
            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM categories WHERE ID = ? ");   //Return user row.
            $check -> execute(array($categoryid));        // Execute the qeury.
            $categoryRowCount = $check -> rowcount();       // Return number of rows.

            if ($categoryRowCount > 0) {

                $categoryInfo = $check -> fetch();   // To fatch the data or Show one row the data.

                ?>

                <div class="row">
                    <div class="col-md-12 col-lg-12">

                        <!-- Start Card -->
                        <div class="card">
                            <div class="card-header">
                                <h6>User Management 
                                    <span class="badge bg-secondary">
                                        <?php echo $categoryRowCount ?>
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
                                            <th scope="col">Status</th>
                                            <th scope="col">Controls</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Show the table -->
                                        <?php  if ($categoryInfo > 0) { 
                                            
                                        { ?>
                                            
                                            <tr>
                                        <th scope="row"><?php echo $categoryInfo['ID'] ?></th>
                                        <td><?php echo $categoryInfo['Title'] ?></td>
                                        <td><?php echo $categoryInfo['Description'] ?></td>
                                        <td>
                                            <?php
                                                if ($categoryInfo['Status'] == 0) {
                                                    echo "<span class='badge bg-danger'>Pending</span>";
                                                } else {
                                                    echo "<span class='badge bg-info'>Approved</span>";
                                                }
                                            ?>
                                        </td>
                                        <td>
                                            <a title="Edit" class="btn btn-outline-success"  
                                                href='?page=ShowOldCategory&categoryid=<?php echo $categoryInfo['ID']?>'>
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <a title="Delete" class="btn btn-outline-danger confirmDelete"  
                                                href='?page=DeleteCategory&categoryid=<?php echo $categoryInfo['ID']?>'>
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
        } elseif ($page == "ShowOldCategory") {

            if (isset($_GET['categoryid']) && !empty($_GET['categoryid']) && is_numeric($_GET['categoryid'])) {
                $categoryid = intval($_GET['categoryid']);   // intvale = integer value.
            } else {
                $categoryid = '';       // I am enter ID in URL
            }

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM categories WHERE ID = ? ");   //Return user row.
            $check -> execute(array($categoryid));        // Execute the qeury.
            $rowsShowOldCount = $check -> rowcount();       // Return number of rows.

            if ($rowsShowOldCount > 0) {

                $Oldcategory = $check -> fetch();   // To fatch the data or Show one row the data.
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
                                    <form action="?page=UpdateCategory" method="POST">

                                        <!-- Title input -->
                                        <div class="form-outline mb-4">
                                            <input value="<?php echo $Oldcategory['Title'] ?>" name="title" type="text" id="form6Example1" class="form-control" />
                                            <label class="form-label" for="form6Example1">Title</label>
                                        </div>

                                        <!-- Description input -->
                                        <div class="form-outline mb-4">
                                            <textarea value="<?php echo $Oldcategory['Description'] ?>" name="description" id="form6Example2" class="form-control" cols="30" rows="5" type="text"><?php echo $Oldcategory['Description'] ?></textarea>
                                            <label class="form-label" for="form6Example2">Description</label>
                                        </div>

                                        <!-- Status input -->
                                        <div class="form-outline mb-4">
                                            <label class="form-label" for="form6Example3">Status</label>
                                            <br>
                                            <input
                                            <?php 
                                                if ($Oldcategory['Status'] === '0') {
                                                    echo 'checked';
                                                } else {
                                                    echo '';
                                                }
                                            ?>
                                            type="radio" name="status" value="0" id="form6Example3" /> Pending

                                            <input
                                            <?php 
                                                if ($Oldcategory['Status'] === '1') {
                                                    echo 'checked';
                                                } else {
                                                    echo '';
                                                }
                                            ?>
                                            type="radio" name="status" value="1" id="form6Example3" /> Approved
                                        </div>

                                        <!-- To make an update for data. -->
                                        <input type="hidden" name="categoryid" value="<?php echo $Oldcategory['ID'] ?>">

                                        <!-- Submit button -->
                                        <input type="submit" class="btn btn-primary btn-block mb-4" name="save-category" value="Save category" />
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
        <?php } elseif ($page == "UpdateCategory") {
            
            // Update Query.
            if($_SERVER["REQUEST_METHOD"] == "POST") {

                // Receiving data from the form and entering it into variables.
                $title = $_POST["title"];
                $description = $_POST["description"];
                $status = $_POST["status"];
                $categoryid = $_POST["categoryid"];

                // Query to Update database.
                //Return update row.
                $updatestetment = $connect -> prepare ("UPDATE categories SET `Title`= ?, `Description`=?, `Status`=?, `Updated_at`=now() WHERE ID=? ");
                $updatestetment -> execute(array($title, $description, $status, $categoryid));        // Execute the qeury.
                $updateRow = $updatestetment -> rowCount();

                if ($updateRow > 0) {
                    echo "<p class='note note-success m-1'><strong>Note success: </strong>Category Has Been Update Successfully</p>";
                    header ("refresh:3; url=categories.php");
                    exit();
                }
            } 
        
        /* ---------------------------------- 
        ------- Delete Row Category --------
        --------------------------------- */
        } elseif ($page == "DeleteCategory") {
                
            if (isset($_GET['categoryid']) && !empty($_GET['categoryid']) && is_numeric($_GET['categoryid'])) {
                $categoryid = intval($_GET['categoryid']);   // intvale = integer value.
            } else {
                $categoryid = '';       // I am enter ID in URL
            }
            

            // Query to check if user exists in database.
            $check = $connect -> prepare ("SELECT * FROM categories WHERE ID = ? ");   //Return user row.
            $check -> execute (array($categoryid));        // Execute the qeury.
            $categoryRowCount = $check -> rowcount();       // Return number of rows.
            
            // So he found the id => Delete query
            if ($categoryRowCount > 0) {
                $deleteStatement = $connect -> prepare ("DELETE FROM categories WHERE ID = ? ");  
                $deleteStatement -> execute (array($categoryid));
                $deleteRow = $deleteStatement -> rowcount();       // Return number of rows.

                // Has user has been deleted.
                if ($deleteRow > 0) { 
                    //Delete at the same moment and Redirect to page after query executed.
                    header ("location:categories.php");
                    exit();
                }
                

            } else {
                echo "Can not delete this id!";
            }
        
        /* ------------------------------ 
        ------- Add New Category --------
        ------------------------------- */
        } elseif ($page == "AddCategory") { ?>
            
            <div class="bgAddUser mt-2">
                <h2 class="hedingAddUser">Add New Category</h2>
                
                <form action="?page=SaveCategory" method="POST">

                    <!-- Title input -->
                    <div class="form-outline mb-4">
                        <input name="title" type="text" id="form6Example1" class="form-control" />
                        <label class="form-label" for="form6Example1">Title</label>
                    </div>

                    <!-- Description input -->
                    <div class="form-outline mb-4">
                        <textarea name="description" id="form6Example2" class="form-control" cols="30" rows="5" type="text"></textarea>
                        <label class="form-label" for="form6Example2">Description</label>
                    </div>

                    <!-- Submit button -->
                    <input type="submit" class="btn btn-primary btn-block mb-4" name="save-Category" value="Save Category" />
                </form>
            </div>

        
        <!------------------------------- 
        --------- Save Category ---------
        --------------------------------->
        <?php } elseif ($page == "SaveCategory") { 
            
            if($_SERVER["REQUEST_METHOD"] == "POST") {
                // name="save-user" back to input (submit) he send check from daata.
                if(isset($_POST["save-Category"])) {

                    // Variables for hold error.
                    $titleError = $descriptionError = "";

                    // Receiving data from the form and entering it into variables.
                    $title = $_POST["title"];
                    $description = $_POST["description"];
                    
                    ///////////// Title Validation //////////////////////////
                    // If the input title is empty or error write this.
                    if (!empty($title)) {
                        $title = filter_var($title, FILTER_SANITIZE_STRING);
                    } else {
                        $titleError[] = "Title is required";
                    }

                    ///////////// Description Validation //////////////////////////
                    // If the input description is empty or error write this.
                    if (!empty($description)) {
                        $description = filter_var($description, FILTER_SANITIZE_STRING);
                    } else {
                        $descriptionError[] = "Description is required";
                    }

                    // Check if there is no errors.
                    if (empty($titleError) && empty($descriptionError)) {

                        // Insert into data.
                        $statement = $connect -> prepare
                        ("INSERT INTO `categories`(`Title`, `Description`, `Status`, `Created_at`)
                        VALUES (:ztitle, :zdescription, :zstatus, now())
                        ");

                        $statement -> execute (array(
                            'ztitle' => $title ,
                            'zdescription' => $description ,
                            'zstatus' => '1'
                        ));

                        // The rowcount to verify that the data is correct.
                        if ($statement -> rowcount() > 0) {
                            echo "<p class='note note-success m-1'><strong>Note success: </strong>Category Has Been Created Successfully</p>";
                            header ("refresh:3; url=categories.php");
                            exit();
                        }

                    
                    } else {
                        echo "<p class='note note-danger m-1'><strong>Note danger: </strong>Sorry, Please enter the data</p>";
                        header ("location:categories.php?page=AddCategory");
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