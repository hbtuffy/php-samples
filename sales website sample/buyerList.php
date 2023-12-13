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
    //get the directory location
    $file = "./GearDirectory.txt";
    //there is no file message if doesnt exist or empty
    if (!file_exists($file) || (filesize($file) == 0)) {
        echo "<p>There are no item listed. Please visit us later.</p>";
    }
    //display the table
    else {
        //extract each line in array
        $itemArray = file($file);
        echo "<table style=\"background-color:lightgray\" border=\"1\" width=\"100%\">\n";
        echo "<tr>";
        echo "<th>No</th>";
        echo "<th>ID Number</th>";
        echo "<th>Type</th>";
        echo "<th>Item Name</th>";
        echo "<th>Action</th>";
        $count = count($itemArray);
        //create array for every item in the sentence
        for ($i = 0; $i < $count; ++$i) {
            $currentItem = explode("~", $itemArray[$i]);
            //create key and values for display
            $KeyArray[] = $currentItem[3];
            $ValueArray[] = $currentItem[4] . "~" . $currentItem[6];
        }
        //combine the keys and values
        $allArray = array_combine($KeyArray, $ValueArray);

        $index = 1;
        //display the item list for each listed item
        foreach ($allArray as $item) {
            $currentItem = explode("~", $item);
            echo "<tr>\n";
            echo "<td style=\"text-align:center\">" . $index . "</span></td>\n";
            echo "<td style=\"text-align:center\">" . htmlentities(key($allArray)) . "</td>";
            echo "<td style=\"text-align:center\">" . htmlentities($currentItem[0]) . "</td>";
            echo "<td >" . htmlentities($currentItem[1]) . "</td>";
            echo "<td style=\"text-align:center\"><a href='itemDetails.php?action=" . htmlentities(key($allArray)) . "'>Details</a>" . "</td>\n";
            echo "</tr>\n";
            ++$index;
            next($allArray);
        }
    }
    //button for main menu
    echo '<p><a href="./mainPage.php"  ><button>Main Menu</button></a></p>';

    //check if the buyer entered an idnumber for searching
    if (isset($_POST["action"])) {
        //pattern check for the idnumber
        $idNumberPattern = "/^[a-zA-Z]{3}-\d{3}:[a-zA-Z]{2}$/";
        $id = trim(($_POST["action"]));

        //direct the buyer to the detailed page if exist otherwise keep showing the list with error message
        if (!preg_match($idNumberPattern, $id)) {

            echo "Invalid ID number format. Your ID number should include Letter (L) and Number (N). Exp: LLL-NNN:LL <br/>";
        } else {
            header("Location: itemDetails.php?action=" . $id);
        }
    }
    ?>

    <!--create a form incase the buyer wanna check the item by entering the idnumber-->
    <form method="post" action="buyerList.php">
        <p>Search ID Number: <input type="text" name="action"> </input></p>
    </form>
</body>

</html>