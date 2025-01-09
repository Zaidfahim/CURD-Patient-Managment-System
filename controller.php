<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'];
    if ($action == "create") {
        createPatient();
    } elseif ($action == "update") {
        updatePatient();
    }
} elseif (isset($_GET['action']) && $_GET['action'] == "delete") {
    deletePatient();
}

function createPatient() {
    global $conn;
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $diagnosis = $_POST['diagnosis'];

    $sql = "INSERT INTO patients (name, age, gender, diagnosis) VALUES ('$name', $age, '$gender', '$diagnosis')";
    if ($conn->query($sql)) {
        header("Location: index.php?message=Patient added successfully");
        exit();
    } else {
        header("Location: index.php?error=Error adding patient: " . $conn->error);
        exit();
    }
}

function updatePatient() {
    global $conn;
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $name = $_POST['name'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $diagnosis = $_POST['diagnosis'];
    
    // Check if all required fields are present
    if ($id && $name && $age && $gender) {
        $sql = "UPDATE patients SET name='$name', age=$age, gender='$gender', diagnosis='$diagnosis' WHERE id=$id";
        
        // Debugging: Display the SQL query to verify it's correct
        // echo $sql; // Uncomment this if you want to debug the query
        
        if ($conn->query($sql) === TRUE) {
            header("Location: index.php?message=Patient updated successfully");
            exit();
        } else {
            header("Location: index.php?error=Error updating patient: " . $conn->error);
            exit();
        }
    } else {
        header("Location: index.php?error=Missing required fields for updating patient");
        exit();
    }
}

function deletePatient() {
    global $conn;
    $id = $_GET['id'];
    if ($conn->query("DELETE FROM patients WHERE id=$id")) {
        header("Location: index.php?message=Patient deleted successfully");
        exit();
    } else {
        header("Location: index.php?error=Error deleting patient: " . $conn->error);
        exit();
    }
}

function getAllPatients() {
    global $conn;
    return $conn->query("SELECT * FROM patients");
}
?>
