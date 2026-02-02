<?php
require_once 'config/config.php';
require_once 'config/db.php';

try {
    echo "<h1>Updating System for Class 11/12 Defaults</h1>";

    // 1. Ensure `department` column is long enough (100 chars just to be safe)
    $pdo->exec("ALTER TABLE users MODIFY department VARCHAR(100)");
    echo "✅ `department` column size increased to 100 characters.<br>";

    // 2. (Optional) Reset existing students to 'Class 11' if they have invalid years like 1, 2, 3, 4
    // This assumes the user wants to migrate old test data to the new format.
    $sql = "UPDATE users SET year = 11 WHERE role='student' AND year IN (1, 2, 3, 4)";
    $count = $pdo->exec($sql);
    if ($count > 0) {
        echo "✅ Updated $count existing students from Year 1-4 to Default 'Class 11'.<br>";
    } else {
        echo "ℹ️ No existing students needed updating (or already updated).<br>";
    }

    echo "<h3>System Ready for Class 11/12 Usage!</h3>";
    echo "<a href='index.php'>Go to Home</a>";

} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
