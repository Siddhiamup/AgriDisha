<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AgriDisha - Agricultural Marketplace</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E7D32;
            /* Deep green */
            --secondary-color: #66BB6A;
            /* Fresh lime green */
            --text-dark: #2D3A1E;
            /* Deep olive */
            --text-light: #ffffff;
            --shadow-light: rgba(0, 0, 0, 0.08);
            --shadow-dark: rgba(0, 0, 0, 0.2);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            color: var(--text-dark);
            background-color: #ffffff;
            line-height: 1.6;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* General Section Styling */
        section {
            padding: 2rem 0;
            text-align: center;
            background: #ffffff;
            /* Ensures all sections have a white background */
        }

        /* About Section */
        .about-section {
            padding: 2rem 0;
            background: #ffffff;
            /* Removed any previous background colors */
            box-shadow: none;
            border-bottom: 2px solid rgba(0, 0, 0, 0.05);
            /* Subtle separation */
        }

        .about-section h1 {
            font-size: 2rem;
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 0.75rem;
        }

        .about-section p {
            font-size: 1.1rem;
            max-width: 800px;
            margin: 0 auto;
            color: var(--text-dark);
            line-height: 1.5;
        }

        /* Offerings & Other Sections */
        .offerings,
        .why-choose-section,
        .faq-section {
            padding: 2rem 0;
            background: #ffffff;
            /* Ensures all sections stay white */
        }

        h2 {
            font-size: 1.7rem;
            color: var(--primary-color);
            font-weight: 700;
            position: relative;
            display: inline-block;
            margin-bottom: 1rem;
        }

        h2::after {
            content: '';
            width: 60px;
            height: 3px;
            background: var(--secondary-color);
            position: absolute;
            bottom: -5px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        /* Offerings Section */
        .offerings {
            padding: 2rem 0;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .offering-card {
            background: #ffffff;
            padding: 1rem;
            border-radius: 8px;
            text-align: center;
            transition: 0.3s ease;
            box-shadow: 0 2px 6px var(--shadow-light);
            border: 1px solid var(--secondary-color);
        }

        .offering-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px var(--shadow-dark);
        }

        .offering-card strong {
            font-size: 1.1rem;
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
        }

        /* Why Choose Us Section */
        .why-choose-section {
            padding: 2rem 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1rem;
            margin-top: 1rem;
        }

        .feature-card {
            padding: 1rem;
            text-align: center;
            background: #ffffff;
            border-radius: 8px;
            transition: 0.3s ease;
            box-shadow: 0 2px 6px var(--shadow-light);
            border: 1px solid var(--primary-color);
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 3px 10px var(--shadow-dark);
        }

        .feature-card i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 0.75rem;
        }

        .feature-card h4 {
            color: var(--primary-color);
            font-size: 1.1rem;
            margin: 0.5rem 0;
        }

        /* FAQ Section */
        .faq-section {
            padding: 2rem 0;
        }

        .faq-title {
            font-size: 1.7rem;
            color: var(--primary-color);
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            h1 {
                font-size: 1.8rem;
            }

            h2 {
                font-size: 1.6rem;
            }

            p {
                font-size: 0.95rem;
            }

            .container {
                padding: 0 1rem;
            }

            .info-grid,
            .features-grid {
                grid-template-columns: 1fr;
                gap: 0.75rem;
            }

            .offering-card,
            .feature-card {
                padding: 0.85rem;
            }
        }
    </style>
</head>

<body>
    <section class="about-section fade-in">
        <div class="container">
        <b>
            <h1>Welcome to AgriDisha</h1>
        </b>
        <p>AgriDisha is a cutting-edge platform that empowers farmers by providing them direct access to buyers. With
            advanced tools, we simplify the process of buying and selling agricultural products. Currently, we cater to
            thousands of farmers across eight product categories.</p>
        </div>
    </section>
    <div class="container">
        <section class="offerings fade-in">
            <h2>What We Offer</h2>
            <div class="info-grid">
                <div class="offering-card">
                    <strong>For Farmers</strong>
                    <p>Sell your crops directly, connect with buyers, and get fair prices.</p>
                </div>
                <div class="offering-card">
                    <strong>For Buyers</strong>
                    <p>Find local farmers, compare prices, and buy fresh produce with ease.</p>
                </div>
                <!-- <div class="offering-card">
                    <strong>For Sellers</strong>
                    <p>List agricultural products, reach more customers, and ensure smooth transactions.</p>
                </div> -->
            </div>
        </section>
        <?php include 'slider.php'; ?>

        <section class="why-choose-section fade-in">
            <center>
                <h2>Why Choose Us?</h2>
            </center>
            <div class="features-grid">
                <div class="feature-card">
                    <i class="bi bi-people-fill"></i>
                    <h4>Connect Farmers & Buyers</h4>
                    <p>Bridging the gap between farmers and buyers for better transactions.</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-bar-chart-line-fill"></i>
                    <h4>Market Prices</h4>
                    <p>Get real-time updates on market trends and crop prices.</p>
                </div>
                <div class="feature-card">
                    <i class="bi bi-globe2"></i>
                    <h4>Nationwide Reach</h4>
                    <p>Connecting agricultural communities across India.</p>
                </div>
            </div>
        </section>
        <section class="faq-section fade-in">
    <h2>Frequently Asked Questions</h2>
    <style>
        /* FAQ-specific styles */
        .faq-section {
            padding: 2rem 0;
        }

        .faq-section .faq {
            text-align: left;
            margin-bottom: 1rem;
            border-radius: 8px;
            overflow: hidden;
        }

        .faq-section .faq h3 {
            margin: 0;
            padding: 1rem;
            font-size: 1.1rem;
            background: #f9f9f9;
            border: 1px solid #ddd;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: var(--text-dark);
        }

        .faq-section .faq h3:hover {
            background: #e8f5e9;
            color: var(--primary-color);
        }

        .faq-section .faq h3::after {
            content: '+';
            font-weight: bold;
        }

        .faq-section .faq.active h3::after {
            content: '-';
        }

        .faq-section .faq p {
            display: none;
            margin: 0;
            padding: 1rem;
            background: #fff;
            border: 1px solid #ddd;
            border-top: none;
        }

        .faq-section .faq.active p {
            display: block;
        }

        .faq-section .extra-faq {
            display: none;
        }

        .faq-section .show-more {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            margin-top: 1rem;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .faq-section .show-more:hover {
            background-color: var(--secondary-color);
        }
    </style>

    <div class="faq">
        <h3>What is AgriDisha?</h3>
        <p>AgriDisha is an agricultural management system designed to streamline the wholesale process of crops and spices, connecting farmers, buyers, and stakeholders efficiently.</p>
    </div>
    <div class="faq">
        <h3>Who can use AgriDisha?</h3>
        <p>AgriDisha is designed for farmers, wholesalers, retailers, and anyone involved in the agricultural supply chain.</p>
    </div>
    <div class="faq">
        <h3>What features does AgriDisha offer?</h3>
        <p>AgriDisha offers features such as inventory management, pricing updates, order tracking, and seamless communication between stakeholders.</p>
    </div>

    <div class="extra-faq">
        <div class="faq">
            <h3>Does AgriDisha offer organic products?</h3>
            <p>Yes, we provide a wide selection of organic crops and spices certified by authorized agencies.</p>
        </div>
        <div class="faq">
            <h3>Can I schedule a delivery for a specific date?</h3>
            <p>Yes, you can choose a preferred delivery date while placing your order, subject to availability.</p>
        </div>
        <div class="faq">
            <h3>What regions does AgriDisha currently support?</h3>
            <p>AgriDisha currently operates in multiple states across the country. Check our website for specific regions covered.</p>
        </div>
        <div class="faq">
            <h3>How can farmers list their crops on AgriDisha?</h3>
            <p>Farmers can list their crops by creating an account, providing necessary crop details, and uploading images.</p>
        </div>
        <div class="faq">
            <h3>Does AgriDisha provide crop pricing trends?</h3>
            <p>Yes, AgriDisha offers detailed pricing trends and analytics to help stakeholders make informed decisions.</p>
        </div>
    </div>

    <button class="show-more" id="show-more-btn">Show More</button>

    <script>
        // Handle FAQ toggles
        document.querySelectorAll('.faq-section .faq h3').forEach(item => {
            item.addEventListener('click', () => {
                const parent = item.parentElement;
                // Close other open FAQs
                document.querySelectorAll('.faq-section .faq').forEach(faq => {
                    if (faq !== parent && faq.classList.contains('active')) {
                        faq.classList.remove('active');
                    }
                });
                parent.classList.toggle('active');
            });
        });

        // Handle Show More/Less
        document.getElementById('show-more-btn').addEventListener('click', function() {
            const extraFaqs = document.querySelector('.faq-section .extra-faq');
            const currentDisplay = window.getComputedStyle(extraFaqs).display;
            extraFaqs.style.display = currentDisplay === 'none' ? 'block' : 'none';
            this.textContent = currentDisplay === 'none' ? 'Show Less' : 'Show More';
        });
    </script>
</section>
    </div>
</body>

</html>