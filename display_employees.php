<?php
include 'db_connection.php'; // Include the database connection file

$sql = "SELECT * FROM Employees";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Phone</th><th>Job Title</th><th>Hourly Pay</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["EmployeeID"]."</td><td>".$row["FirstName"]."</td><td>".$row["LastName"]."</td><td>".$row["Email"]."</td><td>".$row["Phone"]."</td><td>".$row["JobTitle"]."</td><td>".$row["HourlyPay"]."</td></tr>";
    }
    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>
