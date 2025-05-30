<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style-account.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <title>Admin-all</title>
</head>

<body borderline="0" style="background-color: white; padding:3vh;">
    <h2 style="margin-bottom:3vh;">All activities</h2>

    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        Create an activity
    </button>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog" action="userAll.php" method="POST">
            <form class="modal-content" method="POST" action="userAll.php">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Create activity</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="exampleFormControlInput1" class="form-label md">Title</label>
                        <input name="title" type="text" class="form-control" id="exampleFormControlInput1"
                            placeholder="Title here...">
                    </div>
                    <div class="form-floating">
                        <textarea name="content" class="form-control" placeholder="Leave a comment here"
                            id="floatingTextarea2" style="height: 100px"></textarea>
                        <label for="floatingTextarea2">Content</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button name="create" type="submit" class="btn btn-primary" id="createB">Send</button>
                </div>
            </form>
        </div>
    </div>


    <br><br>
    <?php include "../functions.php";
    showUserActivities(); ?>
</body>

</html>

<script>
    function downloadPage() {
        window.location.href = "../report.php";
    }
</script>

<?php
if (isset($_POST['create'])) {
    createActivity();
    $URL = "userAll.php";
    echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
    echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
    debug_to_console(("ok"));
    return;
}
?>