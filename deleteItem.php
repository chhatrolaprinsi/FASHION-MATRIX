<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $item_code = $_POST['item_code'];

    // First, delete from the order_details table
    $stmt = $conn->prepare("DELETE FROM order_details WHERE item_code = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $item_code);
        $stmt->execute();
        $stmt->close();
    }

    // Then, delete the item
    $stmt = $conn->prepare("DELETE FROM item WHERE item_code = ?");
    
    if ($stmt) {
        $stmt->bind_param("s", $item_code); 
        
        if ($stmt->execute()) {
            echo "Item and related details deleted successfully.";
            header("Location: editItem.php"); 
            exit();
        } else {
            echo "Error deleting item: " . $stmt->error;
        }
        
        $stmt->close();
    } else {
        echo "Failed to prepare the SQL statement.";
    }
} else {
    echo "Invalid request.";
}
$conn->close();
?>
