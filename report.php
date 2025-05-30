<!DOCTYPE html>
<html lang="en">
<head>
    <title>Report</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="style-report.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>

<body>
    <?php include "functions.php";?>
    <br><br>
    <h2>Report</h2><br>
	<div class="container" id="container">
        <div class="firstLine">
            <img src="img/logo_example.jpg" alt="logo" width="20%" height="90%">
            <h5 id="date"><span style="text-decoration:underline;">Date: <?php $timestamp = time(); $currentDate = gmdate('Y-m-d', $timestamp); echo $currentDate; ?></span></h5>
        </div><br>
        <h5 class="info">Worker: <span class="field"><?php echo $_SESSION['worker'] ?></span></h5><br>
        <h5 class="info">Customer: <span class="field"><?php echo $_SESSION['username'] ?></span></h5><br>
        <div>
            <h5 class="info">Content:</h5>
            <p>
                <?php echo $_SESSION['content'] ?>
            </p>
        </div><br>
        <h5 class="info">Info activity:</h5>
        <table class="table table-info table-striped table-hover text-center" style="margin-left:10%;margin-right:10%;width:80%;">
            <tr class="align-middle">
                <th>ID activity</th>
                <th>Title</th>
                <th>Creation date</th>
                <th>Finish date</th>
                <th>Status</th>
            </tr>
            <tr>
                <th><?php echo $_SESSION['idActivity'] ?></th>
                <th><?php echo $_SESSION['title'] ?></th>
                <th><?php echo $_SESSION['date_creation'] ?></th>
                <th><?php echo $_SESSION['date_end'] ?></th>
                <th><?php echo $_SESSION['contentState'] ?></th>
            </tr>
            
        </table><div id="containButton">
        <br><br><button id="button" class="btn btn-primary m-auto">Generate PDF</button>
        </div><br><br>
    </div>
    <br><br>
</body>

<script>
	let button = document.getElementById("button");
	button.addEventListener("click", function () {
        document.getElementById("containButton").innerHTML = "";
        document.getElementById("container").style.height = "80vh";
        window.print();
        history.back();
		return true;
	});
</script>
</html>
