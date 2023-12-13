<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>Available classes</title>
</head>

<body>

    <h1>Music Conservatorium</h1>
    <h2>Available Classes</h2>

    <?php

    $errors = 0;
    $db_host = "localhost";
    $db_user = "root";
    $db_password = "";
    $db_name = "music_conservatorium";

    if (isset($_COOKIE['LastRequestDate']))
        $LastRequestDate = $_COOKIE['LastRequestDate'];
    else
        $LastRequestDate = "";

    try {
        $db_connection = mysqli_connect($db_host, $db_user, $db_password);
        mysqli_select_db($db_connection, $db_name);
        $table_students = "students";
        $select_table = "SELECT * FROM $table_students WHERE student_id='" . $_SESSION['student_id'] . "'";
        $qRes = mysqli_query($db_connection, $select_table);
        if (mysqli_num_rows($qRes) == 0) {
            die("<p>Invalid student ID!</p>");
        }
        $Row = mysqli_fetch_assoc($qRes);
        $student_name = $Row['first_name'] . " " . $Row['last_name'];

        $table_enrolments = "enrolments";
        $approved_enrolments = 0;
        $count_classes = "SELECT COUNT(class_id) FROM $table_enrolments WHERE student_id='" . $_SESSION["student_id"] . "' AND date_approved IS NOT NULL";
        $qRes = mysqli_query($db_connection, $count_classes);
        if (mysqli_num_rows($qRes) > 0) {
            $Row = mysqli_fetch_row($qRes);
            $approved_enrolments = $Row[0];
            mysqli_free_result($qRes);
        }

        $selected_classes = array();
        $select_classes = "SELECT class_id FROM $table_enrolments WHERE student_id='" . $_SESSION["student_id"] . "'";
        $qRes = mysqli_query($db_connection, $select_classes);
        if (mysqli_num_rows($qRes) > 0) {
            while (($Row = mysqli_fetch_row($qRes)) != FALSE) {
                $selected_classes[] = $Row[0];
            }
            mysqli_free_result($qRes);
        }
        $enrolled_classes = array();
        $select_class_id = "SELECT class_id FROM $table_enrolments WHERE date_approved IS NOT NULL";
        $qRes = mysqli_query($db_connection, $select_class_id);
        if (mysqli_num_rows($qRes) > 0) {
            while (($Row = mysqli_fetch_row($qRes)) != FALSE)
                $enrolled_classes[] = $Row[0];
            mysqli_free_result($qRes);
        }

        $table_classes = "classes";
        $classes = array();
        $select_class_columns = "SELECT class_id, name,capacity,teacher_id,faculty_id FROM $table_classes";
        $qRes = mysqli_query($db_connection, $select_class_columns);
        if (mysqli_num_rows($qRes) > 0) {
            while (($Row = mysqli_fetch_assoc($qRes)) != FALSE)
                $classes[] = $Row;
            mysqli_free_result($qRes);
        }
        //mysqli_close($db_connection);
    } catch (mysqli_sql_exception $e) {
        die("<p>Error in connection with the database server or database </p>\n" . mysqli_error($db_connection));
    }
    if (!empty($LastRequestDate))
        echo "<p>You last requested enrollment on " . $LastRequestDate . ".</p>\n";

    echo "<table border='1' width='100%'>\n";
    echo "<tr>\n";
    echo " <th style='background-color:cyan'>Name</th>\n";
    echo " <th style='background-color:cyan'>Capacity</th>\n";
    echo " <th style='background-color:cyan'>Teacher ID</th>\n";
    echo " <th style='background-color:cyan'>Faculty Name</th>\n";
    echo " <th style='background-color:cyan'>Status</th>\n";
    echo "</tr>\n";
    foreach ($classes as $class) {
        $sql = "SELECT COUNT(enrolment_id) as count FROM $table_enrolments WHERE class_id='" . $class['class_id'] . "'";
        $result = mysqli_query($db_connection, $sql);
        $row = mysqli_fetch_assoc($result);
        $count_enrolments = intval($row['count']);

        $total_capacity_in_class =  (htmlentities($class['capacity']) - $count_enrolments);



        if (!in_array($class['class_id'], $enrolled_classes)) {
            if (htmlentities($class['faculty_id']) == 1) {
                $faculty_name = "Instrumental";
            } else {
                $faculty_name = "Ensembles";
            }
            $teacher_id = htmlentities($class['teacher_id']);
            $find_teacher = "SELECT first_name,last_name FROM teachers WHERE teacher_id='" . $teacher_id . "'";
            $result = mysqli_query($db_connection, $find_teacher);

            $row = mysqli_fetch_assoc($result);
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];

            $full_name = $first_name . " " . $last_name;






            echo "<tr>\n";
            echo " <td>" . htmlentities($class['name']) . "</td>\n";
            echo " <td>" . $total_capacity_in_class . "</td>\n";

            echo " <td>" . $full_name . "</td>\n";
            echo " <td>" . $faculty_name . "</td>\n";
            echo " <td>";


            if (in_array($class['class_id'], $selected_classes)) {
                echo "Enrolled";
                echo "| <a href='witdraw class.php?" . SID . "&class_id=" . $class['class_id'] . "'>Withdraw</a>";
            } else {
                if ($approved_enrolments > 0) {
                    echo "Open";
                } else {
                    if ($total_capacity_in_class < 1) {
                        echo " Class is full.";
                    } else {
                        echo "<a href='request class.php?" . SID . "&class_id=" . $class['class_id'] . "'>Available</a>";
                    }
                }
            }
            echo "</td>\n";
            echo "</tr>\n";
        }
    }

    echo "</table>\n";
    echo "<p><a href='student login.php'>Log Out</a></p>";
    echo "<p><a href='questions.php'>For more information</a></p>\n";
    ?>
</body>


</html>