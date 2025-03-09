<?php
/**
 * Record Deletion Script
 *
 * This script deletes a record from the `inquiry` table based on the provided `record_number`.
 * It uses prepared statements to prevent SQL injection.
 *
 * @package RecordDeletion
 */

 /**
  * Include the database connection file.
  */
include 'db.php';

/**
 * Get the record number from the GET request.
 *
 * @var string $record_number The record number of the inquiry to be deleted.
 */
$record_number = $_GET['record_number'];

/**
 * Prepare the SQL DELETE statement.
 *
 * @var mysqli_stmt $stmt The prepared statement for deleting the record.
 */
$stmt = $conn->prepare("DELETE FROM inquiry WHERE record_number = ?");

/**
 * Bind parameters to the prepared statement.
 *
 * @param string $record_number The record number to be deleted.
 */
$stmt->bind_param("s", $record_number);

/**
 * Execute the deletion query.
 */
$stmt->execute();

/**
 * Redirect the user to the index page after deletion.
 */
header("Location: list.php");

?>
