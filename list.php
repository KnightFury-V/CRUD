<?php
/**
 * Display Inquiry Records
 *
 * This script retrieves and displays records from the `inquiry` table.
 *
 * @package InquiryRecords
 */

/**
 * Include the database connection file.
 */
include 'db.php';

/**
 * Fetch all records from the `inquiry` table.
 */
$stmt = $conn->prepare("SELECT * FROM inquiry ORDER BY record_number");

// Execute the query
$stmt->execute();

/**
 * Get the result set from the executed statement.
 *
 * @var mysqli_result $result The result set containing inquiry records.
 */
$result = $stmt->get_result();

// Close the prepared statement
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <h1>INQUIRIES</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Record Number</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Comment</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td data-label="Record Number"><?= $row['record_number']; ?></td>
                            <td data-label="First Name"><?= $row['firstName']; ?></td>
                            <td data-label="Last Name"><?= $row['lastName']; ?></td>
                            <td data-label="Email"><?= $row['email']; ?></td>
                            <td data-label="Comment"><?= $row['comment']; ?></td>
                            <td class="actions" data-label="Actions">
                                <a href="edit.php?record_number=<?= $row['record_number']; ?>">Edit</a>
                                <a href="delete.php?record_number=<?= $row['record_number']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <a href="add.php" class="button">Add New Inquiry</a>
        <?php else: ?>
            <p style="color:black;">No records found.</p>
        <?php endif; ?>
        <a href="index.php" class="button">Back to Home</a>
    </div>
</body>
</html>