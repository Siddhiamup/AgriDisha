<?php 
$buyer_id = (int)$_SESSION['id']; 

// Process add to cart action
if (isset($_GET['action']) && $_GET['action'] == 'add_to_cart' && isset($_GET['id'])) {
    $product_id = (int)$_GET['id'];
    
    // Check if product already in cart
    $check_cart = $conn->prepare("SELECT id FROM cart WHERE product_id = ? AND buyer_id = ?");
    $check_cart->bind_param("ii", $product_id, $buyer_id);
    $check_cart->execute();
    $cart_result = $check_cart->get_result();
    
    if ($cart_result->num_rows > 0) {
        $_SESSION['message'] = "This product is already in your cart!";
        $_SESSION['message_type'] = "warning";
    } else {
        // Add to cart
        $add_cart = $conn->prepare("INSERT INTO cart (buyer_id, product_id, quantity) VALUES (?, ?, 1)");
        $add_cart->bind_param("ii", $buyer_id, $product_id);
        
        if ($add_cart->execute()) {
            // Remove from wishlist
            $remove_wishlist = $conn->prepare("DELETE FROM wishlist WHERE buyer_id = ? AND product_id = ?");
            $remove_wishlist->bind_param("ii", $buyer_id, $product_id);
            $remove_wishlist->execute();
            
            $_SESSION['message'] = "Product has been moved to cart successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Failed to add product to cart. Please try again.";
            $_SESSION['message_type'] = "danger";
        }
    }
    
    // Redirect to the same tab with success message
    header("Location: bdemo.php?tab=wishlist&message=" . urlencode($_SESSION['message']) . "&message_type=" . urlencode($_SESSION['message_type']));
    exit();
}

// Process remove from wishlist
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id'])) {
    $wishlist_id = (int)$_GET['id'];
    
    $remove_query = $conn->prepare("DELETE FROM wishlist WHERE id = ? AND buyer_id = ?");
    $remove_query->bind_param("ii", $wishlist_id, $buyer_id);
    
    if ($remove_query->execute()) {
        $_SESSION['message'] = "Item removed from wishlist!";
        $_SESSION['message_type'] = "success";
    } else {
        $_SESSION['message'] = "Failed to remove item. Please try again.";
        $_SESSION['message_type'] = "danger";
    }
    
    // Redirect to the same tab with success message
    header("Location: bdemo.php?tab=wishlist&message=" . urlencode($_SESSION['message']) . "&message_type=" . urlencode($_SESSION['message_type']));
    exit();
}

// Fetch wishlist items
$wishlist_query = $conn->prepare("SELECT w.id, w.product_id, p.name, p.image_url, p.price FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.buyer_id = ?");
$wishlist_query->bind_param("i", $buyer_id);
$wishlist_query->execute();
$wishlist_result = $wishlist_query->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist</title>
    <style>
        
.btn-move-cart, .btn-remove {
            display: inline-block;
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            margin-right: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        
        .btn-move-cart {
            background-color: #4CAF50;
            color: white;
            border: none;
        }
        
        .btn-move-cart:hover {
            background-color: #45a049;
        }
        
        .btn-remove {
            background-color: #f44336;
            color: white;
            border: none;
        }
        
        .btn-remove:hover {
            background-color: #d32f2f;
        }
        
        .empty-wishlist {
            text-align: center;
            padding: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="table-container">
        <h2>My Wishlist</h2>
        
        <?php 
        if (isset($_GET['message'])): 
        ?>
            <div class="alert alert-<?= htmlspecialchars($_GET['message_type']) ?>">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($wishlist_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($wishlist = $wishlist_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($wishlist['name']) ?></td>
                            <td><img src="../AMSDemo/<?= htmlspecialchars($wishlist['image_url']) ?>" width="50" height="50"></td>
                            <td>₹<?= number_format($wishlist['price'], 2) ?></td>
                            <td>
                                <a href="bdemo.php?tab=wishlist&action=add_to_cart&id=<?= $wishlist['product_id'] ?>" class="btn-move-cart">Move to Cart</a>
                                <a href="bdemo.php?tab=wishlist&action=remove&id=<?= $wishlist['id'] ?>" class="btn-remove">Remove</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-wishlist">
                <p>Your wishlist is empty. Browse products to add items to your wishlist.</p>
                <a href="bdemo.php?tab=browse_products" class="btn-move-cart">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>












