<?php
if(isset($_GET['erreur']) && (
           $_GET['erreur']!= 99 // Mots de passes saisis sont différents
        && $_GET['erreur']!= 98 // Pseudo déjà existant, veuillez en choisir un autre
        && $_GET['erreur']!= 97 // Cette adresse email est déjà associé à un compte
    )){
    exit;
}
$email= null;
$pseudo="";
if(!empty($_GET['erreur'])){
    $erreur = $_GET['erreur'];
    if($erreur == "99"){
        $msgErreur = "Les mots de passes saisis sont différents";
        isset($_SESSION['utilisateur_pseudo']) ?
            $pseudo = $_SESSION['utilisateur_pseudo'] :
            $pseudo = "";
        isset($_SESSION['utilisateur_email']) ?
            $email = $_SESSION['utilisateur_email']:
            $email = null;
    }elseif($erreur == "98"){
        $msgErreur= "Pseudo déjà existant, veuillez en choisir un autre";
        isset($_SESSION['utilisateur_email']) ?
            $email = $_SESSION['utilisateur_email']:
            $email = null;
    }elseif($erreur == "97"){
        $msgErreur= "Cette adresse email est déjà associé à un compte";
        isset($_SESSION['utilisateur_pseudo']) ?
            $pseudo = $_SESSION['utilisateur_pseudo'] :
            $pseudo = "";
    }
}
?>
<form action='inscription.php' method='POST' class="form-connexion">
    <h1>S'inscrire</h1>
    <?php if(!empty($_GET['erreur'])): ?>
    <div class="input-container">
        <h2><?= $msgErreur ?></h2>
    </div>
    <?php endif;?>
    <div class="input-container">
        <small class="champIndication">* Champ obligatoire
        </small>
        <input type='text'
               placeholder='Votre pseudo'
               name='pseudo'
               value="<?= isset($_GET['erreur']) ? $pseudo : "" ?>"
               minlength="3"
               maxlength="13"
               oninvalid="this.setCustomValidity('Pseudo entre 3 et 13 caractères')"
               onchange="this.setCustomValidity('')"
               title="Saisir votre pseudo"
               class='input-box-connexion'
               required>
        <i class='fa-solid fa-user input-icon'></i>
        <small class="champIndication">Entre 3 et 13 caractères
        </small>
    </div>


    <div class="input-container">
        <input type='email'
               placeholder='exemple@domaine.fr'
               name='email'
               value="<?= isset($_GET['erreur']) ? $email : null ?>"
               minlength="5"
               maxlength="150"
               title="3 à 13 caractères : lettres, chiffres, tirets et underscores seulement."
               oninvalid="this.setCustomValidity('Veuillez rentrer une adresse valide')"
               onchange="this.setCustomValidity('')"
               autocomplete="off"
               class='input-box-connexion'>
        <i class='fa-solid fa-envelope input-icon'></i>
    </div>


    <div class="input-container">
        <small class="champIndication">* Champ obligatoire
        </small>
        <div class="password-container">
            <input type='password'
                   id='mdp'
                   placeholder='Votre mot de passe'
                   name='mdp'
                   oninvalid="this.setCustomValidity('Le mot de passe doit contenir entre 13 et 255 caractères,\n ' +
                    'une majuscule, un chiffre compris entre 0 et 9,\n et un symbole parmi ?!*@=%$£[]+/\.&')"
                   onchange="this.setCustomValidity('')"
                   pattern="^(?=.*[A-Z])(?=.*[0-9])(?=.*[\?!*@=%$£\[\]+\/\\\.&]).{13,255}$"
                   minlength="13"
                   maxlength="255"
                   title="Saisir le mot de passe"
                   autocomplete="off"
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
        <div class="password-container">
            <input type='password'
                   id='mdp_validation'
                   placeholder='Vérifier le mot de passe'
                   name='mdp_validation'
                   pattern="^(?=.*[A-Z])(?=.*[0-9])(?=.*[\?!*%@=$£\[\]+\/\\\.&]).{13,255}$"
                   minlength="13"
                   maxlength="255"
                   title="Saisir le mot de passe de vérification"
                   class='input-box-connexion'
                   required>
            <i class='fa-solid fa-lock-keyhole input-icon'></i>
        </div>
        <small class="champIndication">Minimum 13 caractères dont au moin:
            <br> - 1 minuscule - 1 Majuscule
            <br> - 1 symbole parmi  .*?!%@=$£\[]+
            <br> - Ne doit pas contenir le pseudo
        </small>
    </div>



    <button type='submit' class='btn-submit-connexion'>
        S'inscrire
        <i class='fa-solid fa-right-to-bracket'></i>
    </button>
    <a href="identification.php">J'ai déja un compte, me connecter</a>
</form>
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