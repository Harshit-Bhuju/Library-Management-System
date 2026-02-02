<?php
require_once 'config/config.php';
require_once 'config/db.php';

try {
    echo "Starting database update...<br>";

    // 1. Rename student_id to roll_no in users table
    // Check if column exists first to avoid error if run multiple times
    $stmt = $pdo->query("SHOW COLUMNS FROM users LIKE 'student_id'");
    $column = $stmt->fetch();

    if ($column) {
        $sql = "ALTER TABLE users CHANGE student_id roll_no VARCHAR(20) UNIQUE";
        $pdo->exec($sql);
        echo "✅ Column 'student_id' renamed to 'roll_no'.<br>";
        
        // Optional: Rename index if you want to be thorough, but usually not strictly required for functionality
        // MySQL often keeps the old index name. 
        // We can try to rename the index if it exists.
    } else {
        echo "ℹ️ Column 'student_id' not found (maybe already renamed).<br>";
    }

    echo "Database update completed successfully.";

} catch (PDOException $e) {
    echo "❌ Error updating database: " . $e->getMessage();
}
?>
