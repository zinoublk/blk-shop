<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbname = 'blkshop';

try {
    // Connect to MySQL server (no DB selected yet to create it)
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to MySQL server.<br>\n";

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "Database '$dbname' checked/created.<br>\n";

    // Select Database
    $pdo->exec("USE $dbname");

    // Create Admins Table
    // PLAIN TEXT password storage as requested
    $sqlAdmins = "CREATE TABLE IF NOT EXISTS admins (
        id INT AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sqlAdmins);
    echo "Table 'admins' checked/created.<br>\n";

    // Create Products Table
    $sqlProducts = "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        price DOUBLE NOT NULL,
        image VARCHAR(255) NOT NULL
    )";
    $pdo->exec($sqlProducts);
    echo "Table 'products' checked/created.<br>\n";

    // Insert Default Admin (blkadmin / 123456)
    $username = 'blkadmin';
    $password = '123456';

    // Check if admin exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admins WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        // Update password if exists to ensure it matches requirement
        $stmt = $pdo->prepare("UPDATE admins SET password = ? WHERE username = ?");
        $stmt->execute([$password, $username]);
        echo "Admin user '$username' updated (password set to $password).<br>\n";
    } else {
        // Insert if not exists
        $stmt = $pdo->prepare("INSERT INTO admins (username, password) VALUES (?, ?)");
        $stmt->execute([$username, $password]);
        echo "Admin user '$username' created (password: $password).<br>\n";
    }

    // Insert Sample Products if empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM products");
    if ($stmt->fetchColumn() == 0) {
        $products = [
            ['Samsung Galaxy S24', 900, 'samsung.jpg'],
            ['iPhone 15 Pro', 1200, 'apple.jpg'],
            ['Oppo Find X5', 600, 'oppo.jpg']
        ];

        $sqlProd = "INSERT INTO products (name, price, image) VALUES (?, ?, ?)";
        $stmtProd = $pdo->prepare($sqlProd);

        foreach ($products as $p) {
            $stmtProd->execute($p);
        }
        echo "Sample products inserted.<br>\n";
    }

    echo "Database setup completed successfully!";

} catch (PDOException $e) {
    die("DB ERROR: " . $e->getMessage());
}
?>