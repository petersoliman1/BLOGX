<?php
    // Resume The session.
    session_start();

    // remove all session variables.
    //in write the name SESSION is ('Admin') The name of the SESSION changes according to the name of the SESSION.
    session_unset($_SESSION ['Admin']);

    // destroy the session
    session_destroy();

    // Return Login page.
    header ("location:login.php");
    exit();
?>