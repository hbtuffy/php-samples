<?php

class inserting_car
{
    private $conn = NULL;

    function __construct()
    {
        include('database_connection.php');
        $this->conn = $conn;
    }

    function insert_new_car($car_detail)
    {

        $model = trim($car_detail['model']);
        $plates = trim($car_detail['plates']);
        $type_car = trim($car_detail['type_car']);
        $status = trim($car_detail['status']);
        $cost = trim($car_detail['cost_per_day']);


        $connection = $this->conn;
        $table_available_cars = "available_car";
        $cars = array();
        $select_cars_columns = "SELECT * FROM $table_available_cars WHERE car_no NOT IN (SELECT car_no FROM list_off_cars)";
        $qRes = mysqli_query($connection, $select_cars_columns);

        $plate_exist = false;

        if (mysqli_num_rows($qRes) > 0) {
            while (($row = mysqli_fetch_assoc($qRes)) != FALSE) {
                $cars[] = $row;
                if ($row['plates'] == $plates) {
                    $plate_exist = true;
                }
            }
        }

        if ($plate_exist) {
            echo "<p>The plate# " . $plates . " exists in the system.</p>\n";
        } else {
            $sql_data = "INSERT INTO available_car (plates, model, type, status, cost_per_day)
                        VALUES ('$plates', '$model', '$type_car', '$status', '$cost')";
            if ($this->conn->query($sql_data)) {
                $car_no = $this->conn->insert_id;
                $_SESSION['car_no'] = $car_no;
                echo "<p>The car has been successfully added in the system.</p>\n";
            } else {
                echo "<p>System corruption please re-login and try again. Otherwise contact your administration .</p>\n";
            }
        }
    }
}
