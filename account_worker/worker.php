<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-account.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <title>Worker info</title>
</head>
<body>
    <div class="container-page">
        <div class="container2">
            <img src="../img/UserImg.png" alt="UserImage" width="150vh" height="150vh" style="border-radius: 50%;"><br>
            <?php session_start() ?>

            <h2>" <?php echo $_SESSION['username']; ?> "</h2>
            <h2 style="margin-top:1vh;">Info:</h2><br>

            <label for="1" class="form-label" style="text-align:left; font-weight:bold;">Email</label>
            <input class="form-control" id="1" type="text" value="<?php echo $_SESSION['email']; ?>" aria-label="readonly input example" readonly style="width:70%; text-align:center;"><br>

            <label for="2" class="form-label" style="text-align:left; font-weight:bold;">Name</label>
            <input class="form-control" id="2" type="text" value="<?php echo $_SESSION['name']; ?>" aria-label="readonly input example" readonly style="width:70%; text-align:center;"><br>

            <label for="3" class="form-label" style="text-align:left; font-weight:bold;">Surname</label>
            <input class="form-control" id="3" type="text" value="<?php echo $_SESSION['surname']; ?>" aria-label="readonly input example" readonly style="width:70%; text-align:center;"><br>

            <label for="4" class="form-label" style="text-align:left; font-weight:bold;">Date of Birth</label>
            <input class="form-control" id="4" type="text" value="<?php echo $_SESSION['date']; ?>" aria-label="readonly input example" readonly style="width:70%; text-align:center;">
            
            <div class="buttons">
                    <form method="post" action="worker.php">
                        <button type="submit" class="btn btn-primary" name="edit">Edit</button><br><br>
                        <button name="logout" type="submit" class="btn btn-secondary">Logout</button>
                    </form>
            </div>
        </div>

        <div class="container-activities">
            <div class="container-buttons">
                <a href="workerAll.php" target="activity-frame" style="border-right:1px solid black; text-decoration:none; color: black;" class="activity"><div>All</div></a>
                <a href="workerNew.php" target="activity-frame" style="border-right:1px solid black; text-decoration:none; color: black;" class="activity"><div>New</div></a>
                <a href="workerActivated.php" target="activity-frame" style="border-right:1px solid black; text-decoration:none; color: black;" class="activity"><div>Activated</div></a>
                <a href="workerResolved.php" target="activity-frame" style="border-right:1px solid black; text-decoration:none; color: black;" class="activity"><div>Solved</div></a>
                <a href="Users.php" target="activity-frame" style="text-decoration:none; color: black;" class="activity"><div>Users</div></a>
            </div>
            <iframe frameborder="0" src="workerAll.php" height="90%" name="activity-frame"></iframe>
        </div>
    </div>
</body>
</html>

<?php
    if(isset($_POST['logout'])){
        session_destroy();
        header("Location:../index.php");
        exit();
    }
    if(isset($_POST['edit'])){
        header("Location:../editPage.php");
        exit();
    }
?>