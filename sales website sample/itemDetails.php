<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My hiking gear</title>
</head>

<body>
    <?php
    // get the method from buyerList.php
    $id = $_GET["action"];
    //get the directory location
    $file = "./GearDirectory.txt";
    //add navigating buttons
    echo '<p><a href="./mainPage.php"  ><button>Main Menu</button></a> <a href="./buyerList.php"  ><button>List All Items</button></a></p>';

    //check if file exist or not empty
    if (!file_exists($file) || (filesize($file) == 0)) {
        echo "<p>There are no item listed. Please visit us later.</p>";
    } else {
        //extract the item from the file
        $itemArray = file($file);
        echo "<table width=\"40%\" style=\"background-color:lightgray\" border=\"1\" width=\"100%\">\n";
        //count how many item you have
        $count = count($itemArray);
        //explode each item by ~
        for ($i = 0; $i < $count; ++$i) {
            $currentItem = explode("~", $itemArray[$i]);
            //create new array with key value if current item is same as id we need
            if ($currentItem[3] == $id) {
                //create key and value and combine them in an array
                $KeyArray[] = $currentItem[3];
                $ValueArray[] = $currentItem[0] . "~" . $currentItem[1] . "~" . $currentItem[2] . "~" . $currentItem[4] . "~" .  $currentItem[5] . "~" .  $currentItem[6] . "~" .  $currentItem[7];
            }
        }
        $allArray = array_combine($KeyArray, $ValueArray);

        foreach ($allArray as $item) {
            $currentItem = explode("~", $item);
            $header= ["Name","Phone","Email","ID Number","Type","Condition","Item Name","Description"];
            //display the item we found in the array
            echo "<tr><th colspan=\"2\"> SELECTED ITEM</span></th><tr>";
            echo "<tr><td>".$header[0]."</td><td>" . htmlentities($currentItem[0]) . "</td></tr>";
            echo "<tr><td>" . $header[1] . "</td><td>" . htmlentities($currentItem[1]) . "</td></tr>";
            echo "<tr><td>" . $header[2] . "</td><td>" . htmlentities($currentItem[2]) . "</td></tr>";
            echo "<tr><td>" . $header[3] . "</td><td>" . htmlentities(key($allArray)) . "</td></tr>";
            echo "<tr><td>" . $header[4] . "</td><td>" . htmlentities($currentItem[3]) . "</td></tr>";
            echo "<tr><td>" . $header[5] . "</td><td>" . htmlentities($currentItem[4]) . "</td></tr>";
            echo "<tr><td>" . $header[6] . "</td><td>" . htmlentities($currentItem[5]) . "</td></tr>";
            echo "<tr><td>" . $header[7] . "</td><td height=\"100\">" . htmlentities($currentItem[6]) . "</td></tr>";
            echo "<tr><td style=\"text-align:center\" colspan=\"2\"><a href='buyerBid.php?action=" . htmlentities(key($allArray)) . "'>Bid</a>" . "</td></tr>";
        }
        echo "</table>";
    }
    ?>
</body>

</html>