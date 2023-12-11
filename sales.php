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

        <h1>Sales Tracker</h1>

        <!-- Add New Sale Form -->

        <h2>Add New Sale</h2>

        <!-- Form for adding a new sale -->
        <div class="add-sale-form">
            <form action="add_sale.php" method="post" onsubmit="return validateForm()">
                <!-- Input fields for sale details -->
                <!-- Adjust as needed based on your sales table structure -->
                <label for="product_name">Product Name:</label>
                <input type="text" id="product_name" name="product_name" required><br><br>

                <label for="date">Date:</label>
                <input type="date" id="date" name="date" required><br><br>

                <label for="quantity">Quantity:</label>
                <input type="text" id="quantity" name="quantity" required><br><br>

                <label for="amount">Amount:</label>
                <input type="text" id="amount" name="amount" required><br><br>

                <button type="submit">Add Sale</button>
            </form>
        </div>

        <h2>Sale List</h2>

        <?php
        include 'db_connection.php';

        // Check if form submitted and delete button pressed
        if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_sale"])) {
            $saleID = $_POST["sale_id"];
            $sql = "DELETE FROM Sales WHERE SaleID = '$saleID'";
            if ($conn->query($sql) === TRUE) {
                echo "Record deleted successfully. ";
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        // Define default sorting options
        $sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'SaleDate';
        $sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';

        // Map user-friendly column names to actual column names in the database
        $columnMapping = [
            'product_name' => 'ProductName',
            'date' => 'SaleDate',
            'quantity' => 'Quantity',
            'amount' => 'Amount'
        ];

        // Validate and set the actual column name
        if (array_key_exists($sortColumn, $columnMapping)) {
            $actualSortColumn = $columnMapping[$sortColumn];
        } else {
            // Default to SaleDate if an invalid column is provided
            $actualSortColumn = 'SaleDate';
        }

        // Select all sales from the database with sorting
        $sql = "SELECT * FROM Sales ORDER BY $actualSortColumn $sortOrder";
        $result = $conn->query($sql);

        // Display the sales list in a table
        if ($result->num_rows > 0) {
            echo "<form method='get' action='sales.php'>";
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

            echo "<table><tr><th>Sale ID</th><th>Date</th><th>Product Name</th><th>Quantity</th><th>Amount</th><th>Action</th></tr>";

            while ($row = $result->fetch_assoc()) {
                // Display each sale as a table row with a delete button
                echo "<tr><td>" . $row["SaleID"] . "</td><td>" . $row["SaleDate"] . "</td><td>" . $row["ProductName"] . "</td><td>" . $row["Quantity"] . "</td><td>" . $row["Amount"] . "</td><td>
              <form method='post' action='sales.php'>
              <input type='hidden' name='sale_id' value='" . $row["SaleID"] . "'>
              <button type='submit' name='delete_sale'>Delete</button>
              </form>
              </td></tr>";
            }
            echo "</table>";
        } else {
            echo "No sales recorded.";
        }
        $conn->close();
        ?>

    </div>

</body>

</html>
