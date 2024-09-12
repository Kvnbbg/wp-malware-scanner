<?php
// Database connection details
$servername = "your_db_host";
$username = "your_db_username";
$password = "your_db_password";
$dbname = "your_db_name";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to search for suspicious entries in wp_options
$sql = "
    SELECT option_name, option_value
    FROM wp_options
    WHERE option_name IN ('siteurl', 'home', 'wpcode_snippets', 'wpseo', 'redirection_options')
    AND (
        option_value LIKE '%<script%' 
        OR option_value LIKE '%eval%' 
        OR option_value LIKE '%base64_decode%' 
        OR option_value LIKE '%document.write%'
    )
";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Suspicious entries found:\n";
    while($row = $result->fetch_assoc()) {
        echo "Option Name: " . $row["option_name"] . "\n";
        echo "Option Value: " . substr($row["option_value"], 0, 300) . "\n"; // Display first 300 characters of option_value
        echo "-------------------------------\n";
    }
} else {
    echo "No suspicious entries found.\n";
}

$conn->close();
?>
