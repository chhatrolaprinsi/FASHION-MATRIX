<?php require 'config.php';
include 'adminHeader.php';
?>

<link rel="stylesheet" href="src/css/editItem.css" type="text/css">

</head>
<body>

<?php 
$sql = "SELECT * FROM item";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<form id='UpdateItemform' method='post' action='deleteItem.php'>";
    echo "<div class='cart-table'>";
    echo "<center>";
    echo "<table>";
    echo "<th>Description</th>";
    echo "<th>Size</th>";
    echo "<th>Item</th>";
    echo "<th>Price</th>";
    echo "<th>Quantity</th>";
    echo "<th>Action</th>";  // Column for Edit and Delete buttons
    
    while ($row = $result->fetch_assoc()) {
        $itemCode = $row['item_code'];

        echo "<tr>";
        echo "<td><p>" . $row['name'] . "</p></td>";
        echo "<td><center><p>" . $row['size'] . "</p></center></td>";
        echo "<input type='hidden' name='itms_code[]' id='itms_code[]' value='" . $itemCode . "'>";
        echo "<td><center><img src='" . $row['image'] . "' alt='Item Image'></center></td>";
        echo "<td><center><p>" . $row['unit_price'] . "</p></center></td>";
        echo "<td><center><input type='number' name='stock[]' min='1' value='" . $row['stock'] . "'></center></td>";  
        
        // Combined Edit and Delete buttons in the same <td>
        echo "<td>";
        
        // Edit button (now using GET method)
        echo "<a href='editproduct.php?item_code=" . $itemCode . "' class='update-btn' onclick=\"return confirm('Are you sure you want to update this item?');\">Update</a>";

        // Delete button (still using POST method)
        echo "<form method='post' action='deleteItem.php' style='display:inline-block; margin-left: 10px;'>";
        echo "<input type='hidden' name='item_code' value='" . $itemCode . "'>";
        echo "<input type='submit' value='Delete' class='delete-btn' onclick=\"return confirm('Are you sure you want to delete this item?');\">";
        echo "</form>";

        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</center>";
    echo "</div>";
    echo "</form>";
    
} else {
    echo "<p>No items found.</p>";
}
?>

</body>
