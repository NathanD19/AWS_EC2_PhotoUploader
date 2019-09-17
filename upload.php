<html>
<?php

include "dbinfo.inc";

/* Connect to MySQL and select the database. */
$connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

if (!$connection) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    die("Connection failed: " . mysqli_connect_error());
}

$title = $description = $date = $keywords = "";

?>

<head>
    <title>Photo Uploader</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Nathan Davies">
    <meta name="student_id" content="101094460">
    <meta name="class" content="COS20019">
</head>

<body>
    <h2 style="display:inline;">Photo Uploader</h2>
    <a style="float:right;" href="getphotos.php ">Photo Album</a>

    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" enctype="multipart/form-data">
        <p>Photo Title: <input type="text" name="title"><br></p>
        <p>Select image to upload: <input type="file" name="image" id="image"></p>
        <p>Description: <input type="text" name="description"><br></p>
        <p>Date: <input type="text" name="date"><br></p>
        <p>Keywords (seperate by commas, e.g. keyword, keyword2, etc): <input type="text" name="keywords"><br></p>
        <input type="submit" value="Upload Image" name="Upload">
    </form>

</body>

<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

//echo "<img src='" . "http://d39xooky6665dv.cloudfront.net/images/pear.jpg" . "'>";
require "aws/aws-autoloader.php";

//require "aws/aws.phar";

use Aws\S3\S3Client;
use Aws\Common\Exception\S3Exception;

$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'ap-southeast-2'
]);

$title = $_POST["title"];
$description = $_POST["description"];
$date = $_POST["date"];
$keywords = $_POST["keywords"];

if (!$title == "" && !$description == "" && !$date == "" && !$keywords == "" && !$_FILES['image']['size'] == 0) {

    $file_name = $_FILES['image']['name'];
    $temp_file_location = $_FILES['image']['tmp_name'];

    try {
        $result = $s3->putObject([
            'Bucket' => BUCKET,
            'Key'    => 'images/' . $file_name,
            'SourceFile' => $temp_file_location
        ]);

        //echo "Image: <a href='" . $result['ObjectURL'] . "'>" . $result['ObjectURL'] . "</a>" . PHP_EOL;
        $url = "http://d39xooky6665dv.cloudfront.net/images/" . $file_name;
        // echo "<img src='" . $url . "'>";
        $sql = "INSERT INTO photos(photo_title, description, date_of_photo, keywords, s3_object_reference) values('$title', '$description', '$date', '$keywords', '$url');";

        $result = mysqli_query($connection, $sql);

        if ($result == 1) { 
            echo "<br>Upload Successful";
        }
    } catch (S3Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }
} else {
    echo "Please fill in details";
}

?>

<footer>
    <br>
    Nathan Davies - 101094460 <br>
    Cloud Computing Architecture - COS20019
</footer>

</html>