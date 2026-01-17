<?php
require_once 'php/auth.php';
checkAdminAuth();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - BLK SHOP</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <header>
        <nav>
            <div class="brand">
                <img src="logo.png" alt="BLK SHOP Logo">
                <span class="logo-text">BLK SHOP Admin</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" class="btn-nav">View Shop</a></li>
                <li><a href="php/logout.php" class="btn-nav btn-delete">Logout</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">

        <div class="admin-header">
            <h2>Dashboard</h2>
        </div>

        <!-- Add Product Section -->
        <section class="admin-section">
            <h3>Add New Product</h3>
            <form id="addProductForm" enctype="multipart/form-data">
                <div style="display: grid; gap: 10px; grid-template-columns: 1fr 1fr; margin-bottom: 10px;">
                    <div>
                        <label>Product Name</label>
                        <input type="text" name="name" required>
                    </div>
                    <div>
                        <label>Price (DA)</label>
                        <input type="number" name="price" step="0.01" required>
                    </div>
                </div>
                <label>Product Image</label>
                <input type="file" name="image" accept="image/*" required>
                <button type="submit">Add Product</button>
            </form>
        </section>

        <!-- Product List -->
        <section class="admin-section">
            <h3>Manage Products</h3>
            <table id="productsTable">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Loaded via AJAX -->
                </tbody>
            </table>
        </section>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Edit Product</h2>
            <form id="editProductForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit-id">

                <label>Name</label>
                <input type="text" name="name" id="edit-nom" required>

                <label>Price</label>
                <input type="number" name="price" id="edit-prix" step="0.01" required>

                <label>Change Image (Optional):</label>
                <input type="file" name="image" accept="image/*">

                <button type="submit">Update Product</button>
            </form>
        </div>
    </div>

    <footer>
        <div class="contact-us">
            <h3>Contact Us</h3>
            <div class="contact-info">
                <span>&#9993; abdallah.boulkenafet@gmail.com</span>
                <span>&#9742; +213699084300</span>
            </div>
        </div>
    </footer>

    <script src="js/script.js"></script>
</body>

</html>