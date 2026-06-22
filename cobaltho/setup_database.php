<?php
require_once "includes/db.php";

$queries = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(160) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('customer','admin') NOT NULL DEFAULT 'customer',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(160) NOT NULL,
        description TEXT NULL,
        price INT NOT NULL DEFAULT 0,
        stock INT NOT NULL DEFAULT 0,
        image VARCHAR(255) NOT NULL DEFAULT 'img/producto1.jpg',
        active TINYINT(1) NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        total INT NOT NULL DEFAULT 0,
        status VARCHAR(40) NOT NULL DEFAULT 'pendiente',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    "CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT NOT NULL,
        product_name VARCHAR(160) NOT NULL,
        price INT NOT NULL,
        quantity INT NOT NULL,
        subtotal INT NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id),
        FOREIGN KEY (product_id) REFERENCES products(id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
];

foreach ($queries as $query) {
    if (!$conn->query($query)) {
        die("Error creando tablas: " . $conn->error);
    }
}

$adminEmail = "admin@cobaltho.com";
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $adminEmail);
$stmt->execute();
$adminExists = $stmt->get_result()->num_rows > 0;

if (!$adminExists) {
    $adminPassword = password_hash("admin123", PASSWORD_DEFAULT);
    $adminName = "Admin COBALTHO";
    $role = "admin";

    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $adminName, $adminEmail, $adminPassword, $role);
    $stmt->execute();
}

$result = $conn->query("SELECT COUNT(*) AS total FROM products");
$productCount = (int) $result->fetch_assoc()['total'];

if ($productCount === 0) {
    $products = [
        ["Collar elegante dorado", "Collar moderno para uso diario.", 12000, 10, "img/producto1.jpg"],
        ["Pulsera minimalista", "Pulsera delicada de estilo minimalista.", 10000, 15, "img/producto2.jpg"],
        ["Set de accesorios regalo", "Set ideal para regalar.", 18000, 8, "img/producto3.jpg"]
    ];

    $stmt = $conn->prepare("INSERT INTO products (name, description, price, stock, image) VALUES (?, ?, ?, ?, ?)");

    foreach ($products as $product) {
        $stmt->bind_param("ssiis", $product[0], $product[1], $product[2], $product[3], $product[4]);
        $stmt->execute();
    }
}

echo "Base de datos lista. Admin: admin@cobaltho.com / admin123";
?>
