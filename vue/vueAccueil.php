<?php

// Générer un nouveau jeton CSRF si non existant
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];
$movies = [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .movie-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }

        .movie-item {
            background-color: #fff;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 200px;
            text-align: center;
        }

        .movie-item img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }

        .movie-item h3 {
            font-size: 18px;
            margin: 10px 0;
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

    <div class="movie-list" id="movie-list">
        <!-- Les films seront insérés ici par JavaScript -->
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('./controller/fetchMoviesController.php')
                .then(response => response.json())
                .then(data => {
                    console.log('Fetched data:', data); // Log the fetched data
                    const movieList = document.getElementById('movie-list');
                    if (data.results && data.results.length > 0) {
                        data.results.forEach(movie => {
                            const movieItem = document.createElement('div');
                            movieItem.className = 'movie-item';
                            movieItem.innerHTML = `
                                <a href="?action=movieDetails&id=${encodeURIComponent(movie.id)}">
                                    <img src="https://image.tmdb.org/t/p/w200${encodeURIComponent(movie.poster_path)}" alt="${encodeURIComponent(movie.title)}">
                                    <h3>${encodeURIComponent(movie.title)}</h3>
                                    <p>${encodeURIComponent(movie.release_date)}</p>
                                </a>
                            `;
                            movieList.appendChild(movieItem);
                        });
                    } else {
                        movieList.innerHTML = '<p>No movies found.</p>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching movies:', error);
                    document.getElementById('movie-list').innerHTML = '<p>Error fetching movies.</p>';
                });
        });
    </script>
</body>
</html>