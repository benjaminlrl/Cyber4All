<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_GET['erreur']) && $_GET['erreur']!=99){
    exit;
}
if(isset($_SESSION['utilisateur'])){
    $utilisateur = $_SESSION['utilisateur'];
}
?>
<?php if (isset($utilisateur)):?>
    <form action='ajoutLexique.php' method='POST' class="form-connexion ajout-mot">
        <h1>Ajouter un mot</h1>
        <div class="input-wrapper">
            <div class="input-container">
                <input type='text'
                       placeholder='Mot que vous aimeriez ajouter'
                       name='nouvMot'
                       value=""
                       title="Saisir votre nouveau mot"
                       class='input-box-connexion'
                       required>
            </div>
        </div>

        <div class="input-wrapper">
            <div class="input-container">
                <div class="textarea-container">
                    <textarea
                        id="mainTextarea"
                        placeholder="Tapez la définition du mot ici..."
                        maxlength="10000"
                        name="nouvDefinition"
                        value=""
                        required
                    ></textarea>
                </div>
            </div>
        </div>

        <?php // Générer un token unique pour ce formulaire
        $token = uniqid('ajout_', true);
        $_SESSION['ajout_tokens'][] = $token;?>
        <input type="hidden" name="ajout_token" value="<?= $token //pour ne pas ressoumettre le
        // formulaire au refresh de la page?>">

        <button type='submit' class='btn-submit-connexion'>
            Soumettre la demande de création
            <i class='fa-solid fa-right-to-bracket'></i>
        </button>
    </form>
<?php endif; ?>

