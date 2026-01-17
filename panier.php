<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLK SHOP - Cart</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>

    <header>
        <nav>
            <div class="brand">
                <img src="logo.png" alt="BLK SHOP Logo">
                <span class="logo-text">BLK SHOP</span>
            </div>
            <ul class="nav-links">
                <li><a href="index.php" class="btn-nav">Home</a></li>
                <li><a href="panier.php" class="btn-nav">Cart (<span id="cart-count">0</span>)</a></li>
            </ul>
        </nav>
    </header>

    <div class="container">
        <h2>Your Shopping Cart</h2>
        <div id="cart-content">
            <p>Loading cart...</p>
        </div>
        <div id="cart-summary" style="text-align: right; margin-top: 20px;">
            <!-- Total price -->
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