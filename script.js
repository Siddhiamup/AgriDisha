// document.getElementById('learnMoreBtn').addEventListener('click', function() {
//     alert('Thank you for your interest! More information will be available soon.');
// });


// Wait for the DOM to fully load
document.addEventListener("DOMContentLoaded", function () {
    console.log("Script loaded!");

    // Smooth scrolling for navigation links
    const navLinks = document.querySelectorAll(".navbar a");
    navLinks.forEach(link => {
        link.addEventListener("click", function (e) {
            e.preventDefault();
            const targetId = this.getAttribute("href").substring(1); // Remove the '#' from href
            const targetElement = document.getElementById(targetId);
            if (targetElement) {
                window.scrollTo({
                    top: targetElement.offsetTop - 70, // Adjust for header height
                    behavior: "smooth"
                });
            }
        });
    });

    // Add interactivity for the "Register Now" button
    const registerButton = document.querySelector("footer button");
    registerButton.addEventListener("click", function () {
        alert("Thank you for showing interest! Redirecting to the registration page...");
        window.location.href = "register.html"; // Change to your registration page URL
    });

    // Basic form validation (if applicable)
    const form = document.querySelector("form"); // Assuming you have a form on your page
    if (form) {
        form.addEventListener("submit", function (e) {
            const name = document.querySelector("#name").value.trim();
            const email = document.querySelector("#email").value.trim();
            if (!name || !email) {
                e.preventDefault();
                alert("Please fill in all required fields.");
            } else if (!validateEmail(email)) {
                e.preventDefault();
                alert("Please enter a valid email address.");
            }
        });
    }

    // Email validation function
    function validateEmail(email) {
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(email);
    }

    // Toggle mobile navigation menu
    const menuToggle = document.querySelector(".menu-toggle");
    const navbar = document.querySelector(".navbar ul");
    if (menuToggle && navbar) {
        menuToggle.addEventListener("click", function () {
            navbar.classList.toggle("open");
        });
    }
});
