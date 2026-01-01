<?php
require 'config.php'; // Include your database connection

// Check for connection error
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if item_code is set in the GET request
if (isset($_GET['item_code'])) {
    $item_code = $_GET['item_code'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM item WHERE item_code = ?");
    if ($stmt === false) {
        die("SQL error: " . $conn->error);
    }
    $stmt->bind_param("s", $item_code);
    $stmt->execute();

    // Get the result
    $result = $stmt->get_result();

    // Check if the item exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Check if the form is submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form values using $_POST
            $name = $_POST['name'];
            $size = $_POST['size'];
            $unit_price = $_POST['unit_price'];
            $stock = $_POST['stock'];
            $item_desc = $_POST['description'];

            // Initialize image variable
            $image = $row['image']; // Default to existing image
            
            // Handle file upload for image
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $target_dir = "src/img/";
                $target_file = $target_dir . basename($_FILES["image"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
            
                // Check if the file is an actual image
                $check = getimagesize($_FILES["image"]["tmp_name"]);
                if ($check !== false) {
                    // Move uploaded file
                    if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                        $image = $target_file; // Update image to the new one
                    } else {
                        echo "Error uploading file.";
                    }
                } else {
                    echo "File is not an image.";
                }
            }

            // Prepare SQL statement to update the product
            $stmt = $conn->prepare("UPDATE item SET name = ?, size = ?, unit_price = ?, image = ?, stock = ?, item_desc = ? WHERE item_code = ?");
            
            // Check if the statement was prepared successfully
            if ($stmt === false) {
                die("SQL error during preparation: " . $conn->error);
            }
            
            // Correctly bind the parameters and execute the query
            // Note: Ensure the types match: s=string, i=integer
            $stmt->bind_param("ssissss", $name, $size, $unit_price, $image, $stock, $item_desc, $item_code);
            
            // Execute the statement and check for errors
            if ($stmt->execute()) {
                echo "Product updated successfully.";
            } else {
                echo "Error updating product: " . $stmt->error;
            }
            $stmt->close();
        }
?>

        <!-- Start of HTML section -->
        <link rel="stylesheet" type="text/css" href="src/css/editproduct.css">

        <div class="container">
            <h4>Edit Product</h4>

            <!-- Display the image of the current product -->
            <div>
                <label for="current_image">Current Image:</label><br>
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product Image" style="max-width: 200px;"><br><br>
            </div>

            <form action="" method="POST" enctype="multipart/form-data">
                <label for="name">Name:</label>
                <input type="text" name="name" value="<?php echo htmlspecialchars($row['name']); ?>" required><br>

                <label for="size">Size:</label>
                <input type="text" name="size" value="<?php echo htmlspecialchars($row['size']); ?>" required><br>

                <label for="unit_price">Price:</label>
                <input type="number" step="0.01" name="unit_price" value="<?php echo htmlspecialchars($row['unit_price']); ?>" required><br>

                <label for="quantity">Quantity:</label>
                <input type="number" name="stock" min="1" value="<?php echo htmlspecialchars($row['stock']); ?>" required><br> 

                <label for="item_desc">Description:</label>
                <textarea name="description" rows="6" cols="50" required><?php echo htmlspecialchars($row['item_desc'], ENT_QUOTES, 'UTF-8'); ?></textarea><br>

                <label for="image">Upload New Image (optional):</label>
                <input type="file" name="image"><br><br>

                <!-- Hidden field to pass the item_code -->
                <input type="hidden" name="item_code" value="<?php echo $item_code; ?>">

                <input type="submit" value="Update" class="update-btn">
            </form>
        </div>

<?php
    } else {
        echo "<h4>Product not found.</h4>";
    }
} else {
    echo "<h4>No product selected.</h4>";
}
$conn->close();
?>
