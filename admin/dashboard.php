<?php
    // In order to enter the dashboard, you must be logged in, not via a URL
    session_start();  //Create / Resume in login page.

    // Here on the page I will write the Database and Header.
    include 'initialize.php';

    // If you registered through the login page, use this code is under code.
    if (isset($_SESSION['Admin'])) {
        
    // Include file templates Navbar.
    include 'includes/templates/navbar.php';

    // Return all users (name table)  rows.
    $qeuryUsers = $connect -> prepare ("SELECT * FROM users");  
    $qeuryUsers -> execute ();        // Execute the qeury.
    $usersCount = $qeuryUsers -> rowCount();       // Return number of rows.

    // Return all posts (name table)  rows.
    $qeuryPosts = $connect -> prepare ("SELECT * FROM posts");  
    $qeuryPosts -> execute ();        // Execute the qeury.
    $postsCount = $qeuryPosts -> rowCount();       // Return number of rows.

    // Return all comments (name table)  rows.
    $qeuryComments = $connect -> prepare ("SELECT * FROM comments");  
    $qeuryComments -> execute ();        // Execute the qeury.
    $commentsCount = $qeuryComments -> rowCount();       // Return number of rows.
    
    // Return all categories (name table)  rows.
    $qeuryCategories = $connect -> prepare ("SELECT * FROM categories");  
    $qeuryCategories -> execute ();        // Execute the qeury.
    $categoriesCount = $qeuryCategories -> rowCount();       // Return number of rows.
?>

    <div id="main-dashboard">
        <div class="container">
            <h4 class="header-Dash">Dashboard</h4>

            <!--Start box Number in dashboard -->
            <div class="stats">
                <div class="row">

                    <div class="col-md-6 col-lg-3">
                        <div class="box box-color1 d-flex justify-content-around align-items-center">
                            <i class="fas fa-users"></i>
                            <div>
                                <h4><?php echo $usersCount ?></h4>
                                <h5>Users</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="box box-color2 d-flex justify-content-around align-items-center">
                        <i class="fas fa-clipboard-list"></i>
                            <div>
                                <h4><?php echo $postsCount ?></h4>
                                <h5>Posts</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="box box-color3 d-flex justify-content-around align-items-center">
                            <i class="fas fa-comments"></i>
                            <div>
                                <h4><?php echo $commentsCount ?></h4>
                                <h5>Comments</h5>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="box box-color4 d-flex justify-content-around align-items-center">
                            <i class="fas fa-shapes"></i>
                            <div>
                                <h4><?php echo $categoriesCount ?></h4>
                                <h5>Categories</h5>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!--End box Number in dashboard -->
        </div>
    


    <!-- Start Card -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,300,400,600,700,900" rel="stylesheet">

    <div class="container">
        <div class="row">
            <div class="col-md-4">
            <div class="card1 card-1">
                <h3>Ionic Native</h3>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et.
                </p>
            </div>
            </div>
            <div class="col-md-4">
            <div class="card1 card-2">
                <h3>UI Components</h3>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et.
                </p>
            </div>
            </div>
            <div class="col-md-4">
            <div class="card1 card-3">
                <h3>Theming</h3>
                <p>
                    Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et.
                </p>
            </div>
            </div>
        </div>
    </div>
    </div>
    <!-- End Card -->

<?php

    // Include file templates footer.
    include 'includes/templates/footer.php';

    // If you entered the page via a URL, execute this code.
    } else {
        echo "<p class='note note-danger m-5'><strong>Note danger: </strong>You are not Authenticated</p>";
        header ("refresh:3;url=login.php");
        exit();
    }
?>