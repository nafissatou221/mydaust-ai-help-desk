<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_database";


function escapeData($conn, $data) {
    return $conn->real_escape_string($data);
}


function saveToJson($data, $form_type) {
    $json_file = 'knowledge_base.json';
    

    if (file_exists($json_file)) {
        $current_data = json_decode(file_get_contents($json_file), true);
    } else {
        $current_data = [
            'bus_schedule' => [],
            'code_of_conduct' => [],
            'admission_form' => []
        ];
    }
    
  
    $current_data[$form_type][] = $data;
    
 
    file_put_contents($json_file, json_encode($current_data, JSON_PRETTY_PRINT));
}


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$form_type = $_POST['form_type'];


switch ($form_type) {
    case 'bus_schedule':
      
        $route_name = escapeData($conn, $_POST['Route_Name']);
        $start_location = escapeData($conn, $_POST['Start_Location']);
        $departure_time = escapeData($conn, $_POST['Departure_Time']);
        $stop_location1 = escapeData($conn, $_POST['Stop_Location1']);
        $stop_time1 = escapeData($conn, $_POST['Stop_Time1']);
        $stop_location2 = escapeData($conn, $_POST['Stop_Location2']);
        $stop_time2 = escapeData($conn, $_POST['Stop_Time2']);
        $stop_location3 = escapeData($conn, $_POST['Stop_Location3']);
        $stop_time3 = escapeData($conn, $_POST['Stop_Time3']);
        $end_location = escapeData($conn, $_POST['End_Location']);
        $arrival_time = escapeData($conn, $_POST['Arrival_Time']);
        $days_operations = escapeData($conn, $_POST['Days_Operations']);
        $bus_driver = escapeData($conn, $_POST['Bus_Driver']);
        $capacity = escapeData($conn, $_POST['Capacity']);
        
        
        $sql = "INSERT INTO bus_schedule (Route_Name, Start_Location, Departure_Time, Stop_Location1, Stop_Time1, Stop_Location2, Stop_Time2, Stop_Location3, Stop_Time3, End_Location, Arrival_Time, Days_Operations, Bus_Driver, Capacity)
                VALUES ('$route_name', '$start_location', '$departure_time', '$stop_location1', '$stop_time1', '$stop_location2', '$stop_time2', '$stop_location3', '$stop_time3', '$end_location', '$arrival_time', '$days_operations', '$bus_driver', '$capacity')";
        
        
        $json_data = [
            'Route_Name' => $route_name,
            'Start_Location' => $start_location,
            'Departure_Time' => $departure_time,
            'Stop_Location1' => $stop_location1,
            'Stop_Time1' => $stop_time1,
            'Stop_Location2' => $stop_location2,
            'Stop_Time2' => $stop_time2,
            'Stop_Location3' => $stop_location3,
            'Stop_Time3' => $stop_time3,
            'End_Location' => $end_location,
            'Arrival_Time' => $arrival_time,
            'Days_Operations' => $days_operations,
            'Bus_Driver' => $bus_driver,
            'Capacity' => $capacity
        ];
        break;
        
    case 'code_of_conduct':
       
        $academic_year = escapeData($conn, $_POST['Academic_Year']);
        $name_of_code = escapeData($conn, $_POST['Name_of_Code']);
        $details = escapeData($conn, $_POST['Details']);
        
       
        $sql = "INSERT INTO code_of_conduct (Academic_Year, Name_of_Code, Details)
                VALUES ('$academic_year', '$name_of_code', '$details')";
        
        
        $json_data = [
            'Academic_Year' => $academic_year,
            'Name_of_Code' => $name_of_code,
            'Details' => $details
        ];
        break;
        
    case 'admission_form':
        
        $general_information = escapeData($conn, $_POST['General_Information']);
        $academic_year = escapeData($conn, $_POST['Academic_Year']);
        $program = escapeData($conn, $_POST['Program']);
        $details = escapeData($conn, $_POST['Details']);
        
       
        $sql = "INSERT INTO admission_form (General_Information, Academic_Year, Program, Details)
                VALUES ('$general_information', '$academic_year', '$program', '$details')";
        
       
        $json_data = [
            'General_Information' => $general_information,
            'Academic_Year' => $academic_year,
            'Program' => $program,
            'Details' => $details
        ];
        break;
        
    default:
        echo "Type de formulaire non reconnu";
        exit;
}


if ($conn->query($sql) === TRUE) {
    
    saveToJson($json_data, $form_type);
    echo "<div style='text-align:center; margin-top:50px;'>";
    echo "<h2>Données soumises avec succès!</h2>";
    echo "<p>Les données ont été enregistrées dans la base de données et dans le fichier de connaissances.</p>";
    echo "<a href='page0.html' style='display:inline-block; margin-top:20px; padding:10px 20px; background-color:#004080; color:white; text-decoration:none; border-radius:5px;'>Retour à l'accueil</a>";
    echo "</div>";
} else {
    echo "Erreur : " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>