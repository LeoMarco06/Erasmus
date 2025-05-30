<?php

include "functions.php";

$conn = connectToDatabase();

$sql = "SELECT * FROM activities WHERE idActivity='{$_POST['download']}'";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if($row['idActivity'] == $_POST['download']){
            $_SESSION['username'] = $row['user'];
            $_SESSION['worker'] = $row['worker'];
            $_SESSION['title'] = $row['title'];
            $_SESSION['content'] = $row['content'];
            $_SESSION['date_creation'] = $row['date_creation'];
            $_SESSION['date_end'] = $row['date_end'];
            $_SESSION['idActivity'] = $row['idActivity'];
            if($row['contentState'] == 0){
                $_SESSION['contentState'] = "New";
            }
            else if($row['contentState'] == 1){
                $_SESSION['contentState'] = "Activated";
            }
            else if($row['contentState'] == 2){
                $_SESSION['contentState'] = "Solved";
            }


            $URL="report.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
            return;
        }
    }
    // If the user is not found in the while loop, show an error message
    echo "<script>window.alert(\"Error\")</script>";
} else {
    echo "<script>window.alert(\"Error, no rows.\")</script>";
}

$conn->close();
exit();

?>