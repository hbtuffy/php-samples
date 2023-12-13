<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>


    <?php

    // Establish database connection
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "music_conservatorium";

    // Check if connection is successful
    try {
        $db_conn = mysqli_connect($db_host, $db_user, $db_password, $db_name);
    } catch (mysqli_sql_exception $e) {
        die("Connection failed: " . mysqli_connect_errno() . "-" . mysqli_connect_error() . "<br/>");
    }
    echo "<b><p>Return to the <a href='available classes.php?" . SID . "'>Available classes</a> page.</p></b>\n";
    // Function to get ID and title of classes offered by a specific faculty
    function get_classes_by_faculty($db_conn, $faculty_id)
    {
        $sql = "SELECT class_id, name FROM Classes WHERE faculty_id = (SELECT faculty_id FROM Faculties WHERE faculty_id = '$faculty_id')";
        $result = mysqli_query($db_conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th>ID</th>
                <th> Title</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row['class_id'] . "</td>
                <td>" . $row['name'] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No class found.<br/>";
        }
    }
    ?>

    <form method="post" action="questions.php">
        to display IDs and titles of classes offered by a specific faculty<br>
        Please enter Faculty ID (1 - 2): <br>
        <input type="text" name="faculty_id">
        <input type="submit" name="submit">
    </form>

    <?php
    if (isset($_POST["submit"]) && !empty($_POST["faculty_id"])) {
        $faculty_id = $_POST["faculty_id"];
        get_classes_by_faculty($db_conn, $faculty_id);
    }
    echo "<hr/>"
    //===================================================================
    ?>



    <?php
    // Function to get names of students enrolled in a specific class
    function get_students_by_class($db_conn, $class_id)
    {
        $sql = "SELECT first_name, last_name, student_id FROM Students WHERE student_id IN (SELECT student_id FROM enrolments WHERE class_id = $class_id)";
        $result = mysqli_query($db_conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th>Student ID</th>
                <th> Full Name</th>
                </tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row['student_id'] . "</td>
                <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No student found.<br/>";
        }
    }
    ?>

    <form method="post" action="questions.php">
        To display names of the students enrolled in a specific class<br>
        Please enter Class ID (1 - 6): <br>
        <input type="text" name="class_id">
        <input type="submit" name="submit">
    </form>

    <?php
    if (isset($_POST["submit"]) && !empty($_POST["class_id"])) {
        $class_id = $_POST["class_id"];
        get_students_by_class($db_conn, $class_id);
    }
    echo "<hr/>";

    //===================================================================
    ?>



    <?php

    // Function to get names of music staff (teachers) in a specific faculty
    function get_teachers_by_faculty($db_conn, $faculty_id)
    {
        $sql = "SELECT first_name, last_name, teacher_id FROM teachers WHERE faculty_id = (SELECT faculty_id FROM faculties WHERE faculty_id = '$faculty_id')";
        $result = mysqli_query($db_conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th>Teacher ID</th>
                <th> Full Name</th>
                </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row['teacher_id'] . "</td>
                <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No teacher found.<br/>";
        }
    }

    ?>

    <form method="post" action="questions.php">
        To display names of the music teachers in a specific faculty <br>
        Please enter Faculty ID (1 - 2): <br>
        <input type="text" name="faculty_id_teacher">
        <input type="submit" name="submit">
    </form>

    <?php
    if (isset($_POST["submit"]) && !empty($_POST["faculty_id_teacher"])) {
        $faculty_id_teacher = $_POST["faculty_id_teacher"];
        get_teachers_by_faculty($db_conn, $faculty_id_teacher);
    }
    echo "<hr/>"
    //===================================================================
    ?>



    <?php

    // Function to get number of students in each class
    function get_num_students_by_class($db_conn)
    {
        $sql = "SELECT classes.class_id, classes.name, COUNT(enrolments.student_id) AS num_students FROM classes LEFT JOIN enrolments ON classes.class_id = enrolments.class_id GROUP BY classes.class_id";
        $result = mysqli_query($db_conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th>Class ID</th>
                <th> Title </th>
                <th> #Student</th>
                </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row['class_id'] . "</td>
                <td>" . $row['name'] . "</td>
                <td>" . $row['num_students'] . "</td>
                
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No classes found.<br/>";
        }
    }
    ?>
    <form method="post" action="questions.php">
        To display number of students in each class<br>
        Click on the button: <br>
        <input type="submit" name="get_num_students_by_class" value="Click here">
    </form>

    <?php
    if (isset($_POST["get_num_students_by_class"])) {
        get_num_students_by_class($db_conn);
    }
    echo "<hr/>"
    //===================================================================
    ?>

    <?php

    // Define function to retrieve the studentID and name of students who have classes with a specific teacher
    function get_students_by_teacher($db_conn, $teacher_id)
    {

        $sql = "SELECT DISTINCT students.student_id, students.first_name, students.last_name
                FROM students
                INNER JOIN enrolments ON students.student_id = enrolments.student_id
                INNER JOIN classes ON enrolments.class_id = Classes.class_id
                WHERE classes.teacher_id = " . $teacher_id;
        $result = mysqli_query($db_conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th> Student ID </th>
                <th> Name</th>
                </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row['student_id'] . "</td>
                <td>" . $row['first_name'] . " " . $row['last_name'] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No student found.<br/>";
        }
    }
    ?>

    <form method="post" action="questions.php">
        To display studentID and name of students who have classes with a specific teacher<br>
        Please enter Teacher ID (1-6): <br>
        <input type="text" name="teacher_id">
        <input type="submit" name="submit">
    </form>

    <?php
    if (isset($_POST["submit"]) && !empty($_POST["teacher_id"])) {
        $teacher_id = $_POST["teacher_id"];
        get_students_by_teacher($db_conn, $teacher_id);
    }
    echo "<hr/>"
    //===================================================================
    ?>



    <?php

    // Define function to count the number of students who have enrolled in the classes offered by each faculty
    function count_students_per_faculty($db_conn)
    {
        $sql = "SELECT f.name AS faculty_name, COUNT(*) AS enrolment_count
                FROM enrolments e
                JOIN classes c ON e.class_id = c.class_id
                JOIN faculties f ON c.faculty_id = f.faculty_id
                GROUP BY f.faculty_id";
        $result = mysqli_query($db_conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th> Faculty</th>
                <th> Number of Enrolled Students</th>
                </tr>";

            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>
                <td>" . $row['faculty_name'] . "</td>
                <td>" . $row['enrolment_count'] . "</td>
                </tr>";
            }
            echo "</table>";
        } else {
            echo "No student found.<br/>";
        }
    }
    ?>
    <form method="post" action="questions.php">
        To display numbers of students who have enrolled in the classes offered by each faculty<br>
        Click on the button: <br>
        <input type="submit" name="count_students_per_faculty" value="Click here">
    </form>

    <?php
    if (isset($_POST["count_students_per_faculty"])) {
        count_students_per_faculty($db_conn);
    }
    echo "<hr/>"
    //===================================================================
    ?>
    <?php
    // Define function to count the number of teachers in each faculty
    function count_teachers_per_faculty($db_conn)
    {
        $sql = "SELECT faculties.name, COUNT(teachers.teacher_id) as num_teachers 
            FROM faculties 
            LEFT JOIN teachers ON faculties.faculty_id = teachers.faculty_id 
            GROUP BY faculties.faculty_id";
        $result = mysqli_query($db_conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "<table border='1px'>
                <tr>
                <th> Faculty </th>
                <th> #Teachers</th>
                </tr>";
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                <td>" . $row['name'] . "</td>
                <td>" . $row['num_teachers'] . "</td>
                </tr>";
            }
        } else {
            echo "No teacher found.<br/>";
        }
    }
    ?>
    <form method="post" action="questions.php">
        To display number of teachers in each faculty;<br>
        Click on the button: <br>
        <input type="submit" name="count_teachers_per_faculty" value="Click here">
    </form>

    <?php
    if (isset($_POST["count_teachers_per_faculty"])) {
        count_teachers_per_faculty($db_conn);
    }

    //===================================================================
    ?>


</body>

</html>