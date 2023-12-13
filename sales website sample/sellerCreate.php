<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My hiking gear</title>
</head>

<body>
    <?php echo '<p><a href="./mainPage.php"  ><button>Main Menu</button></a> <a href="./buyerList.php"  ><button>List items</button></a></p>';
    ?>
    <?php
    //get the file location
    $file = "GearDirectory.txt";
    //get inputs from the form
    if (isset($_POST["createItem"])) {

        $name = stripslashes($_POST['name']);
        $phone = stripslashes($_POST['phone']);
        $email = stripslashes($_POST['email']);
        $idnumber = stripslashes($_POST['idnumber']);
        $type = stripslashes($_POST['type']);
        $condition = stripslashes($_POST['condition']);
        $itemname = stripslashes($_POST['itemname']);
        $description = stripslashes($_POST['description']);


        $name = str_replace("~", "-", $name);
        $phone = str_replace("~", "-", $phone);
        $email = str_replace("~", "-", $email);
        $idnumber = str_replace("~", "-", $idnumber);
        $type = str_replace("~", "-", $type);
        $condition = str_replace("~", "-", $condition);
        $itemname = str_replace("~", "-", $itemname);
        $description = str_replace("~", "-", $description);

        //id number should be a unique number in database. check if the id number is already exist
        if (file_exists($file) && filesize($file) > 0) {
            $itemArray = file($file);
            $count = count($itemArray);
            for ($i = 0; $i < $count; ++$i) {
                $CurrMsg = explode("~", $itemArray[$i]);
                $ExistingListing[] = $CurrMsg[3];
            }
            if (in_array($idnumber, $ExistingListing)) {
                echo "<p>The ID number you entered already exists! Your item cannot be saved. Please enter a different ID Number.</p>\n";
                //if idnumber is empty, pattern validation gives error and do not save
                $idnumber = "";
            }
        }

        //construct the way of recoding in the file
        $itemRecord = "$name~$phone~$email~$idnumber~$type~$condition~$itemname~$description\n";
        // opens file for writing only and place the pointer at the end
        $MessageFile = fopen($file, "ab");
        if ($MessageFile === FALSE)
            echo "There was an error saving your item!\n";
        else {
            //create patterns for patternCheck
            $phonePattern = "/^04\d{8}$/";
            $idNumberPattern = "/^[a-zA-Z]{3}-\d{3}:[a-zA-Z]{2}$/";
            $emailPattern = "/@(gmail|hotmail|yahoo|outlook|icloud)\.com$/i";
            $patternCheck = TRUE;
            //phonePattern check
            if (!preg_match($phonePattern, $phone) || empty($phone)) {
                $patternCheck = FALSE;
                echo "Invalid Phone number format. Your phone number has to be an Australian. Exp: 0422 222 222 <br/>";
            }
            //idNumberPattern check

            if (!preg_match($idNumberPattern, $idnumber) || empty($idnumber)) {
                $patternCheck = FALSE;
                echo "Invalid ID number format. Your ID number should include Letter (L) and Number (N). Exp: LLL-NNN:LL <br/>";
            }
            //emailPattern check

            if (!preg_match($emailPattern, $email) || empty($email)) {
                $patternCheck = FALSE;
                echo "Invalid email format. Your email should be from a valid provider such as gmail, hotmail, yahoo, outlook, icloud. Exp: yourname@gmail.com<br/>";
            }
            //if pattern check is true, save it in to the file.
            if ($patternCheck === TRUE) {
                fwrite($MessageFile, $itemRecord);
                fclose($MessageFile);
                echo "Your item has been saved.\n";
            }
            //available pattern check again if set false earlier
            $patternCheck = TRUE;
        }
    }
    //}
    ?>

    <!-- create an email form to get user's input-->
    <form method="post" action="sellerCreate.php">
        <table>
            <tr>
                <td>
                    <h4>Personal Information</h4>
                </td>
            </tr>
            <tr>
                <td> Name:</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td> Phone:</td>
                <td><input type="text" name="phone"></td>
            </tr>
            <tr>
                <td> Email:</td>
                <td><input type="text" name="email"></td>
            </tr>
            <tr>
                <td>
                    <h4>Basic Information</h4>
                </td>
            </tr>
            <tr>
                <td> ID Number:</td>
                <td><input type="text" name="idnumber"></td>
            </tr>
            <tr>
                <td> Type:</td>
                <td>
                    <select name="type">
                        <option value="backpacks">Backpacks</option>
                        <option value="rainjackets">Rain Jackets</option>
                        <option value="sleepingbags">Sleeping Bags</option>
                        <option value="hikingshoes">Hiking Shoes</option>
                        <option value="hats">Hats</option>
                        <option value="hikingclothing">Hiking Clothing</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Item Name:</td>
                <td><input type="text" name="itemname"></td>
            </tr>
            <tr>
                <td>
                    <h4>Detailed Information</h4>
                </td>
            </tr>
            <tr>
                <td> Condition:</td>
                <td>
                    <select name="condition">
                        <option value="new">New</option>
                        <option value="used">Used</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td> Description:</td>
                <td><textarea name="description" cols="21" rows="10"></textarea></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="createItem" Value="Save">

                </td>
            </tr>
        </table>
    </form>

</body>

</html>