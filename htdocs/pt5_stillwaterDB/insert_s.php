<?php
include("nav.php");
include("database.php");
?>

<title>Add Sales Record</title>
<style>
    body {
        font-family: 'Open Sans', sans-serif;
        font-weight: 300;
        line-height: 1.42em;
        color: #A7A1AE;
        background-color: #1F2739;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    h2 {
        font-size: 2em;
        font-weight: bold;
        text-align: center;
        color: #FB667A;
        margin-bottom: 20px;
        margin-top: 0;
    }

    form {
        width: 50%;
        padding: 20px;
        background-color: #323C50;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    label {
        display: block;
        margin-bottom: 10px;
        color: #A7A1AE;
        font-weight: bold;
    }

    input[type="text"], input[type="number"], input[type="date"], input[type="submit"], select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 2px solid #4DC3FA;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    input[type="text"], input[type="number"] {
        background-color: #2C3446;
        color: #FFF;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    input[type="submit"] {
        background-color: #FFF842;
        color: #403E10;
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    input[type="submit"]:hover {
        background-color: #FB667A;
        color: #FFF;
    }
</style>
<link rel="stylesheet" href="css/style.css">
<br><br>
<h2>&lt;Record a Sold Item&gt;</h2>
<br>
<form action="insert_s.php" method="post">

    <label for="item_num">Item:</label>
    <select id="item_num" name="item_num" required>
        <option value="">Select an Item</option>
        <?php
        $item_sql = "SELECT item_num FROM items WHERE is_sold = 0";
        $item_query = mysqli_query($conn, $item_sql);
        while ($row = mysqli_fetch_assoc($item_query)) {
            echo "<option value='" . $row['item_num'] . "'>" . $row['item_num'] . "</option>";
        }
        ?>
    </select><br>

    <label for="ClientNumber">Client:</label>
    <select id="ClientNumber" name="ClientNumber">
        <option value="">Select a Client (Optional)</option>
        <?php
        $client_sql = "SELECT ClientNumber, givenName, lastName FROM allclients";
        $client_query = mysqli_query($conn, $client_sql);
        while ($row = mysqli_fetch_assoc($client_query)) {
            echo "<option value='" . $row['ClientNumber'] . "'>" . $row['givenName'] . " " . $row['lastName'] . "</option>";
        }
        ?>
    </select><br>

    <label for="sellingPrice">Selling Price:</label>
    <input type="number" id="sellingPrice" name="sellingPrice" required oninput="calculateSalesTax()"><br>

    <label for="commissionPaid">Commission Paid (Optional):</label>
    <input type="number" id="commissionPaid" name="commissionPaid"><br>

    <label for="salesTax">Sales Tax (12%):</label>
    <input type="number" id="salesTax" name="salesTax" readonly><br>

    <label for="date_sold">Date Sold:</label>
    <input type="date" id="date_sold" name="date_sold" required><br>

    <input type="submit" name="submit" value="Submit">
</form>

<script>
    //AI SCRIPT, HEHE----------------------------------------------------
    function calculateSalesTax() {
        var sellingPrice = document.getElementById("sellingPrice").value;
        var salesTax = sellingPrice * 0.12;
        document.getElementById("salesTax").value = salesTax.toFixed(2);
    }
    //--------------------------------------------------------------------
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sellingPrice = $_POST['sellingPrice'];
    $commissionPaid = $_POST['commissionPaid'] ?: null;
    $clientNumber = $_POST['ClientNumber'] ?: null;
    $itemNum = $_POST['item_num'];
    $salesTax = $_POST['salesTax'];
    $date_sold = $_POST['date_sold'];

    $sql = "INSERT INTO sales (sellingPrice, commissionPaid, salesTax, ClientNumber, item_num, date_sold) 
            VALUES ('$sellingPrice', " . ($commissionPaid !== null ? "'$commissionPaid'" : "NULL") . ", 
            '$salesTax', " . ($clientNumber !== null ? "'$clientNumber'" : "NULL") . ", 
            '$itemNum', '$date_sold')";

    if (mysqli_query($conn, $sql)) {
        $update_item_sql = "UPDATE items SET is_sold = 1 WHERE item_num = '$itemNum'";
        mysqli_query($conn, $update_item_sql);
        echo "<script>alert('Record has been added successfully.'); window.location='sales.php';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
