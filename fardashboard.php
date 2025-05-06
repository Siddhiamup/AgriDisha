<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Farmer's Market Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2ecc71;
            --secondary-color: #27ae60;
            --background-color: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: var(--background-color);
        }

        .navbar {
            background: var(--primary-color);
            padding: 1rem 2rem;
            box-shadow: var(--card-shadow);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .navbar-brand {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
            text-decoration: none;
        }

        .main-container {
            margin-top: 80px;
            padding: 2rem;
        }

        .search-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
        }

        .filters {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .filter-input {
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            width: 100%;
            transition: border-color 0.3s;
        }

        .filter-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }

        .search-btn {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0.8rem 1.5rem;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .search-btn:hover {
            background: var(--secondary-color);
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-details {
            padding: 1.5rem;
        }

        .product-title {
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .product-price {
            color: var(--primary-color);
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }

        .seller-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .seller-rating {
            color: #f1c40f;
            margin-left: 0.5rem;
        }

        .cart-container {
            position: fixed;
            right: 2rem;
            top: 80px;
            width: 300px;
            background: white;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            padding: 1.5rem;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.8rem;
            border-bottom: 1px solid #eee;
        }

        .cart-total {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 2px solid #eee;
            text-align: right;
        }

        .checkout-btn {
            width: 100%;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .checkout-btn:hover {
            background: var(--secondary-color);
        }

        .badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            .cart-container {
                position: fixed;
                bottom: 0;
                right: 0;
                width: 100%;
                top: auto;
                border-radius: 10px 10px 0 0;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="#" class="navbar-brand">
            <i class="fas fa-leaf"></i> Farmer's Market
        </a>
    </nav>

    <div class="main-container">
        <div class="search-container">
            <h2>Search Products</h2>
            <div class="filters">
                <select class="filter-input" id="categoryFilter">
                    <option value="">All Categories</option>
                    <option value="vegetables">Vegetables</option>
                    <option value="fruits">Fruits</option>
                    <option value="grains">Grains</option>
                    <option value="dairy">Dairy</option>
                </select>
                <input type="number" class="filter-input" placeholder="Min Price" id="minPrice">
                <input type="number" class="filter-input" placeholder="Max Price" id="maxPrice">
                <input type="text" class="filter-input" placeholder="Location" id="location">
                <button class="search-btn" onclick="searchProducts()">
                    <i class="fas fa-search"></i> Search
                </button>
            </div>
        </div>

        <div class="products-grid" id="productsContainer">
            <!-- Products will be dynamically added here -->
        </div>

        <div class="cart-container">
            <h3><i class="fas fa-shopping-cart"></i> Your Cart</h3>
            <div id="cartItems">
                <!-- Cart items will be dynamically added here -->
            </div>
            <div class="cart-total">
                <h4>Total: $<span id="cartTotal">0.00</span></h4>
            </div>
            <button class="checkout-btn" onclick="checkout()">
                <i class="fas fa-shopping-bag"></i> Checkout
            </button>
        </div>
    </div>

    <script>
        // Sample product data
        const products = [
            {
                id: 1,
                name: "Fresh Organic Tomatoes",
                category: "vegetables",
                price: 2.99,
                image: "/api/placeholder/280/200",
                location: "Green Valley Farm",
                seller: {
                    name: "John Smith",
                    rating: 4.8
                }
            },
            {
                id: 2,
                name: "Sweet Corn",
                category: "vegetables",
                price: 1.99,
                image: "/api/placeholder/280/200",
                location: "Sunny Fields",
                seller: {
                    name: "Mary Johnson",
                    rating: 4.5
                }
            },
            {
                id: 3,
                name: "Fresh Strawberries",
                category: "fruits",
                price: 4.99,
                image: "/api/placeholder/280/200",
                location: "Berry Good Farm",
                seller: {
                    name: "David Wilson",
                    rating: 4.9
                }
            }
        ];

        let cart = [];

        // Function to render products
        function renderProducts(productsToShow = products) {
            const container = document.getElementById('productsContainer');
            container.innerHTML = '';

            productsToShow.forEach(product => {
                const card = document.createElement('div');
                card.className = 'product-card';
                card.innerHTML = `
                    <img src="${product.image}" alt="${product.name}" class="product-image">
                    <div class="product-details">
                        <h3 class="product-title">${product.name}</h3>
                        <p class="product-price">$${product.price.toFixed(2)}</p>
                        <div class="seller-info">
                            <i class="fas fa-user"></i>
                            <span>${product.seller.name}</span>
                            <span class="seller-rating">
                                ${product.seller.rating} <i class="fas fa-star"></i>
                            </span>
                        </div>
                        <p><i class="fas fa-map-marker-alt"></i> ${product.location}</p>
                        <button class="search-btn" onclick="addToCart(${product.id})">
                            <i class="fas fa-cart-plus"></i> Add to Cart
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Function to search products
        function searchProducts() {
            const category = document.getElementById('categoryFilter').value;
            const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
            const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
            const location = document.getElementById('location').value.toLowerCase();

            const filteredProducts = products.filter(product => {
                return (!category || product.category === category) &&
                       product.price >= minPrice &&
                       product.price <= maxPrice &&
                       (!location || product.location.toLowerCase().includes(location));
            });

            renderProducts(filteredProducts);
        }

        // Function to add product to cart
        function addToCart(productId) {
            const product = products.find(p => p.id === productId);
            if (product) {
                const existingItem = cart.find(item => item.id === productId);
                if (existingItem) {
                    existingItem.quantity += 1;
                } else {
                    cart.push({ ...product, quantity: 1 });
                }
                updateCart();
            }
        }

        // Function to update cart display
        function updateCart() {
            const cartItems = document.getElementById('cartItems');
            const cartTotal = document.getElementById('cartTotal');
            cartItems.innerHTML = '';

            let total = 0;
            cart.forEach(item => {
                const itemElement = document.createElement('div');
                itemElement.className = 'cart-item';
                itemElement.innerHTML = `
                    <div>
                        <h4>${item.name}</h4>
                        <p>$${item.price.toFixed(2)} × ${item.quantity}</p>
                    </div>
                    <button class="search-btn" onclick="removeFromCart(${item.id})">
                        <i class="fas fa-trash"></i>
                    </button>
                `;
                cartItems.appendChild(itemElement);
                total += item.price * item.quantity;
            });

            cartTotal.textContent = total.toFixed(2);
        }

        // Function to remove item from cart
        function removeFromCart(productId) {
            cart = cart.filter(item => item.id !== productId);
            updateCart();
        }

        // Function to handle checkout
        function checkout() {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }
            
            // Here you would typically integrate with a payment system
            alert('Proceeding to checkout...');
            // Clear cart after successful checkout
            cart = [];
            updateCart();
        }

        // Initial render
        renderProducts();
    </script>
</body>
</html>