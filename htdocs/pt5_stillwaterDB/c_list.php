<?php
include("nav.php");
include("database.php");

$sql = "SELECT * FROM allclients ORDER BY lastName ASC";
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
    <title>Clients List</title>
</head>
<style>
    .td a[href*="update_c.php"] {
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

    .td a[href*="update_c.php"]:hover {
        background-color: #72BF78;
        /* Pink hover effect to match table details */
    }

    .td a[href*="c_list.php?action=delete"] {
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

    .td a[href*="c_list.php?action=delete"]:hover {
        background-color: #FB667A;
        /* Pink hover effect to match table details */
    }

    .td a:hover {
        background-color: #FB667A;
        /* Pink hover effect to match table details */
    }
</style>
<link rel="stylesheet" href="css/style.css">

<body>
    <br><br><br>
    <h1><span class="blue"></span>Still Waters<span class="blue"></span> <span class="yellow">Antique</span> Database</h1>
    <form action="c_list.php" method="POST">
        <table class="container" border="0">
            <thead>
                <tr>
                    <th colspan="4">Still Waters Clients List</th>
                    <th class="th">
                        <a href="insert_c.php">Insert Client</a>
                    </th>
                </tr>
                <tr>
                    <th align="left">Last Name</th>
                    <th align="left">Given Name</th>
                    <th align="center">Address</th>
                    <th align="center">Client #</th>
                    <th align="center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Loop through all clients and display them
                while ($result = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <td><?php echo $result['lastName']; ?></td>
                        <td><?php echo $result['givenName']; ?></td>
                        <td><?php echo $result['ClientAddress']; ?></td>
                        <td align="center"><?php echo $result['ClientNumber']; ?></td>
                        <td align="center" width="20%" class="td">
                            <a href='update_c.php?action=edit&ClientNumber=<?php echo $result["ClientNumber"]; ?>'>Edit</a>
                            <a href='c_list.php?action=delete&ClientNumber=<?php echo $result["ClientNumber"]; ?>' onclick="return confirm('Are you sure you want to delete this client?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </form>

    <?php
    if (isset($_GET['action']) && isset($_GET['ClientNumber'])) {
        $action = $_GET['action'];
        $clientNumber = $_GET['ClientNumber'];

        if ($action == 'delete') {
            $sql = "DELETE FROM allclients WHERE ClientNumber = '$clientNumber'";
            if (mysqli_query($conn, $sql)) {
                echo "<script>alert('Client has been deleted successfully.'); window.location='c_list.php';</script>";
            } else {
                echo "<script>alert('Failed to delete client.'); window.location='c_list.php';</script>";
            }
        }
    }
    ?>
</body>

</html>