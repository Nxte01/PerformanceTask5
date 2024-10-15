<?php
include("nav.php");
include("database.php");

$sql = "SELECT * FROM purchases ORDER BY p_date DESC";

$query = mysqli_query($conn, $sql);

if (!$query) {
    echo "Error: " . mysqli_error($conn);
}
?>
</head>
<title>Purchase Record</title>
<body>
    <style>
        .td a[href*="update_p.php"] {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #185875;
            /* Blue accent to match table headings */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .td a[href*="update_p.php"]:hover {
            background-color: #72BF78;
            /* Pink hover effect to match table details */
        }

        .td a[href*="purchases.php?action=delete"] {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: #185875;
            /* Blue accent to match table headings */
            color: white;
            text-decoration: none;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        .td a[href*="purchases.php?action=delete"]:hover {
            background-color: #FB667A;
            /* Pink hover effect to match table details */
        }

        .td a:hover {
            background-color: #FB667A;
            /* Pink hover effect to match table details */
        }
    </style>
    <link rel="stylesheet" href="css/style.css">
    <br>
    <br>
    <h1><span class="blue"></span>Still Waters<span class="blue"></span> <span class="yellow">Antique</span> Database</h1>
        <table class="container">
            <tbody>
                <tr>
                    <th colspan="5">Still Waters Antique Purchases Record</th>
                    <th></th>
                    <th class="th">
                        <a href="insert_p.php">Record a Purchase</a>
                    </th>
                </tr>
                <tr>
                    <th width="15%">Date Purchased</th>
                    <th width="7.5%">Client #</th>
                    <th width="7.5%">Item #</th>
                    <th width="12%">Purchase Cost</th>
                    <th width="13.5%">Condition of the Item</th>
                    <th width="15%" align="center">Purchase ID</th>
                    <th align='center'>Action</th>
                </tr>
                <?php
                while ($result = mysqli_fetch_assoc($query)) {
                    $formatCost = number_format($result['p_cost']);
                    $datePurchased = !empty($result['p_date']) ? date("F d, Y", strtotime($result['p_date'])) : 'N/A';
                ?>
                    <tr>
                        <td><?php echo $datePurchased; ?></td>
                        <td align="center"><?php echo $result['ClientNumber']; ?></td>
                        <td><?php echo $result['item_num']; ?></td>
                        <td>₱ <?php echo $formatCost; ?></td>
                        <td><?php echo $result['condition_at_purchase']; ?></td>
                        <td><?php echo $result['purchase_id']; ?></td>
                        <td align="center" width="20%" class="td">
                            <a href='purchases.php?action=delete&purchase_id=<?php echo $result["purchase_id"]; ?>' onclick="return confirm('Are you sure you want to delete this record?');">Delete</a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    <?php
    if (isset($_GET['action']) && isset($_GET['purchase_id'])) {
        $action = $_GET['action'];
        $purchase_id = $_GET['purchase_id'];

        if ($action == 'delete') {
            $sql = "DELETE FROM purchases WHERE purchase_id = '$purchase_id'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Purchase Record has been deleted successfully.'); window.location='purchases.php';</script>";
            } else {
                echo "<script>alert('Failed to delete the Purchase Record.'); window.location='purchases.php';</script>";
            }
        }
    }
    ?>
</body>

</html>