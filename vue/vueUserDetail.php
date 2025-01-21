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


<a href="./?action=logout">Se déconnecter</a>
