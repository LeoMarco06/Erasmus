<?php
    include "../functions.php";
    if(isset($_POST["searchValue"])){
        writeTable($_POST["state"]);
    }
    if(isset($_POST["searchUser"])){
        writeUsersTable(0,$_POST["searchUser"]);
    }
?>