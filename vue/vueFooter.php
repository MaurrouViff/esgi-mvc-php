</body>
<footer>
    <?php
    if (isset($_SESSION['user'])) {
       echo ( '<a href="./?action=logout">Se Déconnecter</a>');
    }
    ?>
</footer>
</html>