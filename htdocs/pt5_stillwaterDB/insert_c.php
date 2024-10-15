<?php
include("nav.php");
include("database.php");
?>
<style>
    body {
        font-family: 'Open Sans', sans-serif;
        font-weight: 300;
        line-height: 1.42em;
        color: #A7A1AE;
        /* Light gray text */
        background-color: #1F2739;
        /* Dark background */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        height: 100vh;
        /* Full height of the viewport */
        margin: 0;
    }

    h2 {
        font-size: 2em;
        font-weight: bold;
        /* Bold the header */
        text-align: center;
        color: #FB667A;
        /* Light red for the form heading */
        margin-bottom: 20px;
        margin-top: 0;
    }

    form {
        width: 50%;
        padding: 20px;
        background-color: #323C50;
        /* Darker background for form */
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    label {
        display: block;
        margin-bottom: 10px;
        color: #A7A1AE;
        /* Light gray for label text */
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="datetime-local"],
    input[type="submit"],
    select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 2px solid #4DC3FA;
        /* Blue border */
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    input[type="text"],
    input[type="number"] {
        background-color: #2C3446;
        /* Dark input background */
        color: #FFF;
        /* White text in input fields */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    input[type="submit"] {
        background-color: #FFF842;
        /* Yellow submit button */
        color: #403E10;
        /* Dark text */
        font-weight: bold;
        cursor: pointer;
        transition: background-color 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    input[type="submit"]:hover {
        background-color: #FB667A;
        /* Red hover for submit button */
        color: #FFF;
        /* White text on hover */
    }
</style>
<h2>&lt;Add Client&gt;</h2>
<br>
<form action="insert_c.php" method="post">

    <label for="lastName">Last Name:</label>
    <input type="text" id="lastName" name="lastName" required><br>
    <label for="givenName">Given Name:</label>
    <input type="text" id="givenName" name="givenName" required><br>
    <label for="ClientAddress">Address:</label>
    <input type="text" id="ClientAddress" name="ClientAddress" required><br>
    <input type="submit" name="submit" value="Submit">
</form>

<?php

if (isset($_POST['submit'])) {
    $givenName = trim($_POST['givenName']);
    $lastName = trim($_POST['lastName']);
    $ClientAddress = trim($_POST['ClientAddress']);

    $sql = "SELECT * FROM allclients WHERE givenName = '$givenName' AND lastName = '$lastName'";
    $query = mysqli_query($conn, $sql);

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            $clientData = mysqli_fetch_assoc($query);
            $clientNumber = $clientData['ClientNumber'];
            echo "Client already exists with ClientNumber: $clientNumber";
            return $clientNumber;
        } else {
            // Insert new client
            $sql = "INSERT INTO allclients (givenName, lastName, ClientAddress) VALUES ('$givenName', '$lastName', '$ClientAddress')";
            if (mysqli_query($conn, $sql)) {
                $clientNumber = mysqli_insert_id($conn);
                echo "Client list Updated with ClientNumber: $clientNumber";
                return $clientNumber;
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
        }
    } else {
        echo "Connection Error: " . mysqli_error($conn);
    }
}
?>