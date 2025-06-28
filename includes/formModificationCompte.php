<?php
if(isset($_SESSION['utilisateur'])){
    $utilisateur = $_SESSION['utilisateur'];
}
?>
<form action='monCompte.php' method='POST' class="form-modifier-moncompte">
    <h1>Modifier mes informations</h1>
    <div class="input-wrapper">
        <div class="input-container">
            <input type='text'
                   id="nouvPseudo"
                   name='nouvPseudo'
                   value="<?= $utilisateur->getPseudo() ?>"
                   placeholder="Nouveau pseudo"
                   title="Votre nouveau pseudo"
                   class='input-box-connexion'
            >
            <i class='fa-solid fa-user input-icon'></i>
        </div>
        <small class="champIndication">Entre 3 et 13 caractères
        </small>
    </div>
    <div class="input-wrapper">
        <div class="input-container">
            <input type='email'
                   id='nouvEmail'
                   name='nouvEmail'
                   placeholder='Nouvel email'
                   value="<?= $utilisateur->getEmail() ?>"
                   title="Votre nouvel email"
                   class='input-box-connexion'
            >
            <i class="fa-solid fa-envelope input-icon"></i>
        </div>
        <label class="checkbox-container">
            <input type="checkbox"
                   value="true"
                   name="suppEmail">
            <span class="checkbox-label checkbox-delete-label">Supprimer mon email</span>
        </label>
    </div>

    <button type='submit'
            class='btn-submit-connexion'
            name="validDonnee"
            value="modifDonnee">
        Valider les modifications
    </button>
</form>
