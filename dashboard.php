<?php
// Simple authentication
session_start();

$valid_username = 'sanjaykhunteta';
$valid_password = 'adminsanjaykhunteta@123';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION['authenticated'] = true;
        $_SESSION['username'] = $username;
    } else {
        header('Location: login.html');
        exit;
    }
} elseif (!isset($_SESSION['authenticated'])) {
    header('Location: login.html');
    exit;
}

// Logout functionality
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: login.html');
    exit;
}

// Read products data
$products_file = '../products.json';
$products = json_decode(file_get_contents($products_file), true);

// Handle product updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_products'])) {
    $updated_products = [];
    
    foreach ($_POST['products'] as $id => $product_data) {
        $updated_products[] = [
            'id' => intval($id),
            'name' => $product_data['name'],
            'price' => intval($product_data['price']),
            'minQuantity' => intval($product_data['minQuantity']),
            'maxQuantity' => intval($product_data['maxQuantity']),
            'available' => isset($product_data['available']) ? true : false,
            'image' => $product_data['image']
        ];
    }
    
    file_put_contents($products_file, json_encode($updated_products, JSON_PRETTY_PRINT));
    $products = $updated_products;
    $success_message = "Products updated successfully!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Khunteta Construction</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 80px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        
        .admin-header h2 {
            color: #2c3e50;
        }
        
        .logout-btn {
            padding: 8px 15px;
            background-color: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.3s;
        }
        
        .logout-btn:hover {
            background-color: #c0392b;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .product-form {
            margin-bottom: 40px;
        }
        
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .product-card {
            background: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #555;
        }
        
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-group .checkbox {
            width: auto;
            margin-right: 10px;
        }
        
        .form-actions {
            text-align: right;
            margin-top: 30px;
        }
        
        .update-btn {
            padding: 10px 20px;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }
        
        .update-btn:hover {
            background-color: #1a252f;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h2>Admin Dashboard</h2>
            <a href="?logout=1" class="logout-btn">Logout</a>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="success-message"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <form class="product-form" method="post" action="">
            <div class="product-grid">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <input type="hidden" name="products[<?php echo $product['id']; ?>][id]" value="<?php echo $product['id']; ?>">
                        
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="products[<?php echo $product['id']; ?>][name]" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Price (per ton)</label>
                            <input type="number" name="products[<?php echo $product['id']; ?>][price]" value="<?php echo $product['price']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Min. Quantity (tons)</label>
                            <input type="number" name="products[<?php echo $product['id']; ?>][minQuantity]" value="<?php echo $product['minQuantity']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Max. Quantity (tons)</label>
                            <input type="number" name="products[<?php echo $product['id']; ?>][maxQuantity]" value="<?php echo $product['maxQuantity']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>
                                <input type="checkbox" class="checkbox" name="products[<?php echo $product['id']; ?>][available]" <?php echo $product['available'] ? 'checked' : ''; ?>>
                                Available
                            </label>
                        </div>
                        
                        <div class="form-group">
                            <label>Image URL</label>
                            <input type="text" name="products[<?php echo $product['id']; ?>][image]" value="<?php echo htmlspecialchars($product['image']); ?>">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="form-actions">
                <button type="submit" name="update_products" class="update-btn">Update Products</button>
            </div>
        </form>
    </div>
</body>
</html>