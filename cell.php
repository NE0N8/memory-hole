<?php

require('DB.php');

while ($row = $result->fetch_assoc()) {
    echo '<div class="cell" id="' . $row['ID'] . '">
            <div class="thumb1">
                <a href="./images/' . $row['file'] . '" target="_blank">
                    <div class="thumb2" 
                    style="background-image: url(\'./thumbs/th_' . $row['file'] . '\');">
                    </div>
                </a>
            </div>
            <div class="label1">
                <a href="./images/' . $row['file'] . '" target="_blank"> 
                    <span class="label2">' . $row['name'] . '
                    </span>
                </a>
            </div><br>';
    $query2 = "SELECT t.word FROM tags t 
            INNER JOIN image_tag it ON(it.tagID = t.id)
            WHERE it.imageID = " . $row['ID'] . " LIMIT 12";
    $result2 = $conn->query($query2);
    echo '<div class="tag1">';
    while ($row2 = $result2->fetch_assoc()) {
        echo '<nav class="tag2">
                <a href="search.php?search=' . $row2['word'] . '">' . $row2['word'] . '</a>
            </nav>';
    }
    echo '</div></div>';
}
?>