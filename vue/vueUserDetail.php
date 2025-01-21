<h1>Bienvenue, <?= htmlspecialchars($user['nom']); ?> !</h1>

<p>Voici vos informations :</p>
<ul>
    <li><strong>ID :</strong> <?= htmlspecialchars($user['id']); ?></li>
    <li><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nom']); ?></li>
    <li><strong>Amis :</strong> <?= htmlspecialchars(json_encode($user['friend_id'])); ?></li>
    <li><strong>Films :</strong> <?= htmlspecialchars(json_encode($user['films'])); ?></li>
</ul>

<a href="./?action=logout">Se déconnecter</a>
