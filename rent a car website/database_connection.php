<?php
$ErrorMsgs = array();
try {
    $conn = new mysqli("localhost", "root", "", "my_rent_buddy");
}
catch (mysqli_sql_exception $e) {
	$ErrorMsgs[] = "The database server is not available. Error: " . $e->getCode() . "." . $e->getMessage();
}
?>

