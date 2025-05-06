// document.getElementById("farmerRegistrationForm").addEventListener("submit", function(event) {
//     const mobile = document.getElementById("mobile").value;
//     const email = document.getElementById("email").value;

//     // Mobile number validation (10-digit numeric only)
//     const mobileRegex = /^[0-9]{10}$/;
//     if (!mobileRegex.test(mobile)) {
//         alert("Please enter a valid 10-digit mobile number.");
//         event.preventDefault(); // Prevent form submission
//         return;
//     }

//     // Email validation
//     const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     if (!emailRegex.test(email)) {
//         alert("Please enter a valid email address.");
//         event.preventDefault(); // Prevent form submission
//         return;
//     }

//     alert("Registration successful!");
// });


// document.getElementById('registrationForm').addEventListener('submit', function(event) {
//     event.preventDefault(); // Prevent form submission

//     const name = document.getElementById('name').value;
//     const mobile = document.getElementById('mobile').value;
//     const email = document.getElementById('email').value;
//     const password = document.getElementById('password').value;
//     const confirmPassword = document.getElementById('confirmPassword').value;
//     const errorMessages = document.getElementById('errorMessages');
//     errorMessages.innerHTML = ''; // Clear previous error messages

//     let valid = true;

//     // Validate mobile number
//     const mobilePattern = /^[0-9]{10}$/;
//     if (!mobilePattern.test(mobile)) {
//         errorMessages.innerHTML += '<p>Invalid mobile number. It should be 10 digits.</p>';
//         valid = false;
//     }

//     // Validate email address
//     const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     if (!emailPattern.test(email)) {
//         errorMessages.innerHTML += '<p>Invalid email address.</p>';
//         valid = false;
//     }

//     // Validate password match
//     if (password !== confirmPassword) {
//         errorMessages.innerHTML += '<p>Passwords do not match.</p>';
//         valid = false;
//     }

//     // If valid, submit the form
//     if (valid) {
//         this.submit();
//     }
// });

// document.getElementById('registrationForm').addEventListener('submit', function(event) {
//     const mobile = document.getElementById('mobile').value;
//     const email = document.getElementById('email').value;
//     const password = document.getElementById('password').value;
//     const confirmPassword = document.getElementById('confirm_password').value;

//     // Validate mobile number
//     const mobilePattern = /^[0-9]{10}$/;
//     if (!mobilePattern.test(mobile)) {
//         alert('Please enter a valid 10-digit mobile number.');
//         event.preventDefault();
//         return;
//     }

//     // Validate email
//     const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
//     if (!emailPattern.test(email)) {
//         alert('Please enter a valid email address.');
//         event.preventDefault();
//         return;
//     }

//     // Validate password match
//     if (password !== confirmPassword) {
//         alert('Passwords do not match.');
//         event.preventDefault();
//         return;
//     }
// });


// FINAL
function validateForm() {
    const name = document.getElementById("name").value;
    const mobile = document.getElementById("mobile").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;

    if (name === "") {
        alert("Please enter your name");
        return false;
    }

    if (mobile.length !== 10 || isNaN(mobile)) {
        alert("Please enter a valid 10-digit mobile number");
        return false;
    }

    // Email validation regex (adjust as needed)
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        alert("Please enter a valid email address");
        return false;
    }

    if (password !== confirmPassword) {
        alert("Passwords do not match");
        return false;
    }

    return true;
}