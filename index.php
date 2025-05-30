<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Homepage</title>
</head>
<body borderline="0">
    <div class="container-page">
        <h1 style="font-size:3.5em; font-weight:bold;">Welcome to my website!</h1>
        <br><br>
        <div class="login-container">
            <h2 class="w-100">Login</h2>
            <form class=" w-100" method="post" action="index.php">
                <label for="Username" class="form-label" style="text-align:left;">Username</label>
                <div class="input-group mb-3 w-100">
                    <input type="text" autocomplete="off" class="form-control w-100" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1"  name="UserName" id="Username">
                </div><br>

                <label for="inputPassword5" class="form-label w-100" style="text-align:left;">Password</label>
                <input type="password" id="inputPassword5" class="form-control w-100" aria-describedby="passwordHelpBlock" name="Passwd">
                <br><br>
                <button type="submit" class="btn btn-primary w-100" name="send" style="width:100%;">Login</button>
            </form>
            <a style="text-align: center;" href="register.php">You haven't still an account? Register here.</a>
        </div>
    </div>
</body>
</html>

<?php
    if(isset($_POST['send'])){
        include "functions.php";
        searchUser();
    }
?>