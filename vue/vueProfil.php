<?php
ini_set('display_errors', 1);
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<h1>Bienvenue, <?= htmlspecialchars($user['nom']); ?> !</h1>

<p>Voici vos informations :</p>
<ul>
    <li><strong>ID :</strong> <?= htmlspecialchars($user['id']); ?></li>
    <li><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nom']); ?></li>
</ul>
<p>Liste de films :</p>
<ul>
    <?php foreach ($listeFilmNoms as $filmNom): ?>
        <li><?= htmlspecialchars($filmNom); ?></li>
    <?php endforeach; ?>
</ul>
<p>Liste d'amis :</p>
<ul>
    <?php foreach ($user['friends_id'] as $friendId): ?>
        <li><?= htmlspecialchars($classUsers->recupUserById($friendId)['nom']); ?></li>
    <?php endforeach; ?>
</ul>
<p>Demandes d'amis :</p>
<ul>
    <?php foreach ($user['friends_request_id'] as $requestId): ?>
        <li>
            <?= htmlspecialchars($classUsers->recupUserById($requestId)['nom']); ?>
            <form method="post" action="?action=profil" style="display:inline;">
                <input type="hidden" name="action" value="acceptFriend">
                <input type="hidden" name="userId" value="<?= $_SESSION['user']['id']; ?>">
                <input type="hidden" name="requestId" value="<?= $requestId; ?>">
                <button type="submit">Accepter</button>
            </form>
            <form method="post" action="?action=profil" style="display:inline;">
                <input type="hidden" name="action" value="rejectFriend">
                <input type="hidden" name="userId" value="<?= $_SESSION['user']['id']; ?>">
                <input type="hidden" name="requestId" value="<?= $requestId; ?>">
                <button type="submit">Refuser</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>



<h2>Ajouter un ami</h2>
<form method="post" action="?action=profil">
    <input type="hidden" name="action" value="addFriend">
    <input type="hidden" name="userId" value="<?= $_SESSION['user']['id']; ?>">
    <select name="friendId">
        <?php
        $ListUtilisateur = new Users(0);
        foreach ($ListUtilisateur->getAllUsersNotFriend($user['id']) as $utilisateur) {
            echo '<option value="' . htmlspecialchars($utilisateur['id']) . '">' . htmlspecialchars($utilisateur['nom']) . '</option>';
        }
        ?>
    </select>
    <button type="submit">Ajouter comme ami</button>
</form>

<script>
    document.querySelectorAll('.accept-request').forEach(function(element) {
        element.addEventListener('click', function(event) {
            event.preventDefault();
            var requestId = this.getAttribute('data-request-id');
            var userId = <?= $_SESSION['user']['id']; ?>;
            fetch('../controller/profil.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        action: 'acceptFriend',
                        userId: userId,
                        requestId: requestId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log("data", data);

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
            console.log("la")
        });
    });
</script>