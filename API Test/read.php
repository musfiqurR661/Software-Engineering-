<?php
    require_once('db_connect.php');

    // Establish database connection
    $connect = mysqli_connect(HOST, USER, PASS, DB, 3306)
        or die("Cannot connect to the database");

    // Query to fetch students, their courses, and age
    $query = "
        SELECT 
            p.Name AS student_name, 
            p.Age AS student_age, 
            c.Course_name AS course_name
        FROM 
            Person p
        JOIN 
            Student_Course sc ON p.Id = sc.Student_id
        JOIN 
            Course c ON sc.Course_id = c.Course_id
    ";

    $results = mysqli_query($connect, $query)
        or die("Cannot execute query");

    // Prepare the output array
    $students = [];
    while ($row = mysqli_fetch_assoc($results)) {
        $student_name = $row['student_name'];
        if (!isset($students[$student_name])) {
            $students[$student_name] = [
                'age' => $row['student_age'],
                'courses' => []
            ];
        }
        $students[$student_name]['courses'][] = $row['course_name'];
    }

    // Convert the associative array to indexed array for JSON output
    $output = [];
    foreach ($students as $name => $data) {
        $output[] = [
            'name' => $name,
            'age' => $data['age'],
            'courses' => $data['courses']
        ];
    }

    // Return the JSON response
    header('Content-Type: application/json');
    echo json_encode($output);

    // Close the database connection
    mysqli_close($connect);
?>
