<?php
include "connection.php";
session_start();
function searchUser()
{
    $conn = connectToDatabase();

    $varName = $_POST['UserName'];
    $varPwd = $_POST['Passwd'];

    $sql = "SELECT * FROM login WHERE user='$varName'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            if ($varName == $row['user'] && $varPwd == $row['passwd']) {

                $_SESSION['username'] = $row['user'];
                $_SESSION['password'] = $row['passwd'];
                $_SESSION['name'] = $row['name'];
                $_SESSION['surname'] = $row['surname'];
                $_SESSION['email'] = $row['email'];
                $_SESSION['role'] = $row['role'];
                $_SESSION['id'] = $row['id'];
                $_SESSION['date'] = $row['date'];

                if ($row['role'] == 10) {
                    header("Location:account_user/account.php");
                } else if ($row['role'] == 1) {
                    header("Location:account_admin/admin.php");
                } else if ($row['role'] == 0) {
                    header("Location:account_worker/worker.php");
                }
                exit();
            }
        }
        // If the user is not found in the while loop, show an error message
        echo "<script>window.alert(\"Password incorrect. Please try again.\")</script>";
    } else {
        echo "<script>window.alert(\"User not exist, please insert the correct username.\")</script>";
    }

    $conn->close();
    exit();
}

function registerUser()
{

    $conn = connectToDatabase();

    $varName = $_POST['UserName'];
    $varPwd = $_POST['Passwd'];
    $Name = $_POST['name'];
    $Surname = $_POST['surname'];
    $Email = strtolower($_POST['email']);
    $date = str_replace("/", "-", $_POST['date']);
    $varRole = 10;

    // First, check if the new username already exists
    $checkSql = "SELECT user FROM login WHERE user='$varName'";

    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        while ($row = $checkResult->fetch_assoc()) {
            if ($row['user'] == $varName) {
                echo "<script>window.alert(\"Error, username already exist...\")</script>";
            }
        }
    } else {
        $sql = "INSERT INTO login (user, passwd, role,name,surname,email,date) VALUES ('$varName','$varPwd','$varRole','$Name','$Surname','$Email','$date')";

        if ($conn->query($sql) === TRUE) {
            $URL = "index.php";
            echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
            echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        } else {
            echo "<script>window.alert(\"Error " . $sql . "<br>" . $conn->error . "\")</script>";
        }
    }

    $conn->close();
    exit();
}

function editUser()
{

    $conn = connectToDatabase();

    $NewVarName = $_POST['UserName'];
    $NewVarPwd = $_POST['Passwd'];
    $NewName = $_POST['name'];
    $NewSurname = $_POST['surname'];
    $NewEmail = strtolower($_POST['email']);
    $NewDate = str_replace("/", "-", $_POST['date']);

    // First, check if the new username already exists
    $checkSql = "SELECT user FROM login WHERE user='$NewVarName'";

    $checkResult = $conn->query($checkSql);

    if ($checkResult->num_rows > 0) {
        while ($row = $checkResult->fetch_assoc()) {
            if ($row['user'] == $_SESSION['username'] && $NewVarName != $_SESSION['username']) {
                echo "<script>window.alert(\"Error, username already exist...\")</script>";
            }
        }
    } else {
        $sql = "UPDATE login SET user='$NewVarName', passwd='$NewVarPwd', name='$NewName', surname='$NewSurname', email='$NewEmail', date='$NewDate',  WHERE id='{$_SESSION['id']}'";

        if ($conn->query($sql) === TRUE) {
            $sql = "UPDATE activities SET user='$NewVarName' WHERE id='{$_SESSION['username']}'";

            if ($conn->query($sql) === TRUE) {
                echo "<script>window.alert(\"Profile updated successfully!\")</script>";
                $URL = "index.php";
                echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
                echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
            } else {
                echo "<script>window.alert(\"Error updating record: " . $conn->error . "\")</script>";
            }
        } else {
            echo "<script>window.alert(\"Error updating record: " . $conn->error . "\")</script>";
        }
    }

    $conn->close();
    exit();
}

function showActivity(int $varStatus)
{

    $conn = connectToDatabase();

    $numButtons = 0;
    $statusOk = false;
    $sql = "SELECT * FROM activities WHERE id = '" . $_SESSION['id'] . "'";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-bordered w-100 p-3 table-hover\">";
        echo "<tr class=\"align-middle\">
                        <th scope=\"col\" class=\"col-1\">Title</th>
                        <th scope=\"col\" class=\"col-3\">Content</th>
                        <th scope=\"col\" class=\"col-1\">PDF</th>
                        <th scope=\"col\" class=\"col-1\">Creation date</th>
                        <th scope=\"col\" class=\"col-1\">Status</th>
                        <th scope=\"col\" class=\"col-1\">Worker</th>
                    </tr>";
        while ($row = $result->fetch_assoc()) {
            if ($row['contentState'] == 0 && $varStatus == 0) {
                echo "<tr class=\"align-middle\"><td>{$row['title']}</td>";
                echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                        <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                            <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                            </path>
                            <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                            </path>
                        </g>
                    </svg></button></form></td>";
                echo "<td>" . $row['date_creation'] . "</td>";
                echo "<td>Sent</td>";
                if ($row['worker'] != "" && $row['worker'] != null) {
                    echo "<td>{$row['worker']}</td></tr>";
                } else {
                    echo "<td>Worker not set</td></tr>";
                }
                $statusOk = true;
            } else if ($row['contentState'] == 1 && $varStatus == 1) {
                echo "<tr class=\"align-middle\"><td>{$row['title']}</td>";
                echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                        <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                            <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                            </path>
                            <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                            </path>
                        </g>
                    </svg></button></form></td>";
                echo "<td>" . $row['date_creation'] . "</td>";
                echo "<td>Activated</td>";
                if ($row['worker'] != "" && $row['worker'] != null) {
                    echo "<td>{$row['worker']}</td></tr>";
                } else {
                    echo "<td>Worker not set</td></tr>";
                }
                $statusOk = true;
            } else if ($row['contentState'] == 2 && $varStatus == 2) {
                echo "<tr class=\"align-middle\"><td>{$row['title']}</td>";
                echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                        <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                            <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                            </path>
                            <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                            </path>
                        </g>
                    </svg></button></form></td>";
                echo "<td>" . $row['date_creation'] . "</td>";
                echo "<td>Solved</td>";
                if ($row['worker'] != "" && $row['worker'] != null) {
                    echo "<td>{$row['worker']}</td></tr>";
                } else {
                    echo "<td>Worker not set</td></tr>";
                }
                $statusOk = true;
            }
        }

    }
    if (!$statusOk) {
        if ($varStatus == 0) {
            echo "<tr><td colspan=\"6\" style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No new activities</td></tr>";
        } else if ($varStatus == 1) {
            echo "<tr><td colspan=\"6\" style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No activities activated</td></tr>";
        } else if ($varStatus == 2) {
            echo "<tr><td colspan=\"6\" style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No activities solved</td></tr>";
        }
    }
    echo "</table><br><br>";
    $conn->close();
    return $numButtons;
}

function createActivity()
{
    $conn = connectToDatabase();

    $content = $_POST['content'];
    $title = $_POST['title'];
    $currentDate = gmdate('Y-m-d', time());

    if ($content == "" || $title == "") {
        echo "<script>window.alert(\"The title and the content cannot be empty.\")</script>";
    } else {
        $sql = "INSERT INTO activities (id, content, contentState,user,title,date_creation) VALUES ('{$_SESSION['id']}','$content','0','{$_SESSION['username']}','$title','$currentDate')";
        if ($conn->query($sql) === TRUE) {
            $conn->close();
            return;
        } else {
            echo "<script>window.alert(\"Error " . $sql . "<br>" . $conn->error . "\")</script>";
        }
    }


    $conn->close();
}

function showAdminActivity(int $varStatus)
{
    $conn = connectToDatabase();

    $statusOk = false;
    $_SESSION['search'] = isset($_POST['search']);

    if (isset($_POST['search'])) {
        $sql = "SELECT * FROM activities WHERE user LIKE '%{$_POST['filter']}%';";
    } else {
        $sql = "SELECT * FROM activities";
    }
    if ($_SESSION['role'] == 0) {
        $r = "worker";
    } else {
        $r = "admin";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-bordered w-100 p-3 table-hover\">";
        echo "<tr class=\"align-middle\">
                    <th scope=\"col\" class=\"col-1\">Username</th>
                    <th scope=\"col\" class=\"col-1\">Title</th>
                    <th scope=\"col\" class=\"col-2\">Content</th>
                    <th scope=\"col\" class=\"col-1\">PDF</th>
                    <th scope=\"col\" class=\"col-1\">Creation date</th>
                    <th scope=\"col\" class=\"col-1\">End date</th>
                    <th scope=\"col\" class=\"col-2\">Status</th>";
        if ($_SESSION['role'] == 1) {
            echo "<th scope=\"col\" class=\"col-1\">Worker</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            if (($row['worker'] == $_SESSION['username']) || ($_SESSION['role'] == 1)) {
                if ($row['contentState'] == 0 && $varStatus == 0) {
                    echo "<tr class=\"align-middle\">";
                    echo "<td>{$row['user']}</td>";
                    echo "<td>{$row['title']}</td>";
                    echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                    echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                            <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                                <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                    <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                                </path>
                                <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                    <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                                </path>
                            </g>
                        </svg></button></form></td>";
                    echo "<td>{$row['date_creation']}</td>";
                    if ($row['date_end'] == null || $row['date_end'] == "") {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}New.php\">
                                    <input type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                    <button type=\"submit\" class=\"btn btn-primary w-50 mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                                </form>
                                </td>";
                    } else if (isset($_POST['editDate']) && ($row['idActivity'] == $_POST['editDate'])) {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}New.php\">
                                    <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                                </form>
                                </td>";
                        $_POST['editDate'] = null;
                    } else {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}New.php\">
                                    <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\" disabled>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                            </td>";
                    }
                    echo "<td><form style=\"width:70%;margin:auto;\" method=\"POST\">" . options($row['contentState'], $row['idActivity']) . "</form></td>";

                    if ($_SESSION['role'] == 1) {
                        if (!isset($_POST['edit'])) {
                            echo "<td>
                                <form method=\"POST\" action=\"{$r}New.php\" class=\"w-100\">
                                    <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                </form>
                                </td></tr>";
                        } else if (isset($_POST['edit']) && ($row['idActivity'] == $_POST['edit'])) {
                            echo "<td>
                                    <form method=\"POST\" action=\"{$r}New.php\" class=\"w-100\">
                                        <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"worker\" name=\"worker\" value=\"{$row['worker']}\" autocomplete=\"off\">
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"save\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                    </form>
                                    </td></tr>";
                            $_POST['edit'] = null;
                        } else {
                            echo "<td>
                                    <form method=\"POST\" action=\"{$r}New.php\" class=\"w-100\">
                                        <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                    </form>
                                    </td></tr>";
                        }
                    } else {
                        echo "</tr>";
                    }
                    $statusOk = true;
                } else if ($row['contentState'] == 1 && $varStatus == 1) {
                    echo "<tr class=\"align-middle\">";
                    echo "<td>{$row['user']}</td>";
                    echo "<td>{$row['title']}</td>";
                    echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                    echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                            <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                                <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                    <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                                </path>
                                <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                    <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                                </path>
                            </g>
                        </svg></button></form></td>";
                    echo "<td>{$row['date_creation']}</td>";
                    if ($row['date_end'] == null || $row['date_end'] == "") {
                        echo "<td>
                            <form method=\"POST\" action=\"adminActivated.php\">
                                    <input type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                    <button type=\"submit\" class=\"btn btn-primary w-50 mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                                </form>
                                </td>";
                    } else if (isset($_POST['editDate']) && ($row['idActivity'] == $_POST['editDate'])) {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}Activated.php\">
                                    <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                                </form>
                                </td>";
                        $_POST['editDate'] = null;
                    } else {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}Activated.php\">
                                    <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\" disabled>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                            </td>";
                    }
                    echo "<td><form style=\"width:70%;margin:auto;\" method=\"POST\">" . options($row['contentState'], $row['idActivity']) . "</form></td>";

                    if ($_SESSION['role'] == 1) {
                        if (!isset($_POST['edit'])) {
                            echo "<td>
                                <form method=\"POST\" action=\"{$r}Activated.php\" class=\"w-100\">
                                    <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                </form>
                                </td></tr>";
                        } else if (isset($_POST['edit']) && ($row['idActivity'] == $_POST['edit'])) {
                            echo "<td>
                                    <form method=\"POST\" action=\"{$r}Activated.php\" class=\"w-100\">
                                        <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"worker\" name=\"worker\" value=\"{$row['worker']}\" autocomplete=\"off\">
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"save\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                    </form>
                                    </td></tr>";
                            $_POST['edit'] = null;
                        } else {
                            echo "<td>
                                    <form method=\"POST\" action=\"{$r}Activated.php\" class=\"w-100\">
                                        <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                        <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                    </form>
                                    </td></tr>";
                        }
                    } else if ($_SESSION['role'] == 10) {
                        if ($row['worker'] != "" && $row['worker'] != null) {
                            echo "<td>{$row['worker']}</td></tr>";
                        } else {
                            echo "<td>Worker not set</td></tr>";
                        }
                    } else {
                        echo "</tr>";
                    }
                    $statusOk = true;
                } else if ($row['contentState'] == 2 && $varStatus == 2) {
                    echo "<tr class=\"align-middle\">";
                    echo "<td>{$row['user']}</td>";
                    echo "<td>{$row['title']}</td>";
                    echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                    echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                            <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                                <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                    <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                                </path>
                                <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                    <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                                </path>
                            </g>
                        </svg></button></form></td>";
                    echo "<td>{$row['date_creation']}</td>";
                    if ($row['date_end'] == null || $row['date_end'] == "") {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}Resolved.php\">
                                    <input type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                    <button type=\"submit\" class=\"btn btn-primary w-50 mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                                </form>
                                </td>";
                    } else if (isset($_POST['editDate']) && ($row['idActivity'] == $_POST['editDate'])) {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}Resolved.php\">
                                    <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                                </form>
                                </td>";
                        $_POST['editDate'] = null;
                    } else {
                        echo "<td>
                            <form method=\"POST\" action=\"{$r}Resolved.php\">
                                    <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\" disabled>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                    <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                            </td>";
                    }
                    echo "<td><form style=\"width:70%;margin:auto;\" method=\"POST\">" . options($row['contentState'], $row['idActivity']) . "</form></td>";

                    if ($_SESSION['role'] == 1) {
                        echo "<td><input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled></td>";
                    } else {
                        echo "</tr>";
                    }
                    $statusOk = true;
                }
            }
        }
    }
    if (!$statusOk) {
        if ($varStatus == 0) {
            echo "<tr><td colspan=\"7\" style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No new activities</td></tr>";
        } else if ($varStatus == 1) {
            echo "<tr><td colspan=\"7\" style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No activities activated</td></tr>";
        } else if ($varStatus == 2) {
            echo "<tr><td colspan=\"7\" style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No activities solved</td></tr>";
        }
    }

    echo "</table><br><br>";
    $conn->close();
    return;
}

function showAllActivities()
{
    $conn = connectToDatabase();
    $statusOk = false;

    if (isset($_POST['searchAll'])) {
        $sql = "SELECT * FROM activities WHERE user LIKE '%{$_POST['filter']}%';";
    } else {
        $sql = "SELECT * FROM activities";
    }
    if ($_SESSION['role'] == 0) {
        $r = "worker";
    } else {
        $r = "admin";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-bordered w-100 p-3 table-hover\">";
        echo "<tr class=\"align-middle\">
                <th scope=\"col\" class=\"col-1\">Username</th>
                <th scope=\"col\" class=\"col-1\">Title</th>
                <th scope=\"col\" class=\"col-2\">Content</th>
                <th scope=\"col\" class=\"col-1\">PDF</th>
                <th scope=\"col\" class=\"col-1\">Creation date</th>
                <th scope=\"col\" class=\"col-1\">End date</th>
                <th scope=\"col\" class=\"col-2\">Status</th>";
        if ($_SESSION['role'] == 1) {
            echo "<th scope=\"col\" class=\"col-1\">Worker</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            if ($row['worker'] == $_SESSION['username'] || ($r == "admin")) {
                echo "<tr class=\"align-middle\">";
                echo "<td>{$row['user']}</td>";
                echo "<td>{$row['title']}</td>";
                echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                        <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                            <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                            </path>
                            <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                            </path>
                        </g>
                    </svg></button></form></td>";
                echo "<td>{$row['date_creation']}</td>";
                if ($row['date_end'] == null || $row['date_end'] == "" || $row['date_end'] == "0000-00-00") {
                    echo "<td>
                            <form method=\"POST\" action=\"{$r}All.php\">
                                <input type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                <button type=\"submit\" class=\"btn btn-primary w-50 mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                        </td>";
                } else if (isset($_POST['editDate']) && ($row['idActivity'] == $_POST['editDate'])) {
                    echo "<td>
                        <form method=\"POST\" action=\"{$r}All.php\">
                                <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                            </td>";
                    $_POST['editDate'] = null;
                } else {
                    echo "<td>
                        <form method=\"POST\" action=\"{$r}All.php\">
                                <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\" disabled>
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                        </form>
                        </td>";
                }
                echo "<td><form style=\"width:70%;margin:auto;\" method=\"POST\">" . options($row['contentState'], $row['idActivity']) . "</form></td>";

                if ($_SESSION['role'] == 1) {
                    if ($row['contentState'] != 2) {
                        if (!isset($_POST['edit'])) {
                            echo "<td>
                            <form method=\"POST\" action=\"{$r}All.php\" class=\"w-100\">
                                <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                            </form>
                            </td>";
                        } else if (isset($_POST['edit']) && ($row['idActivity'] == $_POST['edit'])) {
                            echo "<td>
                                <form method=\"POST\" action=\"{$r}All.php\" class=\"w-100\">
                                    <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"worker\" name=\"worker\" value=\"{$row['worker']}\" autocomplete=\"off\">
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"save\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                </form>
                                </td>";
                            $_POST['edit'] = null;
                        } else {
                            echo "<td>
                                <form method=\"POST\" action=\"{$r}All.php\" class=\"w-100\">
                                    <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                </form>
                                </td>";
                        }
                    } else {
                        echo "<td><input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled></td>";
                    }
                }
                echo "</tr>";
                $statusOk = true;
            }
        }
        echo "</table><br><br>";
    }

    if (!$statusOk) {
        echo "<tr colpan=\"6\"><td style=\"margin-top: 0;margin-bottom;0: padding-top:0;\">No activities</td></tr>";
    }
    $conn->close();
    return;
}

function showUserActivities()
{
    $conn = connectToDatabase();
    $statusOk = false;

    $sql = "SELECT * FROM activities WHERE id='{$_SESSION['id']}'";

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-bordered w-100 p-3 table-hover align-middle\">";
        echo "<th scope=\"col\" class=\"col-2\">Title</th>
            <th scope=\"col\" class=\"col-4\">Content</th>
            <th scope=\"col\" class=\"col-1\">PDF</th>
            <th scope=\"col\" class=\"col-1\">Creation date</th>
            <th scope=\"col\" class=\"col-1\">Status</th>
            <th scope=\"col\" class=\"col-1\">Worker</th></tr>";
        while ($row = $result->fetch_assoc()) {
            if ($row['contentState'] == 0) {
                $state = "Sent";
            } else if ($row['contentState'] == 1) {
                $state = "Activated";
            } else if ($row['contentState'] == 2) {
                $state = "Solved";
            }
            echo "<td>{$row['title']}</td>";
            echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
            echo "<td><form action=\"../downloadPage.php\" method=\"POST\"><button type=\"submit\" value=\"{$row['idActivity']}\" name=\"download\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                    <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                        <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                            <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                        </path>
                        <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                            <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                        </path>
                    </g>
                </svg></button></form></td>";
            echo "<td>" . $row['date_creation'] . "</td>";
            echo "<td>" . $state . "</td>";
            if ($row['worker'] != "" && $row['worker'] != null) {
                echo "<td>{$row['worker']}</td></tr>";
            } else {
                echo "<td>Worker not set</td></tr>";
            }

        }
        echo "</table><br><br>";
        $statusOk = true;
    }


    if (!$statusOk) {
        echo "<tr colspan=\"5\"><td>No activities</td></tr>";
    }
    $conn->close();
    return;
}


function debug_to_console($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);
    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

function options($varStatus, $id)
{
    $string = "<select class=\"btn btn-primary\" name=\"options\" id=\"options\" style=\"height: 100%; width:100%; margin-left:5%;text-align:center;\" onchange=\"this.form.submit()\">";
    if ($varStatus == 0) {
        $string .= "<option class=\"text-start bg-light text-dark\" value=\"0/" . $id . "\" selected>New</option><option class=\"text-start bg-light text-dark\" value=\"1/" . $id . "\">Activated</option><option class=\"text-start bg-light text-dark\" value=\"2/" . $id . "\">Solved</option>";
    } else if ($varStatus == 1) {
        $string .= "<option class=\"text-start bg-light text-dark\" value=\"0/" . $id . "\">New</option><option class=\"text-start bg-light text-dark\" value=\"1/" . $id . "\" selected>Activated</option><option class=\"text-start bg-light text-dark\" value=\"2/" . $id . "\">Solved</option>";
    } else if ($varStatus == 2) {
        $string .= "<option class=\"text-start bg-light text-dark\" value=\"0/" . $id . "\">New</option><option class=\"text-start bg-light text-dark\" value=\"1/" . $id . "\">Activated</option><option class=\"text-start bg-light text-dark\" value=\"2/" . $id . "\" selected>Solved</option>";
    }
    $string .= "</select>";

    return $string;
}

function changeState($page, $role)
{
    $conn = connectToDatabase();
    $state = beforeChar($_POST['options'], "/");
    $id = afterChar($_POST['options'], "/");
    $sql = "UPDATE activities SET contentState='$state' WHERE idActivity='" . $id . "'";

    if ($conn->query($sql) === TRUE) {
        if ($page == 0) {
            if ($role == 0) {
                $URL = "workerAll.php";
            } else {
                $URL = "adminAll.php";
            }
        }
        if ($page == 1) {
            if ($role == 0) {
                $URL = "workerNew.php";
            } else {
                $URL = "adminNew.php";
            }
        }
        if ($page == 2) {
            if ($role == 0) {
                $URL = "workerActivated.php";
            } else {
                $URL = "adminActivated.php";
            }
        }
        if ($page == 3) {
            if ($role == 0) {
                $URL = "workerResolved.php";
            } else {
                $URL = "adminResolved.php";
            }
        }

        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
        $conn->close();
        return;
    } else {
        echo "<script>window.alert(\"Error " . $sql . "<br>" . $conn->error . "\")</script>";
        $conn->close();
        return;
    }
}

function beforeChar($string, $char)
{
    return strrev(substr(strrev($string), (strpos(strrev($string), $char) ?: -1) + 1));
}

function afterChar($string, $char)
{
    return substr($string, (strpos($string, $char) ?: -1) + 1);
}

function writeTable($num)
{
    $conn = connectToDatabase();
    $statusOk = false;

    if ($num == 0) {
        $sql = "SELECT * FROM activities WHERE user LIKE '%{$_POST['searchValue']}%';";

    } else if ($num == 1) {
        $sql = "SELECT * FROM activities WHERE user LIKE '%{$_POST['searchValue']}%' AND contentState = '0';";
    } else if ($num == 2) {
        $sql = "SELECT * FROM activities WHERE user LIKE '%{$_POST['searchValue']}%' AND contentState = '1';";
    } else if ($num == 3) {
        $sql = "SELECT * FROM activities WHERE user LIKE '%{$_POST['searchValue']}%' AND contentState = '2';";
    }

    if ($_SESSION['role'] == 0) {
        $r = "worker";
    } else {
        $r = "admin";
    }


    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-bordered w-100 p-3 table-hover\">";
        echo "<tr class=\"align-middle\">
                <th scope=\"col\" class=\"col-1\">Username</th>
                <th scope=\"col\" class=\"col-1\">Title</th>
                <th scope=\"col\" class=\"col-2\">Content</th>
                <th scope=\"col\" class=\"col-1\">PDF</th>
                <th scope=\"col\" class=\"col-1\">Creation date</th>
                <th scope=\"col\" class=\"col-1\">End date</th>
                <th scope=\"col\" class=\"col-2\">Status</th>";
        if ($_SESSION['role'] == 1) {
            echo "<th scope=\"col\" class=\"col-1\">Worker</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            if ($row['worker'] == $_SESSION['username'] || ($r == "admin")) {
                echo "<tr class=\"align-middle\">";
                echo "<td>{$row['user']}</td>";
                echo "<td>{$row['title']}</td>";
                echo "<td>" . contentCollapse($row['content'], $row['idActivity']) . "</td>";
                echo "<td><button onClick=\"downloadPage()\"><svg xmlns=\"http://www.w3.org/2000/svg\" width=\"1em\" height=\"1em\" viewBox=\"0 0 24 24\">
                        <g stroke=\"currentColor\" stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\">
                            <path fill=\"none\" stroke-dasharray=\"14\" stroke-dashoffset=\"14\" d=\"M6 19h12\">
                                <animate fill=\"freeze\" attributeName=\"stroke-dashoffset\" dur=\"0.4s\" values=\"14;0\" />
                            </path>
                            <path fill=\"currentColor\" d=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\">
                                <animate attributeName=\"d\" calcMode=\"linear\" dur=\"1.5s\" keyTimes=\"0;0.7;1\" repeatCount=\"indefinite\" values=\"M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5;M12 4 h2 v3 h2.5 L12 11.5M12 4 h-2 v3 h-2.5 L12 11.5;M12 4 h2 v6 h2.5 L12 14.5M12 4 h-2 v6 h-2.5 L12 14.5\" />
                            </path>
                        </g>
                    </svg></button></td>";
                echo "<td>{$row['date_creation']}</td>";
                if ($row['date_end'] == null || $row['date_end'] == "") {
                    echo "<td>
                            <form method=\"POST\" action=\"{$r}All.php\">
                                <input type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                <button type=\"submit\" class=\"btn btn-primary w-50 mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                        </td>";
                } else if (isset($_POST['editDate']) && ($row['idActivity'] == $_POST['editDate'])) {
                    echo "<td>
                        <form method=\"POST\" action=\"{$r}All.php\">
                                <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\">
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                            </form>
                            </td>";
                    $_POST['editDate'] = null;
                } else {
                    echo "<td>
                        <form method=\"POST\" action=\"{$r}All.php\">
                                <input value=\"{$row['date_end']}\" type=\"date\" aria-label=\"date\" class=\"form-control w-75 m-auto mt-1 text-center\" name=\"date\" disabled>
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"editDate\" value=\"{$row['idActivity']}\">Edit</button>
                                <button type=\"submit\" class=\"btn btn-primary w-auto mt-2\" style=\"font-size:0.75rem;\" name=\"sendDate\" value=\"{$row['idActivity']}\">Save</button>
                        </form>
                        </td>";
                }
                echo "<td><form style=\"width:70%;margin:auto;\" method=\"POST\">" . options($row['contentState'], $row['idActivity']) . "</form></td>";

                if ($_SESSION['role'] == 1) {
                    if ($row['contentState'] != 2) {
                        if (!isset($_POST['edit'])) {
                            echo "<td>
                            <form method=\"POST\" action=\"{$r}All.php\" class=\"w-100\">
                                <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                            </form>
                            </td>";
                        } else if (isset($_POST['edit']) && ($row['idActivity'] == $_POST['edit'])) {
                            echo "<td>
                                <form method=\"POST\" action=\"{$r}All.php\" class=\"w-100\">
                                    <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"worker\" name=\"worker\" value=\"{$row['worker']}\" autocomplete=\"off\">
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"save\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                </form>
                                </td>";
                            $_POST['edit'] = null;
                        } else {
                            echo "<td>
                                <form method=\"POST\" action=\"{$r}All.php\" class=\"w-100\">
                                    <input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"edit\" type=\"submit\" class=\"btn btn-primary mt-2\">Edit</button>
                                    <button style=\"font-size:0.75rem;\" value=\"{$row['idActivity']}\" name=\"error\" type=\"submit\" class=\"btn btn-primary mt-2\">Save</button>
                                </form>
                                </td>";
                        }
                    } 
                    else {
                        echo "<td><input class=\"form-control text-center\" type=\"text\" placeholder=\"{$row['worker']}\" aria-label=\"Disabled input example\" value=\"{$row['worker']}\" disabled></td>";
                    }
                }
                echo "</tr>";
                $statusOk = true;
            }
        }
        echo "</table><br><br>";
    }

    if (!$statusOk) {
        echo "<tr colspan=\"7\"><td>No activities</td></tr>";
    }
    echo "</table><br><br>";
    $conn->close();
    exit();
}

function showUsers($role)
{
    $conn = connectToDatabase();
    $statusOk = false;

    if (isset($_POST["options"])) {
        changeRole();
        exit();
    }

    if (isset($_POST['searchAll'])) {
        $sql = "SELECT * FROM login WHERE user LIKE '%{$_POST['filter']}%';";
    } else {
        $sql = "SELECT * FROM login;";
    }

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-striped w-100 p-3 table-hover\">";
        echo "<tr class=\"align-middle\"><th scope=\"col\" class=\"col-1\">Username</th>
            <th scope=\"col\" class=\"col-2\">Name and Surname</th>
            <th scope=\"col\" class=\"col-3\">Email</th>
            <th scope=\"col\" class=\"col-1\">Date of Birth</th>";
        if ($role == 1) {
            echo "<th scope=\"col\" class=\"col-1\">Role</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            if ($role == 1) {
                echo "<tr class=\"align-middle\"><td>{$row['user']}</td>";
                echo "<td>{$row['name']} {$row['surname']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['date']}</td>";
                echo "<td><form method=\"POST\">";
                echo showRole($row['role'], $row['id']);
                echo "</form></td>";
                $statusOk = true;
            } else if ($row['role'] == 0) {
                echo "<tr class=\"align-middle\"><td>{$row['user']}</td>";
                echo "<td>{$row['name']} {$row['surname']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['date']}</td>";
                $statusOk = true;
            }
            echo "</tr>";
        }
    }

    if (!$statusOk) {
        echo "<tr colspan=\"6\"><td>No users</td></tr>";
    }

    echo "</table><br><br>";
    $conn->close();
    exit();
}

function showRole($role, $id)
{
    $string = "<select class=\"btn btn-primary\" name=\"options\" id=\"options\" style=\"height: 100%; width:100%; margin-left:5%;text-align:center;\" onchange=\"this.form.submit()\">";
    if ($role == 1) {
        $string .= "<option class=\"text-start bg-light text-dark\" value=\"1/" . $id . "\" selected>Admin</option><option class=\"text-start bg-light text-dark\" value=\"0/" . $id . "\">Worker</option><option class=\"text-start bg-light text-dark\" value=\"10/" . $id . "\">User</option>";

    } else if ($role == 0) {
        $string .= "<option class=\"text-start bg-light text-dark\" value=\"1/" . $id . "\">Admin</option><option class=\"text-start bg-light text-dark\" value=\"0/" . $id . "\" selected>Worker</option><option class=\"text-start bg-light text-dark\" value=\"10/" . $id . "\">User</option>";
    } else if ($role == 10) {
        $string .= "<option class=\"text-start bg-light text-dark\" value=\"1/" . $id . "\">Admin</option><option class=\"text-start bg-light text-dark\" value=\"0/" . $id . "\">Worker</option><option class=\"text-start bg-light text-dark\" value=\"10/" . $id . "\" selected>User</option>";
    }
    $string .= "</select>";

    return $string;
}

function changeRole()
{
    $conn = connectToDatabase();
    $role = beforeChar($_POST['options'], "/");
    $id = afterChar($_POST['options'], "/");

    $sql = "UPDATE login SET role='$role' WHERE id='" . $id . "'";

    if ($conn->query($sql) === TRUE) {
        $URL = "Users.php";
        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';

        $conn->close();
        return;
    } else {
        echo "<script>window.alert(\"Error " . $sql . "<br>" . $conn->error . "\")</script>";
        $conn->close();
        return;
    }
}

function contentCollapse($content, $id)
{
    $string = "<div class=\"content-collapse\">";
    if (strlen($content) > 40) {
        $string .= "
            <div class=\"collapse\" id=\"collapseExample{$id}\" style=\"overflow-wrap: break-word;\">
            " . $content . "
            </div>    
            <span><a class=\"show-all\" data-bs-toggle=\"collapse\" href=\"#collapseExample{$id}\" role=\"button\" aria-expanded=\"false\" aria-controls=\"collapseExample/{$id}\">Show All</a></span>";
    } else {
        $string .= $content;
    }
    $string .= "</div>";
    return $string;
}

function writeUsersTable($role, $string)
{
    $conn = connectToDatabase();
    $statusOk = false;

    $sql = "SELECT * FROM login WHERE user LIKE '%{$string}%';";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        echo "<table class=\"table table-striped w-100 p-3 table-hover\">";
        echo "<tr class=\"align-middle\"><th scope=\"col\" class=\"col-1\">Username</th>
            <th scope=\"col\" class=\"col-2\">Name and Surname</th>
            <th scope=\"col\" class=\"col-3\">Email</th>
            <th scope=\"col\" class=\"col-1\">Date of Birth</th>";
        if ($role == 1) {
            echo "<th scope=\"col\" class=\"col-1\">Role</th>";
        }
        echo "</tr>";
        while ($row = $result->fetch_assoc()) {
            if ($role == 1) {
                echo "<tr class=\"align-middle\"><td>{$row['user']}</td>";
                echo "<td>{$row['name']} {$row['surname']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['date']}</td>";
                echo "<td><form method=\"POST\">";
                echo showRole($row['role'], $row['id']);
                echo "</form></td>";
                $statusOk = true;
            } else if ($row['role'] == 0) {
                echo "<tr class=\"align-middle\"><td>{$row['user']}</td>";
                echo "<td>{$row['name']} {$row['surname']}</td>";
                echo "<td>{$row['email']}</td>";
                echo "<td>{$row['date']}</td>";
                $statusOk = true;
            }
            echo "</tr>";
        }
    }

    if (!$statusOk) {
        echo "<tr colspan=\"6\"><td>No users</td></tr>";
    }

    echo "</table><br><br>";
    $conn->close();
    exit();
}

function changeWorker($idActivity, $worker, $page)
{

    $conn = connectToDatabase();

    $found = false;

    $sql = "SELECT * FROM login WHERE user='$worker'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            if ($row['role'] == 0) {
                $found = true;
                break;
            }
        }
    } else {
        echo "<script>window.alert(\"Worker not exist, please insert the correct name.\")</script>";
        exit();
    }

    if (!$found) {
        echo "<script>window.alert(\"Insert the username of a worker.\")</script>";
        exit();
    }

    $sql = "UPDATE activities SET worker=\"{$_POST['worker']}\" WHERE idActivity='{$idActivity}'";

    if ($conn->query($sql) === TRUE) {
        if ($page == 0) {
            $URL = "adminAll.php";
        } else if ($page == 1) {
            $URL = "adminNew.php";
        } else if ($page == 2) {
            $URL = "adminActivated.php";
        } else if ($page == 3) {
            $URL = "adminResolved.php";
        }

        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
    } else {
        echo "<script>window.alert(\"Error updating record: " . $conn->error . "\")</script>";
    }

    $conn->close();
}

function changeDate($idActivity, $date, $page)
{

    $conn = connectToDatabase();

    $sql = "UPDATE activities SET date_end=\"$date\" WHERE idActivity='{$idActivity}'";

    if ($conn->query($sql) === TRUE) {
        if ($_SESSION['role'] == 0) {
            if ($page == 0) {
                $URL = "workerAll.php";
            } else if ($page == 1) {
                $URL = "workerNew.php";
            } else if ($page == 2) {
                $URL = "workerActivated.php";
            } else if ($page == 3) {
                $URL = "workerResolved.php";
            }
        } else if ($_SESSION['role'] == 1) {
            if ($page == 0) {
                $URL = "adminAll.php";
            } else if ($page == 1) {
                $URL = "adminNew.php";
            } else if ($page == 2) {
                $URL = "adminActivated.php";
            } else if ($page == 3) {
                $URL = "adminResolved.php";
            }
        }

        echo "<script type='text/javascript'>document.location.href='{$URL}';</script>";
        echo '<META HTTP-EQUIV="refresh" content="0;URL=' . $URL . '">';
    } else {
        echo "<script>window.alert(\"Error updating record: " . $conn->error . "\")</script>";
    }

    $conn->close();
}
?>