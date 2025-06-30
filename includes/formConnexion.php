<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['utilisateur'])){
    $utilisateur = $_SESSION['utilisateur'];
}
?>
<?php if (!isset($utilisateur)):?>
<form action='identification.php' method='POST' class="form-connexion">
    <h1>Se connecter</h1>
    <?php if(isset($_GET['erreur']) && $_GET['erreur']==99): ?>
    <div class="input-container">
        <h2>Utilisateur non identifié, mot de passe ou pseudo incorrect</h2>
    </div>
    <?php endif; ?>
    <div class="input-wrapper">
        <div class="input-container">
            <input type='text'
                   placeholder='Votre pseudo'
                   name='pseudo'
                   value=""
                   oninvalid="this.setCustomValidity('Saisir votre pseudo')"
                   onchange="this.setCustomValidity('')"
                   title="Saisir votre pseudo"
                   class='input-box-connexion'
                   required>
            <i class='fa-solid fa-user input-icon'></i>
        </div>
        <small class="champIndication">Entre 3 et 13 caractères
        </small>
    </div>

    <div class="input-wrapper">
        <div class="input-container">
            <div class="password-container">
                <input type='password'
                       id='mdp'
                       placeholder='Votre mot de passe'
                       name='mdp'
                       oninvalid="this.setCustomValidity('Le mot de passe doit contenir au minimum 13 caractères')"
                       onchange="this.setCustomValidity('')"
                       pattern="^(?=.*[A-Z])(?=.*[0-9])(?=.*[\?!*@=%$£\[\]+\/\\\.&]).{13,255}$"
                       min="13"
                       title="Saisir le mot de passe"
                       class='input-box-connexion'
                       required>
                <i class='fa-solid fa-lock-keyhole input-icon'></i>

                <button type='button'
                        class='password-toggle'
                        id='togglePassword'
                        title='Afficher/Masquer le mot de passe'>
                    <i class='fa-solid fa-eye'></i>
                </button>
            </div>
        </div>
    <small class="champIndication">Minimum 13 caractères
    </small>
    </div>

    <a href="#">Mot de passe oublié ?</a>

    <?php // Générer un token unique pour ce formulaire
    $token = uniqid('connexion_', true);
    $_SESSION['connexion_tokens'][] = $token;?>
    <input type="hidden" name="connexion_token" value="<?= $token //pour ne pas ressoumettre le
    // formulaire au refresh de la page?>">

    <button type='submit' class='btn-submit-connexion'>
        Se connecter
        <i class='fa-solid fa-right-to-bracket'></i>
    </button>

    <p class="register-link-connexion">Vous n'avez pas de compte ?
        <a href='inscription.php'>
            <span class="important">S'inscrire</span>
        </a>
    </p>
</form>
<?php endif; ?>
<?php if (isset($utilisateur)):?>
<form action='index.php' method='POST' class="form-connexion">
    <h1>Heureux de te voir : <?= $utilisateur->getPseudo(); ?></h1>
    <button type='submit' class='btn-submit-connexion' name="deconnexion" value="deconnexion">
        Se Déconnecter
        <i class='fa-solid fa-right-to-bracket'></i>
    </button>
</form>
<?php endif; ?>
<script>
    // Fonction pour basculer l'affichage du mot de passe
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('mdp');
        const toggleButton = document.getElementById('togglePassword');
        const toggleIcon = toggleButton.querySelector('i');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleIcon.className = 'fa-solid fa-eye-slash';
            toggleButton.classList.add('visible');
            toggleButton.title = 'Masquer le mot de passe';
        } else {
            passwordInput.type = 'password';
            toggleIcon.className = 'fa-solid fa-eye';
            toggleButton.classList.remove('visible');
            toggleButton.title = 'Afficher le mot de passe';
        }
    }

    // Événement pour le bouton de basculement
    document.getElementById('togglePassword').addEventListener('click', togglePasswordVisibility);

    // Raccourci clavier pour basculer l'affichage (Ctrl + Alt + P)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.altKey && e.key === 'p') {
            e.preventDefault();
            togglePasswordVisibility();
        }
    });

    // Animation au focus des inputs
    const inputs = document.querySelectorAll('.input-box-connexion');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });
    });
</script>
