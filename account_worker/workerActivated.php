<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-account.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Worker-Activated</title>
</head>

<body borderline="0" style="background-color: white; padding:3vh; padding-top:0;">
    
<form class="input-group mb-3 mt-3 w-25 searchBar" method="post">
        <button class="btn btn-primary" type="submit" id="searchForm" name="searchAll">Search</button>
        <input onkeyup='$.ajax({
                method:"POST",
                url: "liveSearch.php",
                data:{
                    "searchValue": $("input.form-control").val(),
                    "state": 2
                }
            })
            .done(function(response){
            $("table").html(response);
            });' name="filter" id="filter" type="text" class="form-control searchBar" autocomplete="off" placeholder="Username..." aria-label="SearchBar" aria-describedby="searchForm">
    </form>

    <h2 style="margin-top:2vh;margin-bottom:2vh;">Activated activities</h2>
    <?php include "../functions.php"; showAdminActivity(1);?>
</body>

</html>

<script>
    function downloadPage(){
        window.location.href = "../report.php";
    }
</script>

<?php 
    if(isset($_POST["options"])) {
        changeState(2,0);
        return;
    }
    if(isset($_POST["sendDate"])) {
        changeDate($_POST["sendDate"], $_POST["date"],2);
        exit();
    }
    if(isset($_POST["editDate"])) {
        header("workerActivated.php");
        exit();
    }
?>