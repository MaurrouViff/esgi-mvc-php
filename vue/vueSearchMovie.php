<?php

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Search Results</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        .movies-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 0;
        }

        .movie-card {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 200px;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            max-height: 400px;
        }

        .movie-card img {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
        }

        .movie-card h2 {
            margin: 10px 0;
            font-size: 16px;
            padding: 0 10px;
        }
    </style>
</head>

<body>
    <form action="" method="get">
        <label for="content">Recherche</label>
        <input type="hidden" name="action" id="action" value="search">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <input type="text" name="content" id="content">
        <button type="submit">Rechercher</button>
    </form>


    <h1>Movie Search Results</h1>
    <?php if (isset($movies['error'])): ?>
        <p>An error occurred: <?php echo htmlspecialchars($movies['error']); ?></p>
    <?php elseif (!empty($movies['results'])): ?>
        <div class="movies-grid">
            <?php foreach ($movies['results'] as $movie): ?>
                <a href="?action=movieDetails&id=<?php echo htmlspecialchars($movie['id']); ?>" class="movie-card">
                    <?php if (!empty($movie['poster_path'])): ?>
                        <img src="https://image.tmdb.org/t/p/w200<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
                    <?php else: ?>
                        <p>No poster available</p>
                    <?php endif; ?>
                    <h2><?php echo htmlspecialchars($movie['title']); ?></h2>
                </a>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No movies found.</p>
    <?php endif; ?>
</body>

</html>