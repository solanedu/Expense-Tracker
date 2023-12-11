<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #333;
            overflow: hidden;
        }

        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .add-expense-form {
            max-width: 400px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <!-- Navigation Bar -->

    <div class="navbar">
        <!-- <a href="display_employees.php">Employees</a> -->
        <a href="expenses.php">Expenses</a>
        <a href="inventory.php">Inventory</a>
        <a href="sales.php">Sales</a>
    </div>

    <div class="container">

        <h1>Inventory Management</h1>

        <!-- Add New Expense Form -->

        <h2>Add New Inventory Item</h2>

<!-- Form for adding a new expense -->

<div class="add-expense-form">
            <form action="add_inventory.php" method="post" onsubmit="return validateForm()">
                <label for="item_name">Item Name:</label>
                <input type="text" id="item_name" name="item_name" required><br><br>

                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" required><br><br>

        <label for="purchase_type">Inventory Type:</label>
        <select id="purchase_type" name="purchase_type" required>
            <option value="food">Food</option>
            <option value="beverage">Beverage</option>
            <option value="supplies">Supplies</option>

        </select><br><br>

                <label for="supplier">Supplier (Optional):</label>
                <input type="text" id="supplier" name="supplier"><br><br>

                <label for="price">Price:</label>
                <input type="text" id="price" name="price" required><br><br>

                <button type="submit">Add Item</button>
            </form>

<!-- JavaScript function for form validation -->


<script>
function validateForm() {
                    var quantity = document.getElementById("quantity").value;
                    var price = document.getElementById("price").value;

                    var parsedQuantity = parseInt(quantity);
                    var parsedPrice = parseFloat(price);

                    if (isNaN(parsedQuantity) || parsedQuantity <= 0 || isNaN(parsedPrice) || parsedPrice <= 0) {
                        alert("Please enter valid positive numbers for quantity and price.");
                        return false;
                    }
                    return true;
                }
    return true;
}
</script>

</div>
        <h2>Inventory List</h2>

        <?php
        include 'db_connection.php';

        // Check if form submitted and delete button pressed
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_item"])) {
            $itemName = $_POST["item_name"];
            $sql = "DELETE FROM Inventory WHERE ItemName = '$itemName'";
            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully. ";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        // Define default sorting options
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'ItemName';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'ASC';

        // Map user-friendly column names to actual column names in the database
        $columnMapping = [
            'item_name' => 'ItemName',
            'quantity' => 'Quantity',
            'purchase_type' => 'PurchaseType',
            'supplier' => 'Supplier',
            'price' => 'Price'
        ];

        // Validate and set the actual column name
        if (array_key_exists($sortColumn, $columnMapping)) {
            $actualSortColumn = $columnMapping[$sortColumn];
        } else {
            // Default to ItemName if an invalid column is provided
            $actualSortColumn = 'ItemName';
        }

        // Select all items from the database with sorting
        $sql = "SELECT * FROM Inventory ORDER BY $actualSortColumn $sortOrder";
        $result = $conn->query($sql);

        // Display the item list in a table
        if ($result->num_rows > 0) {
            echo "<form method='get' action='inventory.php'>";
            echo "<label for='sortColumn'>Sort By:</label>";
            echo "<select id='sortColumn' name='sort'>";
            $currentSortColumn = array_search($actualSortColumn, $columnMapping);
            foreach ($columnMapping as $key => $value) {
                echo "<option value='$key' " . ($currentSortColumn == $key ? 'selected' : '') . ">" . ucwords(str_replace('_', ' ', $key)) . "</option>";
            }
            echo "</select>";

            echo "<label for='sortOrder'>Order:</label>";
            echo "<select id='sortOrder' name='order'>";
            $orderOptions = ['ASC', 'DESC'];
            foreach ($orderOptions as $option) {
                echo "<option value='$option' " . ($sortOrder == $option ? 'selected' : '') . ">" . ucwords(strtolower($option)) . "</option>";
            }
            echo "</select>";

            echo "<button type='submit'>Apply Sorting</button>";
            echo "</form>";

            echo "<table><tr><th>Item Name</th><th>Quantity</th><th>Purchase Type</th><th>Supplier</th><th>Price</th><th>Action</th></tr>";

            while ($row = $result->fetch_assoc()) {
                // Display each item as a table row with a delete button
                echo "<tr><td>".$row["ItemName"]."</td><td>".$row["Quantity"]."</td><td>".$row["PurchaseType"]."</td><td>".$row["Supplier"]."</td><td>".$row["Price"]."</td><td>
                  <form method='post' action='inventory.php'>
                  <input type='hidden' name='item_name' value='".$row["ItemName"]."'>
                  <button type='submit' name='delete_item'>Delete</button>
                  </form>
                  </td></tr>";
            }
            echo "</table>";
        } else {
            echo "No items in the inventory.";
        }
        $conn->close();
        ?>

    </div>

</body>

</html>

