<?php
require ("includes/functions.php");

//Settings
$servername = "localhost";
$username = "root";
$password = "";
$db = "lesson09";

// Create connection
$link = new mysqli($servername, $username, $password);

// Check connection
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
} else {
    echo "<br> Connected to MySQL server.";
}

//Get SQL
$sql = array();

$db_selected = mysqli_select_db($link, $db);
//Create db if can't connect to it (say because it doesn't exist yet).
if (!$db_selected) {
    echo "Couldn't find database '$db', will attempt to create it.";

    $c = fopen('create.txt', 'r');
    $sql_c = fread($c,filesize('create.txt'));
    fclose($c);

    if (mysqli_multi_query($link, $sql_c)) {
        do { mysqli_use_result ( $link ); } while( mysqli_more_results ( $link ) && mysqli_next_result($link) );
        echo " <br> Database created successfully";
        if($db_selected = mysqli_select_db($link, $db)){ //i.e. now it should work
            echo "<br> And connected to database.";
        } else {
            die(" <br> Error connecting to database: " . mysqli_error($link));
        }
    } else {
        die(" <br> Error creating database: " . mysqli_error($link));
    }
} else {
    echo "<br> Connected to database '$db'.";
}

//check if any books exist
$sql = "SELECT COUNT(*) FROM `books`;";
$result = mysqli_query($link, $sql);
$count = mysqli_fetch_array($result);
if (!$count) {
    printf("Error: %s\n", mysqli_error($link));
    exit();
}
if($count[0] == 0){
    echo "<br> Couldn't find any entries, will attempt to insert default.";

    $i = fopen('insert.txt', 'r');
    $sql_i = fread($i,filesize('insert.txt'));
    fclose($i);

    if (mysqli_query($link, $sql_i)) {
        echo " <br> Entries successfully added. Specifically:";
    } else {
        die(" <br> Error inserting entries: " . mysqli_error($link));
    }
} else {
    echo "<br> Some entries found. Specifically:";
}

//Display data
$sql = "SELECT */*id AS ID, title AS Title, author AS Author, published_year AS Year*/ FROM `books`;";
$result = mysqli_fetch_all(mysqli_query($link, $sql), MYSQLI_ASSOC);
query_results_as_table($result, '<code>`books`</code> table');

// Close connection
mysqli_close($link);

echo "<style>
h3{
    border-bottom: 1px solid beige;
}
th, td {
    text-align: center;
    padding: 0.3em;
}
</style>";
