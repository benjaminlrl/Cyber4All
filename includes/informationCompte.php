<?php
include_once('../lib/Utilisateur.php');
include_once('../lib/Utilisateur_CRUD.php');
include_once('../lib/Connexion.php');

use lib\Utilisateur;
use lib\Utilisateur_CRUD;
use lib\Connexion;

if(!empty($_SESSION['utilisateur'])):
    $utilisateur = $_SESSION['utilisateur'];
endif;
?>

<div class="form-compte">
    <h1>Mon compte</h1>
    <div class="input-wrapper">
        <div class="input-container">
            <input type='text'
                   name='pseudo'
                   value="<?= "Pseudo : ".$utilisateur->getPseudo() ?>"
                   title="Votre pseudo"
                   class='input-box-connexion'
                   disabled
            >
            <i class='fa-solid fa-user input-icon'></i>
        </div>
    </div>

    <div class="input-wrapper">
        <div class="input-container">
                <input type='text'
                       id='email'
                       value="<?= $utilisateur->getEmail()!=null?"Email : ".$utilisateur->getEmail():'Email non rensiegné' ?>"
                       name='email'
                       title="Votre email"
                       class='input-box-connexion'
                       disabled
                >
                <i class="fa-solid fa-envelope input-icon"></i>
        </div>
    </div>

    <div class="input-wrapper">
        <div class="input-container">
            <input type='text'
                   id='role'
                   value="<?= "Rôle : ".$utilisateur->getRole() ?>"
                   name='role'
                   title="Votre rôle"
                   class='input-box-connexion'
                   disabled>
            <?php if ($utilisateur->getRole() === "contributeur"): ?>
                <i class="fa-solid fa-user-shield input-icon"></i>
            <?php endif;?>
            <?php if ($utilisateur->getRole() === "admin"): ?>
                <i class="fa-solid fa-shield-halved input-icon"></i>
            <?php endif;?>
        </div>
    </div>
</div>