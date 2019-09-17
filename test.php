<?php
include "dbinfo.inc";
?>
<html>

<body>
    <h1>Database Connection Test</h1>
    <?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    /* Connect to MySQL and select the database. */
    $connection = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if (mysqli_connect_errno()) {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
        die("Connection failed: " . mysqli_connect_error());
    }

    echo "Connected successfully";

    echo "<br><br>";

    echo "Host information: " . mysqli_get_host_info($connection) . PHP_EOL;


    $sql = "SELECT * FROM photos";
    $result = mysqli_query($connection, $sql);


    echo "<br>";

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            // photo_title, description, date_of_photo, keywords, s3_object_reference
            echo "<br>photo_title: " . $row["photo_title"] . " <br> description: " . $row["description"] . "<br> date_of_photo: " . $row["date_of_photo"] . "<br> keywords: "  . $row["keywords"] . "<br> s3_object_reference: " . $row["s3_object_reference"] . "<br>";
        }
    } else {
        echo "0 results";
    }


    echo "<br><br>Test AWS PHP<br><br>";

    require "aws/aws-autoloader.php";
    //require "aws/aws.phar";

    use Aws\S3\S3Client;
    use Aws\Common\Exception\S3Exception;

    $bucket = 'assignment1b-101094460';
    //$keyname = 'test';

    $s3 = new S3Client([
        'version' => 'latest',
        'region'  => 'ap-southeast-2'
    ]);


    try {
        $results = $s3->getPaginator('ListObjects', [
            'Bucket' =>  $bucket
        ]);
    } catch (S3Exception $e) {
        echo $e->getMessage() . PHP_EOL;
    }

    foreach ($results as $result) {
        if ($result['Contents'] != null) {
            foreach ($result['Contents'] as $object) {
                echo $object['Key'];
                echo "\n";
            }
        }
    }

    echo "<br><br>Completed PHP File"


    ?>