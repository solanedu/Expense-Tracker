<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_name = $_POST["expense_name"];
    $date = $_POST["date"];
    $purchase_type = $_POST["purchase_type"];
    $vendor = $_POST["vendor"]; // Optional field
    $amount = $_POST["amount"];

    // Server-side validation for the "Amount" field
    if (!is_numeric($amount) || $amount <= 0) {
        echo "Please enter a valid positive number for the amount.";
    } else {
        // Insert data into the Expenses table
        $sql = "INSERT INTO Expenses (ExpenseName, ExpenseDate, PurchaseType, Vendor, Amount) VALUES ('$expense_name', '$date', '$purchase_type', '$vendor', '$amount')";

 if ($conn->query($sql) === TRUE) {
            // Redirect to expenses.php after successfully adding an expense
            header("Location: expenses.php");
            exit();
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
