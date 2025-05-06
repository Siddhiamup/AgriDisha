<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="ASSETS/CSS/contact.css"> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Styling for Contact Page */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f9fa;
    color: #333;
}

header {
    border-bottom: 2px solid #4CAF50;
}

h2, h4 {
    font-weight: bold;
}
.container {
    max-width: 850px;
    max-height: 1000px;
}

a {
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
    color: #4CAF50;
}

.bg-light {
    background-color: #f9f9f9 !important;
}

#contactForm .form-control:focus {
    border-color: #4CAF50;
    box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
}

.btn-success:hover {
    background-color: #388E3C;
}
@media (max-width: 768px) {
    .container {
        padding: -0.7px;
    }
}
    </style>
</head>
<body>
    <header class="bg-light py-3">
        <div class="container text-center">
            <h2 class="text-success">Get In Touch</h2>
        </div>
    </header>

    <main class="container my-4">
        <div class="row g-1 align-items-center">
            <!-- Contact Info -->
            <div class="col-md-6">
                <h4 class="text-success">Website:</h4>
                <p>AgriDisha</p>

                <h4 class="text-success">Important Emails:</h4>
                <ul>
                    <li><strong>Buyer's Inquiry:</strong> <a href="mailto:buyer@agridisha.com">buyer@agridisha.com</a></li>
                    <li><strong>Farmer's Inquiry:</strong> <a href="mailto:farmer@agridisha.com">farmer@agridisha.com</a></li>
                    <li><strong>General Inquiry:</strong> <a href="mailto:contact@agridisha.com">contact@agridisha.com</a></li>
                </ul>

                <h4 class="text-success">Connect With Us:</h4>
                <p>Stay tuned on our social media platforms to know about new features!</p>
                <div>
                    <a href="#" class="text-dark me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-dark me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-dark me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-dark me-3"><i class="bi bi-youtube"></i></a>
                    <a href="#" class="text-dark"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-md-6">
                <form id="contactForm" action="ASSETS/DATABASE/contact_2.php" method="POST" class="bg-light p-4 rounded shadow">
                    <div class="mb-1">
                        <label for="name" class="form-label">Name *</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-1">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-1">
                        <label for="mobile" class="form-label">Mobile *</label>
                        <input type="tel" class="form-control" id="mobile" name="mobile" pattern="[0-9]{10}" required>
                    </div>
                    <div class="mb-1">
                        <label for="comment" class="form-label">Comment *</label>
                        <textarea class="form-control" id="comment" name="comment" rows="2" required></textarea>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Submit</button>
                        <button type="reset" class="btn btn-secondary">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</body>
<script src="ASSETS/JS/contact.js"></script>
</html>
