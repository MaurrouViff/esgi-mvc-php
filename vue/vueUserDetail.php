<section>

<h1>Bienvenue, <?= htmlspecialchars($user['nom']); ?> !</h1>

<p>Voici vos informations :</p>
<ul>
    <li><strong>ID :</strong> <?= htmlspecialchars($user['id']); ?></li>
    <li><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($user['nom']); ?></li>
    <li><strong>Amis :</strong> <?= htmlspecialchars(json_encode($user['friend_id'])); ?></li>
    <li><strong>Films :</strong> <?= htmlspecialchars(json_encode($user['films'])); ?></li>
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
</section>
