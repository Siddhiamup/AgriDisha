<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    <title>Registration Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            padding: 20px;
        }
        
        /* Background image with blur effect */
        body::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('ASSETS/IMAGES/f10.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            filter: blur(5px); /* Add blur to background image */
            z-index: -1;
        }

        .container {
            max-width: 500px;
            width: 100%;
            background: rgba(5, 31, 9, 0.7); /* Dark semi-transparent background */
            padding: 35px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(5px); /* Adds a subtle blur effect */
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            z-index: 1;
        }

        .title {
            font-size: 32px;
            font-weight: 600;
            color: white;
            position: relative;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .title::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -10px;
            height: 3px;
            width: 60px;
            background: #4CAF50;
            border-radius: 3px;
        }

        form .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select {
            height: 50px;
            width: 100%;
            outline: none;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.9);
            padding: 0 18px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 15px rgba(76, 175, 80, 0.5);
        }

        .error {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 8px;
            display: none;
            background: rgba(0, 0, 0, 0.4);
            padding: 4px 10px;
            border-radius: 4px;
        }

        .success-message {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
        }

        .btn {
            height: 55px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .btn:active {
            transform: translateY(1px);
        }

        #otp-form {
            display: none;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        p {
            color: white;
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #7CFC00;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #9ACD32;
            text-decoration: underline;
        }

        /* Add icon placeholders in inputs */
        .form-group {
            position: relative;
        }

        .form-group input, 
        .form-group select {
            padding-left: 45px;
        }

        .form-group::before {
            font-family: Arial, sans-serif;
            position: absolute;
            left: 15px;
            top: 45px;
            color: #666;
            font-size: 18px;
            z-index: 1;
        }

       
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Registration</div>
       
        <form id="registration-form" action="process-register.php" method="POST">
            <div class="form-group">
                <label for="username"><i class="fa-solid fa-user"></i>&nbsp;Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username">
                <div class="error" id="username-error">Username must be at least 3 characters</div>
            </div>

            <div class="form-group">
                <label for="email"><i class="fa-solid fa-envelope"></i>&nbsp;Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email address">
                <div class="error" id="email-error">Please enter a valid email</div>
            </div>

            <div class="form-group">
                <label for="password"><i class="fa-solid fa-lock"></i> &nbsp;Password</label>
                <input type="password" id="password" name="password" required placeholder="Create a password">
                <div class="error" id="password-error">Password must be at least 8 characters</div>
            </div>

            <div class="form-group">
                <label for="role"><i class="fa-solid fa-users"></i> &nbsp;Role</label>
                <select id="role" name="role" required>
                    <option value="">Select your role</option>
                    <option value="farmer">Farmer/Seller</option>
                    <option value="buyer">Buyer</option>
                </select>
                <div class="error" id="role-error">Please select a role</div>
            </div>

            <button type="submit" class="btn">CREATE ACCOUNT</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>

        </form>
    </div>

    <script>
        function validateForm(event) {
            let isValid = true;
            
            // Username validation
            const username = document.getElementById('username').value;
            if (username.length < 3) {
                document.getElementById('username-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('username-error').style.display = 'none';
            }

            // Email validation
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('email-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('email-error').style.display = 'none';
            }

            // Password validation
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                document.getElementById('password-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('password-error').style.display = 'none';
            }

            // Role validation
            const role = document.getElementById('role').value;
            if (!role) {
                document.getElementById('role-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('role-error').style.display = 'none';
            }

            return isValid;
        }

        document.getElementById('registration-form').addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html>





<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;x
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: url('ASSETS/IMAGES/f9.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        
            padding: 20px;
        } 

        .container {
            max-width: 500px;
            width: 100%;
            background: rgba(57, 62, 1, 0.7); /* Dark semi-transparent background */
            padding: 35px 40px;
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(19, 24, 1, 0.61);
            backdrop-filter: blur(5px); /* Adds a subtle blur effect */
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .title {
            font-size: 32px;
            font-weight: 600;
            color: white;
            position: relative;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .title::after {
            content: '';
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            bottom: -10px;
            height: 3px;
            width: 60px;
            background: #4CAF50;
            border-radius: 3px;
        }

        form .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: white;
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 16px;
            letter-spacing: 0.5px;
        }

        .form-group input,
        .form-group select {
            height: 50px;
            width: 100%;
            outline: none;
            border-radius: 10px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            background: rgba(255, 255, 255, 0.9);
            padding: 0 18px;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 15px rgba(76, 175, 80, 0.5);
        }

        .error {
            color: #ff6b6b;
            font-size: 14px;
            margin-top: 8px;
            display: none;
            background: rgba(0, 0, 0, 0.4);
            padding: 4px 10px;
            border-radius: 4px;
        }

        .success-message {
            color: #28a745;
            text-align: center;
            margin-bottom: 15px;
        }

        .btn {
            height: 55px;
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 12px 15px;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin-top: 10px;
            letter-spacing: 1px;
        }

        .btn:hover {
            background-color: #45a049;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }

        .btn:active {
            transform: translateY(1px);
        }

        #otp-form {
            display: none;
        }

        .alert {
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        p {
            color: white;
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #7CFC00;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        a:hover {
            color: #9ACD32;
            text-decoration: underline;
        }

        /* Add icon placeholders in inputs */
        .form-group {
            position: relative;
        }

        .form-group input, 
        .form-group select {
            padding-left: 45px;
        }

        .form-group::before {
            font-family: Arial, sans-serif;
            position: absolute;
            left: 15px;
            top: 45px;
            color: #666;
            font-size: 18px;
            z-index: 1;
        }

        .form-group:nth-child(1)::before {
            content: "👤";
        }

        .form-group:nth-child(2)::before {
            content: "✉";
        }

        .form-group:nth-child(3)::before {
            content: "🔒";
        }

        .form-group:nth-child(4)::before {
            content: "🔍";
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Registration</div>
       
        <form id="registration-form" action="process-register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required placeholder="Enter your username">
                <div class="error" id="username-error">Username must be at least 3 characters</div>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email address">
                <div class="error" id="email-error">Please enter a valid email</div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required placeholder="Create a password">
                <div class="error" id="password-error">Password must be at least 8 characters</div>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select your role</option>
                    <option value="farmer">Farmer/Seller</option>
                    <option value="buyer">Buyer</option>
                </select>
                <div class="error" id="role-error">Please select a role</div>
            </div>

            <button type="submit" class="btn">CREATE ACCOUNT</button>
            <p>Already have an account? <a href="login.php">Login here</a></p>

        </form>
    </div>

    <script>
        function validateForm(event) {
            let isValid = true;
            
            // Username validation
            const username = document.getElementById('username').value;
            if (username.length < 3) {
                document.getElementById('username-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('username-error').style.display = 'none';
            }

            // Email validation
            const email = document.getElementById('email').value;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                document.getElementById('email-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('email-error').style.display = 'none';
            }

            // Password validation
            const password = document.getElementById('password').value;
            if (password.length < 8) {
                document.getElementById('password-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('password-error').style.display = 'none';
            }

            // Role validation
            const role = document.getElementById('role').value;
            if (!role) {
                document.getElementById('role-error').style.display = 'block';
                isValid = false;
            } else {
                document.getElementById('role-error').style.display = 'none';
            }

            return isValid;
        }

        document.getElementById('registration-form').addEventListener('submit', function(event) {
            if (!validateForm()) {
                event.preventDefault();
            }
        });
    </script>
</body>
</html> -->