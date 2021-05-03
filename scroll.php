<?php

require('DB.php');
session_start();
if (!empty($_GET['src'])) {
    $query = "SELECT DISTINCT i.name, i.file, i.ID FROM images i
            INNER JOIN image_tag it ON i.ID = it.imageID
            INNER JOIN tags t ON it.tagID = t.ID
            WHERE (t.word LIKE '%" . $_GET['src'] . "%'
            XOR i.name LIKE '%" . $_GET['src'] . "%')
            AND i.ID NOT IN (" . $_GET['ids'] . ")
            LIMIT 8";
    $result = $cnct->query($query);
    if (!$result) {
        die("Database access failed.");
    } else {
        $json = include('cell.php');
        echo json_encode($json);
    }
} else {
    $query = "SELECT * FROM images WHERE ID < '" . $_GET['last'] . "' ORDER BY ID DESC LIMIT 8";
    $result = $cnct->query($query);
    if (!$result) {
        die("Database access failed.");
    } else {
        $json = include('cell.php');
        echo json_encode($json);
    }
}
?>