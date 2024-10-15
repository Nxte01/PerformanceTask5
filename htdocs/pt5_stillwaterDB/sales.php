<?php
include("nav.php");
include("database.php");

$sql = "
    SELECT 
        date_sold, 
        sellingPrice, 
        commissionPaid, 
        saleID, 
        ClientNumber,
        (SELECT givenName FROM allclients WHERE ClientNumber = sales.ClientNumber) AS givenName,
        (SELECT lastName FROM allclients WHERE ClientNumber = sales.ClientNumber) AS lastName,
        item_num,
        (SELECT description FROM items WHERE item_num = sales.item_num) AS description
    FROM sales
    ORDER BY date_sold ASC
";

$query = mysqli_query($conn, $sql);

if (!$query) {
    echo "Error: " . mysqli_error($conn);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Record</title>
</head>
<style>
    .td a[href*="update_s.php"] {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 10px;
        background-color: #185875;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .td a[href*="update_s.php"]:hover {
        background-color: #72BF78;
    }

    .td a[href*="sales.php?action=delete"] {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 10px;
        background-color: #185875;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .td a[href*="sales.php?action=delete"]:hover {
        background-color: #FB667A;
    }
</style>
<link rel="stylesheet" href="css/style.css">

<body>
    <br><br>
    <h1><span class="blue"></span>Still Waters<span class="blue"></span> <span class="yellow">Antique</span> Database</h1>
    <table class="container">
        <thead>
            <tr>
                <th colspan="7">Still Waters Antique Sales Record</th>
                <th class="th"><a href="insert_s.php">Add Sales Record</a></th>
            </tr>
            <tr>
                <th>Date Sold</th>
                <th>Item's Owner</th>
                <th>Item Description</th>
                <th>Selling Price</th>
                <th>Commission Paid</th>
                <th>Sales Tax (12%)</th> <!-- Updated column header -->
                <th>Sale ID</th>
                <th align="center">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($result = mysqli_fetch_assoc($query)) {
                $saleID = $result['saleID'];
                $description = $result['description'];
                $lastName = $result['lastName'];
                $givenName = $result['givenName'];
                
                $sellingPrice = $result['sellingPrice'] !== null ? number_format($result['sellingPrice'], 2) : ' 0.00';
                $commission = $result['commissionPaid'] !== null ? number_format($result['commissionPaid'], 2) : ' 0.00';

                $salesTax = $result['sellingPrice'] !== null ? $result['sellingPrice'] * 0.12 : 0;
                $formatSalesTax = number_format($salesTax, 2); // format sales tax

                $dateSold = !empty($result['date_sold']) ? date("F d, Y", strtotime($result['date_sold'])) : '&nbsp;';
            ?>
                <tr>
                    <td><?php echo $dateSold; ?></td>
                    <td><?php echo $givenName . ' ' . $lastName; ?></td>
                    <td><?php echo $description; ?></td>
                    <td>₱ <?php echo $sellingPrice; ?></td>
                    <td>₱ <?php echo $commission; ?></td>
                    <td>₱ <?php echo $formatSalesTax; ?></td> <!-- Display calculated sales tax -->
                    <td><?php echo $saleID; ?></td>
                    <td align="center" width="20%" class="td">
                        <a href='sales.php?action=delete&saleID=<?php echo $result["saleID"]; ?>' onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <?php
    if (isset($_GET['action']) && isset($_GET['saleID'])) {
        $action = $_GET['action'];
        $saleID = $_GET['saleID'];

        if ($action == 'delete') {
            $sql = "DELETE FROM sales WHERE saleID = '$saleID'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Record has been deleted successfully.'); window.location='sales.php';</script>";
            } else {
                echo "<script>alert('Failed to delete the Record.'); window.location='sales.php';</script>";
            }
        }
    }
    ?>
</body>

</html>
