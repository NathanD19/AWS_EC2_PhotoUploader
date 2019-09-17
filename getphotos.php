<?php

include "dbinfo.inc";

/* Connect to MySQL and select the database. */
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$connection) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die("Connection failed: " . mysqli_connect_error());
}

?>

<html>

<head>

    <title>Search Photo Table</title>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Nathan Davies">
    <meta name="student_id" content="101094460">
    <meta name="class" content="COS20019">
    <link rel="stylesheet" type="text/css" href="style.css">

</head>

<body>

    <h2 style="display:inline;">Photo Search</h2>
    <a style="float:right;" href="upload.php">Upload Photo</a>
    <div style="clear:float;"></div>
    <div style=" margin: auto; width: 90%;">
        <form style="width: 20%; display:inline-block;" action="" method="post">
            <h3>Search Title</h3>
            Search: <input type="text" name="input" value=""><br><br>

            <input type="submit" name="search" value="Search DB">

        </form>

        <form style="width: 20%; display:inline-block;" action="" method="post">
            <h3>Search Keywords</h3>
            Search: <input type="text" name="input_key" value=""><br><br>

            <input type="submit" name="search" value="Search DB">

        </form>

        <form style="width: 20%; display:inline-block;" action="" method="post">
            <h3>Search Date</h3>
            Dates: <input style="width:35%" type="text" name="date_one" value=""> &#60; &#62; <input style="width:35%" type="text" name="date_two" value=""><br><br>

            <input type="submit" name="search" value="Search DB">

        </form>
        <form style="text-align:center; display:inline-block;" action="" method="post">
            <h3>Retrieve All</h3>
            <span style="visibility: hidden;">hidden<input style="width: 1px;" type="text" name="input_key" value=""></span>
            <br><br>
            <input type="submit" name="search_all" value="Search DB">
            

        </form>
    </div>
    <br><br>

</body>


<?php
// echo $_SERVER['SERVER_ADDR'] . "<br>";

/* Retreive values from form */
$input = $_REQUEST["input"];

if (isset( $_POST['search_all'] )){
    $sql = "SELECT * FROM photos";
    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "<br>"; 
        echo "Rows: " . mysqli_num_rows($result) . "<br><br>";

        while ($row = mysqli_fetch_assoc($result)) {
            echo $row["photo_title"] . " | " . $row["description"] . " | " . $row["date_of_photo"] . " | "  . $row["keywords"] . " | " . "<br> Image: " . $row["s3_object_reference"] . "<br> <img src = '" . $row["s3_object_reference"] . "' width='15%'><hr>";
        }
    } else {
        echo "0 results";
    }
}

if (!$input == "") {

    $sql = "SELECT * FROM photos WHERE photo_title LIKE '%$input%'; # title only OR (description LIKE '%$input%') OR (date_of_photo LIKE '%$input%') OR (keywords LIKE '%$input%')";
    // NOT USED: OR (s3_object_reference LIKE '%$input%')
    // $sql = "SELECT * FROM photos";

    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "Rows: " . mysqli_num_rows($result) . "<br><br>";

        while ($row = mysqli_fetch_assoc($result)) {
            // photo_title, description, date_of_photo, keywords, s3_object_reference
            echo $row["photo_title"] . " | " . $row["description"] . " | " . $row["date_of_photo"] . " | "  . $row["keywords"] . " | " . "<br> Image: " . $row["s3_object_reference"] . "<br> <img src = '" . $row["s3_object_reference"] . "' width='15%'><hr>";
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Enter data to search photo database<br>";
}

$input_key = $_REQUEST["input_key"];

if (!$input_key == "") {

    $sql = "SELECT * FROM photos WHERE keywords LIKE '%$input_key%'";
    // NOT USED: OR (s3_object_reference LIKE '%$input%')
    // $sql = "SELECT * FROM photos";

    $result = mysqli_query($connection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        echo "Rows: " . mysqli_num_rows($result) . "<br><br>";

        while ($row = mysqli_fetch_assoc($result)) {
            // photo_title, description, date_of_photo, keywords, s3_object_reference
            echo $row["photo_title"] . " | " . $row["description"] . " | " . $row["date_of_photo"] . " | "  . $row["keywords"] . " | " . "<br> Image: " . $row["s3_object_reference"] . "<br> <img src = '" . $row["s3_object_reference"] . "' width='15%'><hr>";
        }
    } else {
        echo "0 results";
    }
} else {
    echo "<br>";
}



$date_one = $_REQUEST["date_one"];
$date_two = $_REQUEST["date_two"];

if (!$date_one == "" or !$date_two = "") {

    $sql_two = "SELECT * FROM photos WHERE date_of_photo BETWEEN '$date_one' AND '$date_two'";
    // echo $sql_two;

    $result_two = mysqli_query($connection, $sql_two);

    while ($row_two = mysqli_fetch_assoc($result_two)) {
        echo $row_two["photo_title"] . " | " . $row_two["description"] . " | " . $row_two["date_of_photo"] . " | "  . $row_two["keywords"] . " | " . "<br> Image: " . $row_two["s3_object_reference"] . "<br> <img src = '" . $row_two["s3_object_reference"] . "' width='15%'><hr>";
    }
}

?>

<footer>
    <br>
    Nathan Davies - 101094460 <br>
    Cloud Computing Architecture - COS20019
</footer>

</html>