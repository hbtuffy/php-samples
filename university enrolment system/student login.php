<?php
session_start();
$_SESSION = array();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    td {
        width: 150px;
    }

    th {
        text-align: left;
    }
</style>

<body>
    <table>
        <tr>
            <th colspan="2">
                <h1 style=" text-align: center;">Music Conservatorium</h1>
                <h2 style="text-align: center;">Register / Log In</h2>
            </th>
        </tr>
    </table>
    <form method="post" action="register student.php">

        <table>
            <tr>
                <th colspan="2" style="text-align: left;">
                    <h3>New Student Registration</h3>
                </th>
            </tr>
            <tr>
                <td>First Name:</td>
                <td>
                    <input type="text" name="first_name" />
                </td>
            </tr>
            <tr>
                <td>Last Name:</td>
                <td>
                    <input type="text" name="last_name" />
                </td>
            </tr>
            <tr>
                <td>E-mail:</td>
                <td>
                    <input type="text" name="email" />
                </td>
            </tr>
            <tr>
                <td>Enter Password:</td>
                <td>
                    <input type="password" name="password_1" />
                </td>
            </tr>
            <tr>
                <td>Confirm Password:</td>
                <td>
                    <input type="password" name="password_2" />
                </td>
            </tr>
            <tr>
                <td>Phone:</td>
                <td>
                    <input type="tel" name="phone" />
                </td>
            </tr>
            <tr>

                <td colspan="2" style="text-align: right;">
                    <input type="reset" name="reset" value="Clear" />
                    <input type="submit" name="register" value="Register" />
                </td>
            </tr>
        </table>
    </form>

    <form method="post" action="verify login.php">

        <table>
            <tr>
                <th colspan="2">
                    <h3>Current Student Login</h3>
                </th>
            </tr>
            <tr>
                <td>E-mail</td>
                <td><input type="text" name="email"></td>
            </tr>
            <tr>
                <td>Password</td>
                <td><input type="password" name="password"></td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: right;">
                    <input type="reset" name="reset" value="Clear">
                    <input type="submit" name="submit" value="Login">
                </td>
            </tr>
        </table>
    </form>
</body>

</html>