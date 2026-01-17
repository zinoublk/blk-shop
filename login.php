<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - BLK SHOP</title>
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
        </ul>
    </nav>
</header>

<div class="login-container">
    <h2>Admin Login</h2>
    <form id="loginForm">
        <label>Username</label>
        <input type="text" name="username" required>
        
        <label>Password</label>
        <input type="password" name="password" required>
        
        <button type="submit" style="width: 100%;">Login</button>
        <div id="login-message" style="margin-top: 15px; text-align: center;"></div>
    </form>
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

<script>
$(document).ready(function(){
    $('#loginForm').submit(function(e){
        e.preventDefault();
        $.ajax({
            url: 'php/login.php',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(response){
                if(response.success){
                    window.location.href = 'admin.php';
                } else {
                    $('#login-message').html('<p style="color:red;">'+response.message+'</p>');
                }
            },
            error: function(xhr, status, error) {
                console.log(xhr.responseText);
                $('#login-message').html('<p style="color:red;">Server communication error. Check console/logs.</p>');
            }
        });
    });
});
</script>

</body>
</html>