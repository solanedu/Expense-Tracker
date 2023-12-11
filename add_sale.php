<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST["product_name"];
    $date = $_POST["date"];
    $quantity = $_POST["quantity"];
    $amount = $_POST["amount"];

    // Server-side validation for the "Amount" and "Quantity" fields
    if (!is_numeric($amount) || $amount <= 0 || !is_numeric($quantity) || $quantity <= 0) {
        echo "Please enter valid positive numbers for quantity and amount.";
    } else {
        // Insert data into the Sales table
        $sql = "INSERT INTO Sales (ProductName, SaleDate, Quantity, Amount) VALUES ('$product_name', '$date', '$quantity', '$amount')";

        if ($conn->query($sql) === TRUE) {
            // Redirect to sales.php after successfully adding a sale
            header("Location: sales.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
