<?php
// Include the database connection file
include 'db_connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve data from the form
    $itemName = $_POST["item_name"];
    $quantity = $_POST["quantity"];
    $purchaseType = $_POST["purchase_type"];
    $supplier = $_POST["supplier"];
    $price = $_POST["price"];

    // Validate the data (you can add more validation if needed)
    if (empty($itemName) || empty($quantity) || empty($price)) {
        echo "Please fill in all the required fields.";
    } else {
        // Insert data into the Inventory table
        $sql = "INSERT INTO Inventory (ItemName, Quantity, PurchaseType, Supplier, Price) 
                VALUES ('$itemName', '$quantity', '$purchaseType', '$supplier', '$price')";

        if ($conn->query($sql) === TRUE) {
            echo "Inventory item added successfully.";
        } else {
            echo "Error adding inventory item: " . $conn->error;
        }
    }
}

// Close the database connection
$conn->close();
?>
