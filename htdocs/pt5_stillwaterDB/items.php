<?php
include("nav.php");
include("database.php");

// Only show items that have not been sold
$sql = "SELECT * FROM items WHERE is_sold = 0 ORDER BY item_num DESC";

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
    <title>Items List</title>
</head>
<style>
    .td a[href*="update_i.php"] {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 10px;
        background-color: #185875;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .td a[href*="update_i.php"]:hover {
        background-color: #72BF78;
    }

    .td a[href*="items.php?action=delete"] {
        display: inline-block;
        padding: 10px 20px;
        margin: 0 10px;
        background-color: #185875;
        color: white;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    .td a[href*="items.php?action=delete"]:hover {
        background-color: #FB667A;
    }
</style>
<link rel="stylesheet" href="css/style.css">

<body>
    <br>
    <br>
    <h1><span class="blue"></span>Still Waters<span class="blue"></span> <span class="yellow">Antique</span> Database</h1>
    <table class="container">
        <tbody>
            <tr>
                <th colspan="4">Still Waters Items List</th>
                <th></th>
                <th>
                <th class="th">
                    <a href="insert_i.php">Insert Item</a>
                </th>
            </tr>
            <tr>
                <th width="10.5%">Condition</th>
                <th width="11%">Item Type</th>
                <th width="10%">Price</th>
                <th width="15%">Description</th>
                <th width="20%">Critiqued Comments</th>
                <th width="7.5%">Item #</th>
                <th align='center'>Actions</th>
            </tr>
            <?php while ($result = mysqli_fetch_assoc($query)) {
                $formatPrice = number_format($result['asking_price']); ?>
                <tr>
                    <td><?php echo $result['condition']; ?></td>
                    <td><?php echo $result['item_type']; ?></td>
                    <td><?php echo $formatPrice; ?></td>
                    <td><?php echo $result['description']; ?></td>
                    <td><?php echo $result['critiqued_comments']; ?></td>
                    <td><?php echo $result['item_num']; ?></td>
                    <td align="center" width="20%" class="td">
                        <a href='update_i.php?action=edit&item_num=<?php echo $result["item_num"]; ?>'>Edit</a>
                        <a href='items.php?action=delete&item_num=<?php echo $result["item_num"]; ?>' onclick="return confirm('Are you sure you want to mark this item as sold?');">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <?php
        if (isset($_GET['action']) && isset($_GET['item_num'])) {
            $action = $_GET['action'];
            $item_num = $_GET['item_num'];

            if ($action == 'delete') {
                // Mark item as sold instead of deleting it
                $sql = "UPDATE items SET is_sold = 1 WHERE item_num = '$item_num'";
                if (mysqli_query($conn, $sql)) {
                    echo "<script>alert('Item has been marked as sold.'); window.location='items.php';</script>";
                } else {
                    echo "<script>alert('Failed to update the item status.'); window.location='items.php';</script>";
                }
            }
        }
        ?>
</body>

</html>
