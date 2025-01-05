<?php
// Include the database connection file
require '../config/db.php'; // This file contains the PDO connection setup

// Get the email from the form submission
$email = $_POST['email'] ?? null; // If 'email' is not provided, default to null

// Validate that the email is not empty
if (empty($email)) {
    echo json_encode([
        "status" => "error", // Response status
        "message" => "Please enter your email address." // Error message
    ]);
    exit; // Stop further script execution
}

// Validate that the email format is correct
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "status" => "error",
        "message" => "The email address is not valid. Please try again."
    ]);
    exit;
}

try {
    // Prepare the SQL statement to call the stored procedure
    $addSubscriberQuery = $conn->prepare("CALL AddSubscriber(:email)");

    // Bind the email parameter to the placeholder in the query
    $addSubscriberQuery->bindParam(':email', $email, PDO::PARAM_STR);

    // Execute the stored procedure
    $addSubscriberQuery->execute();

    // If execution is successful, return a success message
    echo json_encode([
        "status" => "success",
        "message" => "You have successfully subscribed!" // Success message for the user
    ]);
} catch (PDOException $e) {
    // Check if the error is caused by a duplicate entry
    if ($e->getCode() == '23000') { // MySQL error code for duplicate entry
        echo json_encode([
            "status" => "error",
            "message" => "This email is already subscribed." // Inform user about duplicate
        ]);
    } else {
        // Handle any other database error
        echo json_encode([
            "status" => "error",
            "message" => "Something went wrong. Please try again later." // Generic error message
        ]);
    }
}
?>
