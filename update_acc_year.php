<?php
// Set script execution time limit
set_time_limit(0); // Unlimited execution time

// Database connection (using PDO)
$dsn = 'mysql:host=localhost;dbname=adi_dravidar';
$username = 'root';
$password = '4/rb5sO2s3TpL4gu';
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die('Connection failed: ' . $e->getMessage());
}

// Update records in batches
$batchSize = 500; // Number of records to process per batch
$lastId = 0; // Start with the first record

do {
    try {
        // Start a transaction to ensure atomicity and improve performance
        $pdo->beginTransaction();

        // Prepare the update statement using the primary key (assuming 'id' is the primary key)
        $stmt = $pdo->prepare(
            "UPDATE attendancerecordinfo 
             SET acc_year = 1 
             WHERE acc_year IS NULL AND id > :lastId 
             ORDER BY id ASC 
             LIMIT :batchSize"
        );
        
        // Bind the parameters
        $stmt->bindValue(':lastId', $lastId, PDO::PARAM_INT);
        $stmt->bindValue(':batchSize', $batchSize, PDO::PARAM_INT);
        
        // Execute the update query
        $stmt->execute();
        
        // Commit the transaction
        $pdo->commit();
        
        // Get the last updated ID
        $rowCount = $stmt->rowCount();
        if ($rowCount > 0) {
            $lastId = $pdo->lastInsertId(); // Update the last ID
        }
    } catch (PDOException $e) {
        // Rollback the transaction in case of error
        $pdo->rollBack();
        echo "Error: " . $e->getMessage();
        break;
    }
} while ($rowCount > 0); // Continue until no rows are updated

echo "Update completed successfully!";
?>
