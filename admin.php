<?php
session_start();
$tiers = ['S', 'A', 'B', 'C', 'D'];
// Check if the user is authenticated
if (!isset($_SESSION["authenticated"]) || $_SESSION["authenticated"] !== true) {
    // If not authenticated, redirect to login page
    header("Location: Login.php");
    exit;
}


// Load the games.json file
$games = json_decode(file_get_contents('games.json'), true);
if ($games === null) {
    $games = [
        'S' => [],
        'A' => [],
        'B' => [],
        'C' => [],
        'D' => [],
    ];
}

// Update games.json with textarea contents
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $main = [];
    foreach ($tiers as $tier) {
        if (isset($_POST[$tier])) {
            $main[$tier] = explode("\n", $_POST[$tier]);
        }
    }
    file_put_contents('games.json', json_encode($main, JSON_PRETTY_PRINT));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav>
        <div class="container">
            <h1>Admin Panel</h1>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
    </nav>
    <div class="container">


        <!-- Textareas for Tiers -->
        <div>
            <h1>Tiers</h1>
            <form action="" method="POST">

                <?php
               
                foreach ($tiers as $tier)
                
                {
                    echo "<h2>$tier Tier:</h2>";
                    echo "<textarea name='$tier' id='$tier' cols='30' rows='10'>" . implode("\n", $games[$tier]) . "</textarea>";
                }
                ?>
                <input type="submit" value="Save Changes">
            </form>

        </div>

        <!-- Submit button for updating textarea contents -->
        <form action="admin.php" method="post">
            <input type="hidden" name="action" value="update">

        </form>
    </div>
</body>

</html>