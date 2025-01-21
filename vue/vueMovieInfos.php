<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['title'] ?? 'Movie Details'); ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .movie-details {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            gap: 20px;
        }

        .movie-details img {
            width: 300px;
            height: auto;
            border-radius: 8px;
        }

        .movie-info {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .movie-details h1 {
            margin-top: 0;
        }

        .buttons {
            margin-top: 20px;
        }

        .buttons button {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-right: 10px;
        }

        .favorite-button {
            background-color: #ffcc00;
            color: #fff;
        }

        .watch-later-button {
            background-color: #00ccff;
            color: #fff;
        }

        .watched-button {
            background-color: #66cc66;
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="movie-details">
        <?php if (isset($movie['error'])): ?>
            <p><?php echo htmlspecialchars($movie['error']); ?></p>
        <?php else: ?>
            <img src="https://image.tmdb.org/t/p/w500<?php echo htmlspecialchars($movie['poster_path']); ?>" alt="<?php echo htmlspecialchars($movie['title']); ?>">
            <div class="movie-info">
                <h1><?php echo htmlspecialchars($movie['title']); ?></h1>
                <p><?php echo htmlspecialchars($movie['overview']); ?></p>
                <p><strong>Release Date:</strong> <?php echo htmlspecialchars($movie['release_date']); ?></p>
                <p><strong>Rating:</strong> <?php echo htmlspecialchars($movie['vote_average']); ?>/10</p>
                <p><strong>Genres:</strong> 
                    <?php foreach ($movie['genres'] as $genre): ?>
                        <?php echo htmlspecialchars($genre['name']); ?> 
                    <?php endforeach; ?>
                </p>
                <div class="buttons">
                    <button class="favorite-button" onclick="handleAction('favorite', <?php echo htmlspecialchars($movie['id']); ?>)">Add to Favorites</button>
                    <button class="watch-later-button" onclick="handleAction('watch_later', <?php echo htmlspecialchars($movie['id']); ?>)">Watch Later</button>
                    <button class="watched-button" onclick="handleAction('watched', <?php echo htmlspecialchars($movie['id']); ?>)">Mark as Watched</button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
        function handleAction(action, movieId) {
            fetch('../controller/movieActionsController.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: action,
                    movie_id: movieId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Action completed successfully : ' + data.message);
                } else {
                    alert('Action failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        }
    </script>
</body>

</html>