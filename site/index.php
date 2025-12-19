<?php

$host = 'localhost';
$dbname = 'dictionary';
$username = 'root';
$password = '';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$definition = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $word = $conn->real_escape_string(trim($_POST['word']));
    $sql = "SELECT definition FROM words WHERE word = '$word'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $definition = $row['definition'];
    } else {
        $definition = "Word not found.";
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Dictionary</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: #eaf6ff; 
            color: #333;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }

        .container {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px);
            border-radius: 16px;
            padding: 40px 50px;
            max-width: 450px;
            width: 90%;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
            color: #1a3d6c; 
        }

        p.description {
            font-size: 1.1rem;
            margin-bottom: 30px;
            color: #1a5276; 
        }

        input[type="text"] {
            padding: 14px 16px;
            width: 100%;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1rem;
            background: #f9fcff;
            color: #333;
            outline: none;
            transition: border-color 0.3s ease, background 0.3s ease;
        }

        input[type="text"]:focus {
            border-color: #1a5276; 
            background: #eaf6ff;
        }

        input[type="submit"] {
            padding: 14px 20px;
            background-color: #1a5276;
            color: #fff;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #14517a;
            transform: scale(1.05);
        }

        .definition {
            margin-top: 20px;
            font-size: 1.1rem;
            padding: 15px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 8px;
            color: #333;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .definition p {
            margin: 0;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Our Dictionary test</h1>
        <p class="description">Quickly find definitions of any word in a clean and modern interface.</p>
    <p></p>
        <form method="post">
            <input type="text" name="word" placeholder="Enter a word" required>
            <input type="submit" value="Search">
        </form>
        <div class="definition">
            <?php if ($definition): ?>
                <p><?php echo $definition; ?></p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>