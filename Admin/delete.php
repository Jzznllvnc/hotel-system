<?php
if ( isset($_GET["id"]) ) {
    $id = $_GET["id"];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $database = "idgenerate";

    $connection = new mysqli($servername, $username, $password, $database);

    $sql = "DELETE FROM examinees WHERE id=$id";
    $connection->query($sql);
}

header("location: /jazzphp/Admin/table.php");
exit;
?>