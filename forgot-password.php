<!DOCTYPE html>
<html>
<head>
    <title>Forgot Password</title>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css"> -->
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,500,600,700&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Comfortaa:400,700" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">

    <style>
                body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding-top: 20px;
        }

        form {
            background: #ffffff;
            padding: 20px 30px;
            border: 1px solid #ddd;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: left;
            margin: 40px auto;
        }

        form input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }

        form input[type="submit"] {
            background-color: #218838;
            color: #fff;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        form input[type="submit"]:hover {
            background-color: #1c7430;
        }

        span {
            color: red;
            font-size: 14px;
            display: block;
        }

        h3 {
            text-align: center;
        }

        .account-subtitle {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    </style>
</head>
<body>


    <form method="post" action="send-password-reset.php">

    <h3>Forget Password</h3>
        <p class="account-subtitle">Enter your email to reset your password</p>
        <input type="text" name="email" placeholder="Email Id *" required>
        <input type="submit" name="submit" value="Submit">

    </form>

</body>
</html>