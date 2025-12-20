<?php
$host = "localhost";
$dbname = "restaurant";
$username = "restaurant_user";
$password = "123456";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$resultText = "";
$options = "";

$res = $conn->query("SELECT DISTINCT meal FROM menu");
while ($row = $res->fetch_assoc()) {
    $meal = htmlspecialchars($row['meal']);
    $options .= "<option value='$meal'>$meal</option>";
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!empty($_POST['typed_meal'])) {
        $meal = $conn->real_escape_string($_POST['typed_meal']);
    } else {
        $meal = $conn->real_escape_string($_POST['selected_meal']);
    }

    $q = $conn->query(
        "SELECT restaurant FROM menu WHERE meal='$meal' LIMIT 1"
    );

    if ($q && $q->num_rows > 0) {
        $r = $q->fetch_assoc();
        $resultText = "🍽️ Recommended restaurant: <b>" .
            htmlspecialchars($r['restaurant']) . "</b>";
    } else {
        $resultText = "❌ No restaurant found for this meal.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Food Recommendation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(to right, #ffecd2, #fcb69f);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            background: white;
            width: 420px;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            text-align: center;
        }

        img {
            width: 100%;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        input, select {
            width: 80%;
            padding: 10px;
            border-radius: 8px;
            font-size: 16px;
            margin-top: 10px;
        }

        button {
            margin-top: 15px;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            background-color: #ff7a18;
            color: red;
            font-size: 16px;
            cursor: pointer;
        }

        .result {
            margin-top: 20px;
            font-size: 18px;
        }
    </style>
</head>

<body>
<div class="card">
    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?auto=format&fit=crop&w=800&q=80">
    <h2>Your Restaurant</h2>

    <form method="post">
        <input name="typed_meal" placeholder="Type meal (optional)">
        <select name="selected_meal">
            <?= $options ?>
        </select>
        <br>
        <button type="submit">Recommend 🍽️</button>
    </form>

    <div class="result"><?= $resultText ?></div>
</div>
</body>
</html>

