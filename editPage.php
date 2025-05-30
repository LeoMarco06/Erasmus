<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="javascript/functions.js" type="text/javascript"></script>
    <title>Edit</title>
</head>
<body>
    <div class="container-page">
        <h1 style="margin-top:5vh; margin-bottom: 0; font-size:3.5em; font-weight:bold;">Update the Profile</h1><br>
        <div class="login-container w-50 bordersRadius mb-3 pb-0">
            <h2 style="margin-bottom: 0;">Insert the new information <br>of your account</h2>
            <form method="post" action="editPage.php"  class="w-100 needs-validation" novalidate>
                <label for="basic-addon1" class="form-label" style="text-align:left;">Username</label>
                <div class="input-group mb-3" id="userName">
                    <input required id="username" type="text" autocomplete="off" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" name="UserName">
                </div>

                <label for="Email" class="form-label" style="text-align:left;">Email</label>
                <div class="input-group mb-3" id="divEmail">
                    <span class="input-group-text" id="Email">@</span>
                    <input id="email" required type="text" autocomplete="off" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="basic-addon1" name="email">
                </div>

                <label class="form-label" style="text-align:left;">Personal data</label>
                <div class="input-group">
                    <span class="input-group-text">First and last name</span>
                    <div id="divName" style="width:38%;">
                        <input id="name" required type="text" autocomplete="off" aria-label="First name" class="form-control" name="name" placeholder="Name">
                    </div>
                    <div id="divSurname" style="width:39.5%;">
                        <input id="surname" required type="text" autocomplete="off" aria-label="Last name" class="form-control" name="surname" placeholder="Surname">
                    </div>
                </div>
                
                <label for="date" class="form-label" style="text-align:left; margin-top:1vh;">Date of Birth</label>
                <div class="input-group" id="date">
                    <span class="input-group-text">Date</span>
                    <input type="date" aria-label="Day" class="form-control" name="date">
                </div>

                <div id="divPwd">
                    <label for="Passwd" class="form-label" style="text-align:left; margin-top:1vh;">Password</label>
                    <input required type="password" id="Passwd" class="form-control" aria-describedby="passwordHelpBlock" name="Passwd">
                </div>
                <div id="passwordHelpBlock" class="form-text">
                    Your password must be 8-20 characters long, contain letters and numbers, and must not contain spaces, special characters, or emoji.
                </div>
                <br>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary" name="send" onClick='checkData()' style="margin-left:auto; margin-right:auto; width:40%;">Edit</button>
                    <button type="submit" class="btn btn-secondary" name="cancel" style="margin-left:auto; margin-right:auto; width:40%;" onClick='history.back();'>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        const forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', event => {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }

                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>

<?php
    include "functions.php";
    if(isset($_POST['send'])){
        editUser();
        exit();
    }
    if(isset($_POST['cancel'])){
        if($_SESSION['role'] == 1){
            $URL = "account_admin/admin.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        }
        else {
            $URL = "account_user/account.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        }
        exit();
    }
?>