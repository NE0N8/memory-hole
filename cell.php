<?php
require('DB.php');
while ($row = $result->fetch_assoc()) : ?>
    <div class="cell" id="<?= $row['ID'] ?>"">
        <div class="thumb1">
            <a href="./images/<?= $row['file'] ?>" target="_blank">
                <div class="thumb2" style="background-image: url('./thumbs/th_<?= $row['file'] ?>');">
                </div>
            </a>
        </div>
        <div class="label1">
            <a href="./images/<?= $row['file'] ?>" target="_blank">
                <span class="label2"><?=$row['name']?></span>
            </a>
        </div><br>
        <?php
        if (isset($_SESSION['user']) && $_SESSION['user'] == "admin") : 
            $page = substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1); 
        //add delete button if logged in as admin
        ?>
            <div class="delet">
                <form action="<?= $page ?>" method="post">
                    <input type="hidden" name="delete" value="yes">
                    <input type="hidden" name="id" value="<?= $row['ID'] ?>">
                    <input type="submit" value="DELETE">
                </form>
            </div>
        <?php
        endif;
        $query2 = "SELECT t.word FROM tags t 
            INNER JOIN image_tag it ON(it.tagID = t.id)
            WHERE it.imageID = " . $row['ID'] . " LIMIT 12";
        $result2 = $cnct->query($query2);
        ?>
        <div class="tag1">
            <?php
            while ($row2 = $result2->fetch_assoc()) : ?>
                <nav class="tag2">
                    <a href="search.php?search=<?= $row2['word'] ?>"><?= $row2['word'] ?></a>
                </nav>
            <?php endwhile; ?>
        </div>
    </div>
<?php endwhile; ?>