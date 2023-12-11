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

        <h1>Expense Tracker</h1>

        <!-- Add New Expense Form -->

        <h2>Add New Expense</h2>

<!-- Form for adding a new expense -->

 <div class="add-expense-form">
    <form action="add_expense.php" method="post" onsubmit="return validateForm()">
        <label for="expense_name">Expense Name:</label>
        <input type="text" id="expense_name" name="expense_name" required><br><br>

        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required><br><br>

        <label for="purchase_type">Expense Type:</label>
        <select id="purchase_type" name="purchase_type" required>
            <option value="insurance">Insurance</option>
            <option value="rent">Rent</option>
            <option value="utility">Utility</option>

        </select><br><br>

        <label for="vendor">Vendor (Optional):</label>
        <input type="text" id="vendor" name="vendor"><br><br>

        <label for="amount">Amount:</label>
        <input type="text" id="amount" name="amount" required><br><br>

        <button type="submit">Add Expense</button>
    </form>

<!-- JavaScript function for form validation -->


<script>
    function validateForm() {
    var amount = document.getElementById("amount").value;
    // Use parseFloat to parse the input as a floating-point number
    var parsedAmount = parseFloat(amount);
    
    if (isNaN(parsedAmount) || parsedAmount <= 0) {
        alert("Please enter a valid positive number for the amount.");
        return false;
    }
    return true;
}
</script>

</div>
        <h2>Expense List</h2>

        
<?php
include 'db_connection.php';

// Check if form submitted and delete button pressed
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_expense"])) {
    $expenseName = $_POST["expense_name"];
    $sql = "DELETE FROM Expenses WHERE ExpenseName = '$expenseName'";
    if ($conn->query($sql) === TRUE) {
        echo "Record deleted successfully. ";
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Define default sorting options
$sortColumn = isset($_GET['sort']) ? $_GET['sort'] : 'ExpenseDate';
$sortOrder = isset($_GET['order']) ? $_GET['order'] : 'DESC';

// Map user-friendly column names to actual column names in the database
$columnMapping = [
    'expense_name' => 'ExpenseName',
    'date' => 'ExpenseDate',
    'purchase_type' => 'PurchaseType',
    'vendor' => 'Vendor',
    'amount' => 'Amount'
];

// Validate and set the actual column name
if (array_key_exists($sortColumn, $columnMapping)) {
    $actualSortColumn = $columnMapping[$sortColumn];
} else {
    // Default to ExpenseDate if an invalid column is provided
    $actualSortColumn = 'ExpenseDate';
}

// Select all expenses from the database with sorting
$sql = "SELECT * FROM Expenses ORDER BY $actualSortColumn $sortOrder";
$result = $conn->query($sql);

// Display the expense list in a table
if ($result->num_rows > 0) {
    echo "<form method='get' action='expenses.php'>";
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

    echo "<table><tr><th>Expense Name</th><th>Date</th><th>Purchase Type</th><th>Vendor</th><th>Amount</th><th>Action</th></tr>";

    while ($row = $result->fetch_assoc()) {
        // Display each expense as a table row with a delete button
        echo "<tr><td>".$row["ExpenseName"]."</td><td>".$row["ExpenseDate"]."</td><td>".$row["PurchaseType"]."</td><td>".$row["Vendor"]."</td><td>".$row["Amount"]."</td><td>
              <form method='post' action='expenses.php'>
              <input type='hidden' name='expense_name' value='".$row["ExpenseName"]."'>
              <button type='submit' name='delete_expense'>Delete</button>
              </form>
              </td></tr>";
    }
    echo "</table>";
} else {
    echo "No expenses recorded.";
}
$conn->close();

?>

    </div>

</body>

</html>
