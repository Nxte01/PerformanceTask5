<?php
?>
<style>
    nav {
        text-align: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        background-color: #1F2739;
        padding: 10px 0;
        z-index: 10;
    }

    nav a {
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

    nav a:hover {
        background-color: #FB667A;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
        /* Pink hover effect to match table details */
    }
</style>
<nav>
    <a href="c_list.php"><b>Clients</b></a>
    <a href="items.php"><B>Items</b></a>
    <a href="purchases.php"><B>Store Purchases</b></a>
    <a href="sales.php"><B>Sales</b></a>
</nav>
