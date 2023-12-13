<!DOCTYPE html>
<html>

<head>
    <title>Car Rental Company</title>
</head>

<body>
    <?php
    // Including the header.php file
    include("header.php")
    ?>
    <?php
    if (isset($_GET['content'])) {
        switch ($_GET['content']) {
            case 'About Me':
                // Including the inc_about.html file for the 'About Me' 
                include('inc_about.html');
                break;
            case 'Contact Me':
                // Including the inc_contact.html file for the 'Contact Me' 
                include('inc_contact.html');
                break;
            case 'Register':
                // Including the register.html file for the 'Register' content
                include('register.html');
                break;
            case 'Login':
                // Including the login.html file for the 'Login' content
                include('login.html');
                break;

            default:
                // Including the inc_home.html file for the 'Home' content by default
                include('inc_home.html');
                break;
        }
    } else
        // In case there is a mistake, including the inc_home.html file if 'content' parameter is not set
        include('inc_home.html');
    ?>
    <?php
    // Including the footer.php file
    include("footer.php");
    ?>
</body>

</html>