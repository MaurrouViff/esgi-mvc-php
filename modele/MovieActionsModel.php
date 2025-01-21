<?php
session_start();

class MovieActionsModel {
    public function addToFavorites($movieId) {
        if (!isset($_SESSION['favorites'])) {
            $_SESSION['favorites'] = [];
        }

        if (!in_array($movieId, $_SESSION['favorites'])) {
            $_SESSION['favorites'][] = $movieId;
        }
    }

    public function addToWatchLater($movieId) {
        if (!isset($_SESSION['watch_later'])) {
            $_SESSION['watch_later'] = [];
        }

        if (!in_array($movieId, $_SESSION['watch_later'])) {
            $_SESSION['watch_later'][] = $movieId;
        }
    }

    public function markAsWatched($movieId) {
        if (!isset($_SESSION['watched'])) {
            $_SESSION['watched'] = [];
        }

        if (!in_array($movieId, $_SESSION['watched'])) {
            $_SESSION['watched'][] = $movieId;
        }
    }
}