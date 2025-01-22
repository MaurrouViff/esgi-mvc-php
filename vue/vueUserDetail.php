<h1>Bienvenue, <?= htmlspecialchars($user['nom']); ?> !</h1>

<p>Voici vos informations :</p>
<ul>
    <li><strong>ID :</strong> <?= htmlspecialchars($user['id']); ?></li>
    <li><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nom']); ?></li>
    <li><strong>Amis :</strong> <?= htmlspecialchars(json_encode($user['friends_id'])); ?></li>
    <li><strong>Films :</strong> <?= htmlspecialchars(json_encode($user['films'])); ?></li>
</ul>

<p>Demandes d'amis :</p>
<ul>
    <?php foreach ($user['friends_request_id'] as $requestId): ?>
        <li>
            <?= htmlspecialchars($classUsers->recupUserById($requestId)['nom']); ?>
            <a href="./?action=accept&requestId=<?= $requestId; ?>">Accepter</a>
            <a href="./?action=reject&requestId=<?= $requestId; ?>">Refuser</a>
        </li>
    <?php endforeach; ?>
</ul>

<p>Paramètres :</p>
<h2>Changer de mot de passe</h2>
<form action="./?action=updatePassword" method="post">
    <label for="nom">Votre nom :</label><br />
    <input type="text" id="nom" name="nom" required><br />
    <label for="newPassword">Nouveau mot de passe :</label><br>
    <input type="password" id="newPassword" name="newPassword" required><br>
    <input type="submit" value="Changer le mot de passe">
</form>


<a href="./?action=logout">Se déconnecter</a>
