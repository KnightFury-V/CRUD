<?php
/**
 * Edit Inquiry Record
 *
 * This script retrieves an existing record from the `inquiry` table for editing
 * and updates it upon form submission.
 *
 * @package EditInquiry
 */

/**
 * Include the database connection file.
 */
include 'db.php'; 

/**
 * Initialize the record data variable.
 *
 * @var array|null $row Holds the fetched record details if found, otherwise remains null.
 */
$row = null; 

/**
 * Retrieve the record data if `record_number` is provided via GET request.
 */
if (isset($_GET['record_number'])) {
    /**
     * @var string $record_number The record number retrieved from the GET request.
     */
    $record_number = $_GET['record_number'];

    /**
     * Prepare the SQL SELECT statement to fetch the record.
     *
     * @var mysqli_stmt $stmt The prepared statement for retrieving record data.
     */
    $stmt = $conn->prepare("SELECT * FROM inquiry WHERE record_number = ?");
    
    /**
     * Bind parameters and execute the query.
     *
     * @param string $record_number The record number used to fetch the data.
     */
    $stmt->bind_param("s", $record_number);
    $stmt->execute();

    /**
     * Get the result and fetch the record details.
     *
     * @var mysqli_result $result The result set returned by the query.
     */
    $result = $stmt->get_result();
    $row = $result->fetch_assoc(); // Fetch the row

    // Close the prepared statement
    $stmt->close();
}

/**
 * Handle form submission for updating the record.
 */
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /**
     * Retrieve updated values from the form submission.
     *
     * @var string $record_number The record number of the record being updated.
     * @var string $firstName The updated first name.
     * @var string $lastName The updated last name.
     * @var string $email The updated email address.
     * @var string $comment The updated comment.
     */
    $record_number = $_POST['record_number'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $comment = $_POST['comment'];

    /**
     * Validate input data before updating.
     * Ensures email format is valid and required fields are not empty.
     */
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($firstName) && !empty($lastName) && !empty($comment)) {
        /**
         * Prepare the SQL UPDATE statement.
         *
         * @var mysqli_stmt $stmt The prepared statement for updating the record.
         */
        $stmt = $conn->prepare("UPDATE inquiry SET firstName = ?, lastName = ?, email = ?, comment = ? WHERE record_number = ?");

        /**
         * Bind parameters to the prepared statement.
         *
         * @param string $firstName The updated first name.
         * @param string $lastName The updated last name.
         * @param string $email The updated email.
         * @param string $comment The updated comment.
         * @param string $record_number The record number of the entry being updated.
         */
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $comment, $record_number);
        
        // Execute the update statement
        $stmt->execute();
        $stmt->close();

        /**
         * Redirect to list.php after updating the record.
         */
        header("Location: list.php");
        exit(); // Ensure no further code is executed after the redirect
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Inquiry</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Edit Inquiry</h1>
        <form method="POST">
            <!-- Hidden input to store the record_number -->
            <input type="hidden" name="record_number" value="<?= isset($row['record_number']) ? $row['record_number'] : ''; ?>">

            <label for="firstName">First Name:</label>
            <input type="text" id="firstName" name="firstName" value="<?= isset($row['firstName']) ? $row['firstName'] : ''; ?>" required>

            <label for="lastName">Last Name:</label>
            <input type="text" id="lastName" name="lastName" value="<?= isset($row['lastName']) ? $row['lastName'] : ''; ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?= isset($row['email']) ? $row['email'] : ''; ?>" required>

            <label for="comment">Comment:</label>
            <textarea id="comment" name="comment" required><?= isset($row['comment']) ? $row['comment'] : ''; ?></textarea>

            <button type="submit">Update Record</button>
            <button type="button" onclick="window.location.href='list.php';">Cancel</button>
        </form>
    </div>
</body>
</html>