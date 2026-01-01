<?php require_once 'config.php' ?>
<?php include 'adminHeader.php' ?>

<?php 
if (isset($_SESSION['log_user'])) {
    $user = $_SESSION['log_user'];
    $sql = "SELECT admin_id FROM admin WHERE admin_name='$user'";
    
    if ($result = $conn->query($sql)) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $admin = $row['admin_id'];
        }
    } else {
        echo "no results";
    }
} else {
    header("Location:adminLog.php");
    exit();
}
?>
<link rel="stylesheet" href="src/css/addItem.css" type="text/css">

</header>
<body>
    <form method="post" action="addItem.php" enctype="multipart/form-data">
        <div class="container-1">
            <div class="left-side">
                <label>Product Name</label>
                <input class="product-name" name="productName" type="text" required>
                <div class="wrapper">
                    <div class="category">
                        <label>Category</label>
                        <select class="category-select" name="category" required>
                            <option value="men">Men</option>
                            <option value="women">Women</option>
                            <option value="kid">Kid</option>
                        </select>
                    </div>
                    <div class="type">
                        <label>Type</label>
                        <select class="type-select" name="type" required>
                            <optgroup label="Men">
                                <option value="suit">suit</option>
                                <option value="shirt">Short sleeves shirt</option>
                                <option value="t-shirt">Short sleeves T-shirt</option>
                                <option value="suit">Double Breasted Suit</option>
                                <option value="trousers">Trousers</option>
                            </optgroup>
                            <optgroup label="Women">
                                <option value="BLOUSE">BLOUSE</option>
                                <option value="SKIRTS">Skirts</option>
                                <option value="DRESS">Dresses</option>
                                <option value="PANTS">Pants</option>
                                <option value="SHORTS">Shorts</option>
                            </optgroup>
                            <optgroup label="Kids">
                                <option value="t-shirt">Girls</option>
                                <option value="t-shirt">Boys</option>
                                <option value="t-shirt">Baby collection</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                <label>Description</label>
                <input class="description" name="description" type="text" required>
            </div>
            <div class="middle">
                <div class="product-images">
                    <label>Product Images</label>
                    <input type="file" name="imageAddress" required>
                    <p>*Image must not exceed the size of 4MB</p>
                </div>
                <div class="add-size">
                    <label>Add Size</label>
                    <div class="size-toplayer">
                        <label><input type="radio" id="XS" value="XS" name="Size" required>XS</label>
                        <label><input type="radio" id="S" value="S" name="Size">S</label>
                        <label><input type="radio" id="M" value="M" name="Size">M</label>
                    </div>
                    <div class="size-bottomlayer">
                        <label><input type="radio" id="L" value="L" name="Size">L</label>
                        <label><input type="radio" id="XL" value="XL" name="Size">XL</label>
                    </div>
                </div>
            </div>
            <div class="right-side">
                <label>Price</label>
                <input type="number" name="price" required>
                <label>Qty</label>
                <input class="qty-input" type="number" min="1" value="1" name="qty" required>
                <div class="btn-grp">
                    <input type="submit" value="Add product" name="addproduct">
                    <input type="reset" value="Cancel" name="cancel">
                </div>
            </div>
        </div>
    </form>
</body>
</html>

<?php
if (isset($_POST["addproduct"])) {
    $name = $_POST["productName"];
    $category = $_POST["category"];
    $type = $_POST["type"];
    $item_desc = $conn->real_escape_string($_POST["description"]);
    $size = $_POST["Size"];
    $price = $_POST["price"];
    $qty = $_POST["qty"];

    // Handle file upload
    $target_dir = $_SERVER['DOCUMENT_ROOT'] . '/FASHION-MATRIX/src/image/';
    $target_file = $target_dir . basename($_FILES["imageAddress"]["name"]); // Corrected name here
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file was uploaded
    if (isset($_FILES["imageAddress"])) { // Corrected to check "imageAddress"
        if ($_FILES["imageAddress"]["error"] == UPLOAD_ERR_OK) {
            // Check if image file is an actual image
            $check = getimagesize($_FILES["imageAddress"]["tmp_name"]);
            if ($check === false) {
                echo '<script>alert("File is not an image.")</script>';
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES["imageAddress"]["size"] > 4000000) {
                echo '<script>alert("Sorry, your file is too large.")</script>';
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                echo '<script>alert("Sorry, only JPG, JPEG, PNG files are allowed.")</script>';
                $uploadOk = 0;
            }

            // Try to upload file if everything is okay
            if ($uploadOk) {
                if (move_uploaded_file($_FILES["imageAddress"]["tmp_name"], $target_file)) {
                    // Prepare SQL query
                    $sql = "INSERT INTO item (name, unit_price, type, category, item_desc, stock, image, admin_id, size) 
                            VALUES ('$name', '$price', '$type', '$category', '$item_desc', '$qty', '$target_file', '$admin', '$size')";
                    
                    // Execute the query and handle success/failure
                    if ($conn->query($sql)) {
                        echo '<script>alert("Item added successfully")</script>';
                    } else {
                        echo '<script>alert("Item was not added: ' . htmlspecialchars($conn->error) . '")</script>';
                    }
                } else {
                    echo '<script>alert("Sorry, there was an error uploading your file.")</script>';
                }
            }
        } else {
            echo '<script>alert("File upload error: ' . $_FILES["imageAddress"]["error"] . '")</script>';
        }
    } else {
        echo '<script>alert("No file was uploaded or there was an upload error.")</script>';
    }
}

$conn->close();
?>


