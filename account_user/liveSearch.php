<?php
    include "../functions.php";
    if(isset($_POST["searchUser"])){
        writeUsersTable(10,$_POST["searchUser"]);
    }
?>