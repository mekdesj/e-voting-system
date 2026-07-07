<?php
// Database import script

$servername = "localhost";
$username = "root";
$password = "";

// Create connection (without database selected first)
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Read the schema file
$schemaFile = __DIR__ . '/schema.sql';

if (!file_exists($schemaFile)) {
    die("Schema file not found: $schemaFile");
}

$sqlContent = file_get_contents($schemaFile);

// Execute the SQL statements
$statements = array_filter(array_map('trim', explode(';', $sqlContent)));

$successCount = 0;
$errorCount = 0;

foreach ($statements as $statement) {
    if (!empty($statement)) {
        if ($conn->multi_query($statement . ";")) {
            $successCount++;
            
            // Process multiple results if any
            while ($conn->more_results()) {
                $conn->next_result();
            }
        } else {
            $errorCount++;
            echo "Error executing statement: " . $conn->error . "\n";
        }
    }
}

$conn->close();

echo "✓ Database import completed!\n";
echo "Success: $successCount statements executed\n";
if ($errorCount > 0) {
    echo "Errors: $errorCount statements failed\n";
} else {
    echo "No errors encountered!\n";
}
echo "\nYou can now access the e-voting system at:\n";
echo "http://localhost/e-voting-system/\n\n";
echo "Sample login credentials:\n";
echo "Admin: admin / admin\n";
echo "Officer: officer / officer\n";
?>
