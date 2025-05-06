ADD SEARCH FUNCTIONALITY                                                                                                                            <?php
// search.php

// Simulated data for crops (in a real application, fetch this from a database)
$crops = [
    "Wheat",
    "Rice",
    "Sugarcane",
    "Cotton",
    "Maize",
    "Barley",
    "Soybean",
    "Millet",
    "Groundnut",
    "Pulses"
];

// Get the search query from the URL
$query = isset($_GET['query']) ? trim($_GET['query']) : '';
$results = [];

if ($query) {
    // Perform a case-insensitive search
    foreach ($crops as $crop) {
        if (stripos($crop, $query) !== false) {
            $results[] = $crop;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #28a745;
        }

        .results {
            margin-top: 20px;
        }

        .result {
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        .result:hover {
            background-color: #f8f9fa;
        }

        .no-results {
            color: red;
            font-size: 1.2rem;
        }

        .back-link {
            margin-top: 20px;
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .back-link:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Search Results</h1>

    <div class="results">
        <?php if (empty($results)): ?>
            <p class="no-results">No crops found matching your query: <strong><?php echo htmlspecialchars($query); ?></strong></p>
        <?php else: ?>
            <ul>
                <?php foreach ($results as $result): ?>
                    <li class="result"><?php echo htmlspecialchars($result); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>

    <a href="index.html" class="back-link">Back to Home</a>
</body>
</html>