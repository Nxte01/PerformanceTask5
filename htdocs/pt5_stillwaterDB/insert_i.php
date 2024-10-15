<?php
include("nav.php");
include("database.php");
?>
<style>
  body {
      font-family: 'Open Sans', sans-serif;
      font-weight: 300;
      line-height: 1.42em;
      color: #A7A1AE; /* Light gray text */
      background-color: #1F2739; /* Dark background */
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      height: 100vh; /* Full height of the viewport */
      margin: 0;
  }

  h2 {
      font-size: 2em;
      font-weight: bold; /* Bold the header */
      text-align: center;
      color: #FB667A; /* Light red for the form heading */
      margin-bottom: 20px;
      margin-top: 0;
  }

  form {
      width: 50%;
      padding: 20px;
      background-color: #323C50; /* Darker background for form */
      border-radius: 10px;
      box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
  }

  label {
      display: block;
      margin-bottom: 10px;
      color: #A7A1AE; /* Light gray for label text */
      font-weight: bold;
  }

  input[type="text"], input[type="number"], input[type="datetime-local"], input[type="submit"], select {
      width: 100%;
      padding: 10px;
      margin-bottom: 20px;
      border: 2px solid #4DC3FA; /* Blue border */
      border-radius: 5px;
      box-sizing: border-box;
      font-size: 16px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  }

  input[type="text"], input[type="number"] {
      background-color: #2C3446; /* Dark input background */
      color: #FFF; /* White text in input fields */
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  }

  input[type="submit"] {
      background-color: #FFF842; /* Yellow submit button */
      color: #403E10; /* Dark text */
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s ease;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
  }

  input[type="submit"]:hover {
      background-color: #FB667A; /* Red hover for submit button */
      color: #FFF; /* White text on hover */
  }
</style>
<br><br>
<h2>&lt;Add Item&gt;</h2>
<br>
  <form action="insert_i.php" method="post">
    
    <label for="condition">Condition:</label>
    <input type="text" id="condition" name="condition" required><br>

    <label for="item_type">Item Type:</label>
    <input type="text" id="item_type" name="item_type" required><br>

    <label for="asking_price">Asking Price:</label>
    <input type="number" id="asking_price" name="asking_price" required><br><br>

    <label for="description">Description:</label>
    <input type="text" id="description" name="description" required><br>

    <label for="critiqued_comments">Comments:</label>
    <input type="text" id="critiqued_comments" name="critiqued_comments" required><br>
    
    <input type="submit" name="submit" value="Submit">
  </form>

  <?php
  if(isset($_POST['submit'])){
    $condition = trim($_POST['condition']);
    $item_type = trim($_POST['item_type']);
    $asking_price = trim($_POST['asking_price']);
    $description = trim($_POST['description']);
    $comments = trim($_POST['critiqued_comments']);

    $sql = "SELECT * FROM items WHERE description = '$description'";
    $query = mysqli_query($conn, $sql);
    if ($query) {
      if (mysqli_num_rows($query) > 0) {
        echo "Item already exists";
      } else {
        $sql = "INSERT INTO items (`condition`, item_type, asking_price, description, critiqued_comments) 
                  VALUES ('$condition', '$item_type', '$asking_price', '$description', '$comments')";
        if (mysqli_query($conn, $sql)) {
          echo "Item list Updated";
        } else {
          echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
      }
    } else {
      echo "Connection Error: " . mysqli_error($conn);
    }
  }
?>
