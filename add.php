<?php
/**
 * Include the database connection file.
 */
include 'db.php';

/**
 * Handles form submission to insert a new inquiry record.
 * 
 * This script sanitizes user input, validates the email format, assigns a unique record number,
 * and inserts data into the database using a prepared statement. If successful, it redirects to `index.php`.
 */

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /**
     * Sanitize and validate user inputs.
     *
     * @var string $firstName The sanitized first name.
     * @var string $lastName The sanitized last name.
     * @var string $email The sanitized and validated email.
     * @var string $comment The sanitized comment.
     */
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $comment = htmlspecialchars(trim($_POST['comment']));

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Invalid email format.");
    }

    // Ensure required fields are not empty
    if (empty($firstName) || empty($lastName) || empty($comment)) {
        die("All fields are required.");
    }

    /**
     * Retrieve the next available record number.
     * 
     * @var int $record_number The next available record number.
     */
    $sql = "SELECT COALESCE(MAX(record_number), 0) + 1 AS next_record_number FROM inquiry";
    $result = $conn->query($sql);
    
    if (!$result) {
        die("Error retrieving record number: " . $conn->error);
    }

    $row = $result->fetch_assoc();
    $record_number = (int) $row['next_record_number']; // Ensure it's an integer

    /**
     * Prepare and execute the SQL insert statement.
     * 
     * @var mysqli_stmt $stmt The prepared statement for inserting data.
     */
    $stmt = $conn->prepare("INSERT INTO inquiry (record_number, firstName, lastName, email, comment) VALUES (?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters and execute
    $stmt->bind_param("issss", $record_number, $firstName, $lastName, $email, $comment);
    
    if ($stmt->execute()) {
        $stmt->close();
        header("Location: index.php?show_records=true&success=1");
        exit();
    } else {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();

    // Redirect to index.php after adding the record
    header("Location: list.php");
    exit(); // Ensure no further code is executed after the redirect
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Inquiry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Add New Inquiry</h1>
        <form method="POST">
            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required></textarea>

            <button type="submit">Add Record</button>
            <button type="button" onclick="window.location.href='index.php?show_records=true';">Cancel</button>
        </form>
    </div>
</body>
</html>
