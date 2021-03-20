<?php //Script for replacing website content with placeholder text and images.
require_once 'DB.php';
if ($conn->connect_error)
    die($conn->connect_error);

function searchUpdate() {
    $count = 0;
    $lorem = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore', 'magna', 'aliqua', 'Ut', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation', 'ullamco', 'laboris', 'nisi', 'ut', 'aliquip', 'ex', 'ea', 'commodo', 'consequat', 'Duis', 'aute', 'irure', 'dolor', 'in', 'reprehenderit', 'in', 'voluptate', 'velit', 'esse', 'cillum', 'dolore', 'eu', 'fugiat', 'nulla', 'pariatur', 'Excepteur', 'sint', 'occaecat', 'cupidatat', 'non', 'proident', 'sunt', 'in', 'culpa', 'qui', 'officia', 'deserunt', 'mollit', 'anim', 'id', 'est', 'laborum'];

    $query = "SELECT * FROM memes";
    $result = $conn->query($query);

    while ($result->fetch_assoc()) {
        $words = [];
        array_push($words, $lorem(random_int(0, count($lorem) - 1)));
        array_push($words, $lorem(random_int(0, count($lorem) - 1)));
        for ($y = 0; $y < 3; $y++) {       //add up to three more words
            if (random_int(0, 1) < 1) {
                array_push($words, $lorem(random_int(0, count($lorem) - 1)));
            }
        }
        $name = implode(' ', $words);
        $query2 = "UPDATE memes SET name='" . $name . "', file='./PLACEHOLDER.jpg' WHERE id='" . $count++ . "'";
        $conn->query($query2);
    }

    $count = 0;

    foreach ($lorem as $word) {
        $query4 = "UPDATE tags SET word='" . $word . "' WHERE id='" . $count++ . "'";
        $conn->query($query4);
    }
}
?>
<script>
    var links = document.getElementsByTagName("a");
    var lorem = ['Lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing', 'elit', 'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore', 'et', 'dolore', 'magna', 'aliqua', 'Ut', 'enim', 'ad', 'minim', 'veniam', 'quis', 'nostrud', 'exercitation', 'ullamco', 'laboris', 'nisi', 'ut', 'aliquip', 'ex', 'ea', 'commodo', 'consequat', 'Duis', 'aute', 'irure', 'dolor', 'in', 'reprehenderit', 'in', 'voluptate', 'velit', 'esse', 'cillum', 'dolore', 'eu', 'fugiat', 'nulla', 'pariatur', 'Excepteur', 'sint', 'occaecat', 'cupidatat', 'non', 'proident', 'sunt', 'in', 'culpa', 'qui', 'officia', 'deserunt', 'mollit', 'anim', 'id', 'est', 'laborum'];

    for (var x = 0; x < links.length; x++) {
        links[x].href = "./PLACEHOLDER.jpg";
        words = [];
        words.push(lorem[Math.floor(Math.random() * lorem.length)]);    //add a minimum of two words
        words.push(lorem[Math.floor(Math.random() * lorem.length)]);
        for (var y = 0; y < 3; y++) {       //add up to three more words
            if (Math.random() < 0.5) {
                words.push(lorem[Math.floor(Math.random() * lorem.length)]);
            }
        }
        links[x].innerHTML = words.toString().replace(/,/g, ' ');
    }
</script>