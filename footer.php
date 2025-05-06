    <!-- Footer -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css"
        rel="stylesheet">

   <style>
    footer {  
    background-color: #343a40;
    color: white;
    padding: 2.5px 0; /* Halved from 5px */
    height: 10%; /* Halved from 75% */
    text-align: center;
}
footer h5 {
    font-weight: bold;
    margin-bottom: 2px; /* Original size */
    font-size: 1rem; /* Original size */
}
footer a {
    color: white;
    text-decoration: none;
    transition: color 0.3s ease;
    font-size: 0.875rem; /* Original size */
}
footer a:hover {
    color: #d4edda;
}
footer .input-group {
    display: flex;
    justify-content: center;
    max-width: 150px; /* Halved from 300px */
    margin: 2px auto; /* Original size */
}
footer .input-group input {
    flex: 1;
    padding: 4px 8px; /* Original size */
    border-radius: 5px 0 0 5px; /* Original size */
    border: 1px solid #ced4da; /* Original size */
}
footer .input-group button {
    padding: 4px 12px; /* Original size */
    border-radius: 0 5px 5px 0; /* Original size */
    background-color: #28a745;
    color: white;
    border: none;
    font-size: 0.875rem; /* Original size */
}
footer .input-group button:hover {
    background-color: #218838;
}
footer .footer-links {
    display: flex;
    justify-content: center;
    gap: 2px; /* Original size */
    margin-top: 5px; /* Halved from 10px */
    font-size: 0.875rem; /* Original size */
}
footer p {
    
        font-size: 14px; /* Adjust the size as needed */
        font-weight: bold; /* Optional: Makes text bold */
        line-height: 1.2; /* Improves readability */
    }
    
     .img {
        display: block; 
         margin: 20px auto;
        border-radius: 50%;  /* Makes the logo circular */
         object-fit: cover; /* Ensures image covers the area without distortion */
        background-color: white;  /*In case of transparent logos */
        padding: 8px;  /* Creates some space around the logo */
    }
@media (max-width: 768px) {
    footer .footer-links {
        flex-direction: column;
        gap: 8px; /* Original size */
    }
    
    footer .input-group {
        flex-direction: column;
    }
    
    footer .input-group input,
    footer .input-group button {
        border-radius: 5px; /* Original size */
        margin: 4px 0; /* Original size */
    }
}
   </style>
    <footer class="bg-dark text-white pt-5">
        <div class="container">
            <div class="row">
                <!-- About Section -->
                <div class="col-md-4 mb-4">
                   <img src="ASSETS/IMAGES/logo1.jpg" alt="AgriDisha Logo" height="120" width="120"class="img">

                    <h5 class="fw-bold text-uppercase">About Us</h5>
                    <p>
                        AgriDisha connects farmers and buyers to create a transparent agricultural market. Together,
                        we aim to revolutionize farming practices and empower the farming community.
                    </p>
                </div>
                <!-- Quick Links -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-uppercase">Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="index.php?tab=home" class="text-white text-decoration-none">Home</a></li>
                        <li><a href="index.php?tab=buy" class="text-white text-decoration-none">Buy</a></li>
                        <li><a href="index.php?tab=sell" class="text-white text-decoration-none">Sell</a></li>
                        <li><a href="index.php?tab=blogs" class="text-white text-decoration-none">Blogs</a></li>
                        <li><a href="index.php?tab=contact" class="text-white text-decoration-none">Contact Us</a></li>
                    </ul>
                </div>
                <!-- Newsletter -->
                <div class="col-md-4 mb-4">
                    <h5 class="fw-bold text-uppercase">Subscribe to Newsletter</h5>
                    <p>Get updates and latest offers directly to your inbox!</p>
                    <form>
                        <div class="input-group">
                            <input type="email" class="form-control" placeholder="Your Email" required>
                            <button class="btn btn-success" type="submit">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
            <hr class="border-light">
            <div class="row">
                <!-- Social Media Links -->
                <div class="col-md-6 mb-3 mb-md-0 text-center text-md-start">
                    <span class="me-3">Follow Us:</span>
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-linkedin"></i></a>
                </div>
                <!-- Copyright -->
                <div class="col-md-6 text-center text-md-end">
                    <p class="mb-0 small">
                        &copy; 2025 AgriDisha. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>
