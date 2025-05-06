<!DOCTYPE html>
<html>
<head>
    <title>Verify OTP</title>
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Verify OTP</h2>
        <form action="verify-process.php" method="POST">
            <div class="form-group">
                <label>Enter OTP sent to your email:</label>
                <input type="text" name="otp" required>
            </div>
            <button type="submit" class="btn">Verify OTP</button>
        </form>
    </div>
</body>
</html>
