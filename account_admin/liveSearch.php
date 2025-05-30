<?php
    include "../functions.php";
    if(isset($_POST["searchValue"])){
        writeTable($_POST["state"]);
    }
    
    if(isset($_POST["searchUser"])){
        writeUsersTable(1,$_POST["searchUser"]);
    }
?>