<?php

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$database = "my_rent_buddy";


try {
    // Create connection
    $conn = new mysqli($servername, $username, $password);
    echo "successful connection<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}


try {
    $create_database = "CREATE DATABASE $database";
    $conn->query($create_database);
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}

try {
    $conn->select_db($database);
    echo "successful database selection<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}

// Create user table
try {
    $create_user = "CREATE TABLE user (
        user_id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_type INT(1) NOT NULL ,
        first_name VARCHAR(255) NOT NULL,
        last_name VARCHAR(255) NOT NULL,
        password VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone INT(255) NOT NULL
    )";
    $conn->query($create_user);
    echo "Table 'user' created successfully\n<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}

// Create available_car table
try {
    $create_available_car =
        "CREATE TABLE available_car (
        car_no INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        plates VARCHAR(255) NOT NULL,
        model VARCHAR(255) NOT NULL,
        type VARCHAR(255) NOT NULL,
        status VARCHAR(255) NOT NULL,
        cost_per_day DECIMAL(10,2) NOT NULL,
        capacity INT(1) NOT NULL DEFAULT 1
    )";
    $conn->query($create_available_car);
    echo "Table 'available_car' created successfully\n<br>";


    $insert_query = "INSERT INTO available_car (plates, model, type, status, cost_per_day) VALUES 
    ('ABC123', 'Toyota Camry', 'sedan', 'available', 120.58),
    ('DEF456', 'Ford Mustang', 'sports car', 'available', 180.32),
    ('GHI789', 'Volkswagen Golf', 'hatchback', 'available', 70.74),
    ('JKL012', 'BMW X5', 'SUV', 'available', 200.91),
    ('MNO345', 'Honda Civic', 'sedan', 'available', 50.29),
    ('PQR678', 'Mercedes-Benz C-Class', 'sedan', 'available', 150.76),
    ('STU901', 'Audi A3', 'sedan', 'available', 100.13),
    ('VWX234', 'Nissan Pathfinder', 'SUV', 'available', 220.45),
    ('YZA567', 'Tesla Model S', 'electric', 'available', 250.00),
    ('BCD890', 'Subaru Outback', 'wagon', 'available', 130.80),
    ('EFG123', 'Mazda CX-5', 'SUV', 'available', 190.23),
    ('HIJ456', 'Ford Ranger', 'pickup truck', 'available', 160.99),
    ('KLM789', 'Hyundai i30', 'hatchback', 'available', 80.61),
    ('NOP012', 'Kia Sportage', 'SUV', 'available', 140.48),
    ('QRS345', 'Holden Commodore', 'sedan', 'available', 110.16),
    ('TUV678', 'Jeep Wrangler', 'SUV', 'available', 210.85),
    ('WXY901', 'Volvo XC60', 'SUV', 'available', 230.67),
    ('ZAB234', 'Lexus RX', 'SUV', 'available', 240.22),
    ('CDE567', 'Peugeot 308', 'hatchback', 'available', 90.05),
    ('FGH890', 'Suzuki Swift', 'hatchback', 'available', 30.70)";

    $conn->query($insert_query);
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}

// Create rented_car table
try {
    $create_rented_car = "CREATE TABLE rented_car (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        car_no INT(11) UNSIGNED NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        date_approved DATE,
        total_cost INT(11) UNSIGNED NOT NULL,
        FOREIGN KEY (user_id) REFERENCES user(user_id),
        FOREIGN KEY (car_no) REFERENCES available_car(id)
    )";
    $conn->query($create_rented_car);
    echo "Table 'rented_car' created successfully\n<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}

try {
    $create_previously_rented = "CREATE TABLE previously_rented (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(11) UNSIGNED NOT NULL,
        car_no INT(11) UNSIGNED NOT NULL,
        start_date DATE NOT NULL,
        end_date DATE NOT NULL,
        total_cost INT(11) UNSIGNED NOT NULL,
        date_approved DATE,
        FOREIGN KEY (user_id) REFERENCES user(user_id),
        FOREIGN KEY (car_no) REFERENCES available_car(car_no)
    )";
    $conn->query($create_previously_rented);
    echo "Table 'previously_rented' created successfully\n<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}


try {
    $create_list_off_cars = "CREATE TABLE list_off_cars (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        car_no INT(11) UNSIGNED NOT NULL,
        FOREIGN KEY (car_no) REFERENCES available_car(car_no)
);";
    $conn->query($create_list_off_cars);
    echo "Table 'list_off_cars' created successfully\n<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}
// Close the connection
try {
    $conn->close();
    echo "Table 'rented_car' created successfully\n<br>";
} catch (mysqli_sql_exception $e) {
    die($e->getCode() . ": " . $e->getMessage());
}
