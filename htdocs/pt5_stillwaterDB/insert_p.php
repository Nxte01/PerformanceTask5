<?php
include("nav.php");
include("database.php");

$isNewClient = isset($_POST['is_new_client']) ? $_POST['is_new_client'] : 'existing';

if (isset($_POST['submit'])) {
    if ($isNewClient == "existing") {
        $clientNumber = $_POST['ClientNumber'];  
    } else {
        $givenName = trim($_POST['givenName']);
        $lastName = trim($_POST['lastName']);
        $ClientAddress = trim($_POST['ClientAddress']);

        $sql = "SELECT * FROM allclients WHERE givenName = '$givenName' AND lastName = '$lastName'";
        $query = mysqli_query($conn, $sql);

        if ($query && mysqli_num_rows($query) > 0) {
            $clientData = mysqli_fetch_assoc($query);
            $clientNumber = $clientData['ClientNumber'];
        } else {
            $sql = "INSERT INTO allclients (givenName, lastName, ClientAddress) VALUES ('$givenName', '$lastName', '$ClientAddress')";
            if (mysqli_query($conn, $sql)) {
                $clientNumber = mysqli_insert_id($conn);
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                exit;
            }
        }
    }

    $asking_price = $_POST['asking_price'];
    $item_type = $_POST['item_type'];
    $description = $_POST['description'];
    $critiqued_comments = $_POST['critiqued_comments'];
    $condition_at_purchase = $_POST['condition_at_purchase'];

    $insertItemSql = "INSERT INTO items (asking_price, item_type, `description`, critiqued_comments, `condition`) 
                      VALUES ('$asking_price', '$item_type', '$description', '$critiqued_comments', '$condition_at_purchase')";

    if (mysqli_query($conn, $insertItemSql)) {
        $itemNumber = mysqli_insert_id($conn);

        $p_date = $_POST['p_date'];
        $p_cost = $_POST['p_cost'];

        $insertPurchaseSql = "INSERT INTO purchases (p_date, p_cost, `condition_at_purchase`, ClientNumber, item_num) 
                               VALUES ('$p_date', '$p_cost', '$condition_at_purchase', $clientNumber, $itemNumber)";

        if (mysqli_query($conn, $insertPurchaseSql)) {
            echo "<script>alert('Client, Purchase, and Item have been added successfully.'); window.location='purchases.php';</script>";
        } else {
            echo "<script>alert('Failed to add Purchase: " . mysqli_error($conn) . "'); window.location='purchases.php';</script>";
        }
    } else {
        echo "<script>alert('Failed to add Item: " . mysqli_error($conn) . "'); window.location='purchases.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Purchase Record</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #1F2739;
            color: #A7A1AE;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
        }

        h1 {
            color: #FB667A;
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
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        input[type="submit"],
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 2px solid #4DC3FA;
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        }

        input[type="text"],
        input[type="number"] {
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
</head>

<body>
    <h1>Record a Purchase</h1>
    <form action="" method="POST">
        <h2>Client Information</h2>
        <label for="is_new_client">Select a type of Client:</label>
        <select name="is_new_client" id="is_new_client" required onchange="this.form.submit()"> <!-- Automatically submit the form on selection -->
            <option value="existing" <?= $isNewClient == 'existing' ? 'selected' : '' ?>>Existing Client</option>
            <option value="new" <?= $isNewClient == 'new' ? 'selected' : '' ?>>New Client</option>
        </select>
        <br><br>
    <!-- Existing Client Section -->
        <?php if ($isNewClient == 'existing'): ?>
        
            <div id="existing_client_fields">
                <label for="ClientNumber">Client:</label>
                <select id="ClientNumber" name="ClientNumber" required>
                    <option value="">Select a Client</option>
                    <?php
                    $client_sql = "SELECT ClientNumber, givenName, lastName FROM allclients";
                    $client_query = mysqli_query($conn, $client_sql);
                    while ($row = mysqli_fetch_assoc($client_query)) {
                        echo "<option value='" . $row['ClientNumber'] . "'>" . $row['givenName'] . " " . $row['lastName'] . "</option>";
                    }
                    ?>
                </select>
            </div>
    <!-- New Client Section -->
        <?php else: ?>
            <div id="new_client_fields">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="lastName" required>

                <label for="givenName">Given Name:</label>
                <input type="text" id="givenName" name="givenName" required>

                <label for="ClientAddress">Address:</label>
                <input type="text" id="ClientAddress" name="ClientAddress" required>
            </div>
        <?php endif; ?>

        <hr>
    <!-- Form -->
        <h2>Purchase Information</h2>
        <label for="p_date">Date Purchased:</label>
        <input type="date" name="p_date" required><br><br>

        <label for="p_cost">Purchase Cost:</label>
        <input type="number" name="p_cost" required>

        <label for="condition_at_purchase">Condition:</label>
        <input type="text" name="condition_at_purchase" required>

        <hr>

        <h2>Item Information</h2>
        <label for="asking_price">Asking Price:</label>
        <input type="number" name="asking_price" required>

        <label for="item_type">Item Type:</label>
        <input type="text" name="item_type" required>

        <label for="description">Description:</label>
        <input type="text" name="description" required>

        <label for="critiqued_comments">Critiqued Comments:</label>
        <input type="text" name="critiqued_comments" required>

        <input type="submit" name="submit" value="Add Record">
    </form>
</body>

</html>