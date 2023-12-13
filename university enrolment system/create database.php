<?php

// Establish database connection
$db_host = "localhost";
$db_user = "root";
$db_password = "";
$db_name = "music_conservatorium";

// Check if connection is successful
try {
    $db_conn = mysqli_connect($db_host, $db_user, $db_password);
    echo "Connection is successful.";
    echo "You are connected $db_name.<br/>";
} catch (mysqli_sql_exception $e) {
    die("Connection failed: " . mysqli_connect_errno() . "-" . mysqli_connect_error() . "<br/>");
}

//Define SQL queries to create database
$sql_create_database = "CREATE DATABASE $db_name";

try {
    mysqli_query($db_conn, $sql_create_database);
    echo "Database created successfully!<br/>";
} catch (mysqli_sql_exception $e) {
    die("CREATE DATABASE failed: " . mysqli_errno($db_conn) . "-" . mysqli_error($db_conn) . "<br/>");
}

// Define SQL queries to create tables
$sql_students = "CREATE TABLE Students (
    student_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    password_md5 VARCHAR(32), 
    email VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL
)";

$sql_faculties = "CREATE TABLE Faculties (
    faculty_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL
)";

$sql_teachers = "CREATE TABLE Teachers (
    teacher_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    phone_number VARCHAR(20) NOT NULL,
    faculty_id INT(6) UNSIGNED,
    FOREIGN KEY (faculty_id) REFERENCES Faculties(faculty_id)
)";

$sql_classes = "CREATE TABLE Classes (
    class_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    capacity INT(6) NOT NULL,
    teacher_id INT(6) UNSIGNED,
    faculty_id INT(6) UNSIGNED,
    FOREIGN KEY (teacher_id) REFERENCES Teachers(teacher_id),
    FOREIGN KEY (faculty_id) REFERENCES Faculties(faculty_id)
)";

$sql_enrolments =
    "CREATE TABLE Enrolments (
    enrolment_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    class_id INT(6) UNSIGNED,
    student_id INT(6) UNSIGNED,
    date_selected DATE, 
    date_approved DATE,
    FOREIGN KEY (class_id) REFERENCES Classes(class_id),
    FOREIGN KEY (student_id) REFERENCES Students(student_id)
)";

//Select the database
$db_conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);

try {
    // Execute SQL queries to create tables
    mysqli_query($db_conn, $sql_students);
    mysqli_query($db_conn, $sql_faculties);
    mysqli_query($db_conn, $sql_teachers);
    mysqli_query($db_conn, $sql_classes);
    mysqli_query($db_conn, $sql_enrolments);
    echo "Tables created successfully!<br/>";
} catch (mysqli_sql_exception $e) {
    die("Error creating tables: " . mysqli_connect_errno() . "-" . mysqli_connect_error() . "<br/>");
}


$insert_faculties = "INSERT INTO Faculties (name) VALUES
        ('Instrumental'),
        ('Ensembles')";

$insert_teachers = "INSERT INTO Teachers (first_name, last_name, email, phone_number, faculty_id) VALUES
        ('John', 'Doe', 'johndoe@example.com', '0400123456', 1),
        ('Jane', 'Smith', 'janesmith@example.com', '0400987654', 2),
        ('Bob', 'Johnson', 'bobjohnson@example.com', '0412123456', 1),
        ('Sara', 'Lee', 'saralee@example.com', '0412987654', 2),
        ('Tom', 'Williams', 'tomwilliams@example.com', '0400765432', 1),
        ('Anna', 'Nguyen', 'annanguyen@example.com', '0400112233', 2),
        ('Mark', 'Taylor', 'marktaylor@example.com', '0400567890', 1)";


$insert_classes =
    "INSERT INTO Classes (name, capacity, teacher_id, faculty_id) VALUES
        ('Piano 101', 1, 1, 1),
        ('Guitar 101', 1, 3, 1),
        ('Violin 101', 1, 5, 1),
        ('Ensemble A', 5, 2, 2),
        ('Ensemble B', 5, 4, 2),
        ('Ensemble C', 5, 6, 2)";

try {
    // Execute SQL queries to insert data
    mysqli_query($db_conn, $insert_faculties);
    mysqli_query($db_conn, $insert_teachers);
    mysqli_query($db_conn, $insert_classes);
    echo "Data inserted successfully!<br/>";
} catch (mysqli_sql_exception $e) {
    die("Error inserting data: " . mysqli_connect_errno() . "-" . mysqli_connect_error() . "<br/>");
}

// Close database connection
mysqli_close($db_conn);
echo "Disconnection is successful.<br/>";
echo "You are disconnected from $db_name.<br/>";
