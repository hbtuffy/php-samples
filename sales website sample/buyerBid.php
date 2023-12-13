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
    //get the file location to save 
    $file = "BuyersEOI.txt";
    //if form set after first load
    if (isset($_POST["bidItem"])) {

        //get the items and save
        $name = stripslashes($_POST['name']);
        $phone = stripslashes($_POST['phone']);
        $idnumber = stripslashes($_POST['idnumber']);
        $price = stripslashes($_POST['price']);

        $name = str_replace("~", "-", $name);
        $phone = str_replace("~", "-", $phone);
        $idnumber = str_replace("~", "-", $idnumber);
        $price = str_replace("~", "-", $price);

        $MessageRecord = "$name~$phone~$idnumber~$price\n";
        // opens file for writing only and place the pointer at the end
        $MessageFile = fopen($file, "ab");
        if ($MessageFile === FALSE)
            echo "There was an error saving your message!\n";
        else {
            //pattern check for entry
            $phonePattern = "/^04\d{8}$/";
            $idNumberPattern = "/^[a-zA-Z]{3}-\d{3}:[a-zA-Z]{2}$/";
            //check for preg_match
            $formValid = TRUE;
            //check if page is loaded again
            $reloadPage = TRUE;

            if (!preg_match($phonePattern, $phone) || empty($phone)) {
                $formValid = FALSE;
                echo "Invalid Phone number format. Your phone number has to be an Australian. Exp: 0422 222 222 <br/>";
                $reloadPage = FALSE;
            }
            if (!preg_match($idNumberPattern, $idnumber) || empty($idnumber)) {
                $formValid = FALSE;
                echo "Invalid ID number format. Your ID number should include Letter (L) and Number (N). Exp: LLL-NNN:LL <br/>";
                $reloadPage = FALSE;
            }
            //if inputs are valid, write into document
            if ($formValid === TRUE) {
                fwrite($MessageFile, $MessageRecord);
                fclose($MessageFile);
                echo "Your bid has been saved.\n";
            }
            //set the formvalidation and reloadpage again
            $formValid = TRUE;
            $reloadPage = TRUE;
        }
    }

    ?>
    <?php
    // get the idnumber from itemdetails.php
    if (isset($_GET["action"])) {
        //reloadPage$reloadPage is false if webpage is loaded first time
        $reloadPage = FALSE;
        $id = $_GET["action"];
    } else {
        $id = "";
    }
    ?>
    <!-- display buttons to navitage-->
    <?php echo '<p><a href="./mainPage.php"  ><button>Main Menu</button></a> <a href="./buyerList.php"  ><button>List items</button></a></p>' ?>
    <!-- display the form-->
    <form method="post" action="buyerBid.php">

        <table>
            <tr>
                <td> Name:</td>
                <td><input type="text" name="name"></td>
            </tr>
            <tr>
                <td> Phone:</td>
                <td><input type="text" name="phone"></td>
            </tr>
            <tr>
                <td> ID Number:</td>
                <!-- when first load the page get value as $id otherwise $idnumber-->
                <?php
                if ($reloadPage) {
                    echo "<td><input type=\"text\" name=\"idnumber\" value=\"$idnumber\"</td>";
                } else {
                    echo "<td><input type=\"text\" name=\"idnumber\" value=\"$id\"</td>";
                }
                ?>
            </tr>
            <tr>
                <td> Price:</td>
                <td><input type="number" name="price">$</td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <input type="submit" name="bidItem" Value="Save">
                </td>
            </tr>
        </table>
    </form>

</body>

</html>