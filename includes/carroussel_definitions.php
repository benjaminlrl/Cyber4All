<?php
include_once(__DIR__ . '/../lib/Connexion.php');
include_once(__DIR__ . '/../lib/MotCle_CRUD.php');
include_once(__DIR__ . '/../lib/MotCategorie_CRUD.php');
include_once(__DIR__ . '/../lib/MotCle.php');

use lib\Connexion;
use lib\MotCle;
use lib\MotCategorie_CRUD;
use lib\MotCle_CRUD;


$id_session = session_id();
if ($id_session):

$pdo = new Connexion("user");
$connexion = $pdo->setConnexion();
if (is_a($connexion, "PDO")):
    $crudMot = new MotCle_CRUD($connexion);
    $mots = $crudMot->recup10MotsAleatoires();
    $crudMotCategorie = new MotCategorie_CRUD($connexion);
    foreach ($mots as $mot):
        $listeCategories[$mot->getId()] = $crudMotCategorie->recupToutesLesCategoriesDunMot($mot);
    endforeach;
endif

?>
<div class="caroussel">
    <div class="caroussel__wrapper">
        <div class="caroussel-slides" id="slides">
        <?php
            $numSlide = 0;
            foreach ($mots as $mot):
                    $numSlide++ ;?>
            <div class="slide" id="slide-<?= $numSlide ?>" title="slide de Cryptographie asymétrique">
                <h3 class="slide__title">
                    <?= $mot->getMot();?>
                </h3>
                <p class="slide__definition">
                    <?= $mot->getDefinition();?>
                </p>
                <?php
                // Récupérer les catégories spécifiques à ce mot
                $categoriesDuMot = $listeCategories[$mot->getId()] ?? [];
                foreach ($categoriesDuMot as $categorie): ?>
                    <a href="lexiques.php?categorie=<?= $categorie->getId()?>" class="slide__categorie"
                       title="Catégorie du mot" data-category="<?= $categorie->getId() ?>">
                        <?= $categorie->getNom() ?>
                    </a>
                <?php endforeach; ?>
        </div>
        <?php endforeach; ?>
        </div>
    </div>

    <button class="caroussel-controle-precedent" onclick="changeSlide(-1)"></button>
    <button class="caroussel-controle-suivant" onclick="changeSlide(1)"></button>

    <div class="caroussel-indicateurs">
        <?php
            $numSlide = 0;
            foreach ($mots as $mot):
                $numSlide++ ;?>
        <button class="caroussel-indicateur" onclick="currentSlide(<?= $numSlide ?>)"></button>
        <?php endforeach; ?>
    </div>
</div>
    <script>
        let currentSlideIndex = 0;
        const slides = document.getElementById('slides');
        const indicators = document.querySelectorAll('.caroussel-indicateur');
        const totalSlides = <?= count($mots)?>;
        let autoPlayInterval; // Variable pour stocker l'intervalle

        function showSlide(index) {
            currentSlideIndex = index;
            const translateX = -index * 100;
            slides.style.transform = `translateX(${translateX}%)`;

            // Mettre à jour les indicateurs
            indicators.forEach((indicator, i) => {
                indicator.classList.toggle('actif', i === index);
            });
        }

        function changeSlide(direction) {
            currentSlideIndex += direction;

            if (currentSlideIndex >= totalSlides) {
                currentSlideIndex = 0;
            } else if (currentSlideIndex < 0) {
                currentSlideIndex = totalSlides - 1;
            }

            showSlide(currentSlideIndex);
        }

        function currentSlide(index) {
            showSlide(index - 1);
        }

        // Nouvelle fonction pour gérer le clic sur les indicateurs avec reset du timer
        function currentSlideWithReset(index) {
            currentSlide(index);
            resetAutoPlay(); // Remet le timer à 0
        }

        // Fonction pour arrêter et redémarrer l'auto-play
        function resetAutoPlay() {
            clearInterval(autoPlayInterval); // Arrête l'intervalle actuel
            startAutoPlay(); // Redémarre un nouvel intervalle
        }

        // Fonction pour démarrer l'auto-play
        function startAutoPlay() {
            autoPlayInterval = setInterval(() => {
                changeSlide(1);
            }, 5000);
        }

        // Démarrer l'auto-play initial
        startAutoPlay();

        // Support tactile pour mobile
        let startX = 0;
        let endX = 0;

        slides.addEventListener('touchstart', (e) => {
            startX = e.touches[0].clientX;
        });

        slides.addEventListener('touchend', (e) => {
            endX = e.changedTouches[0].clientX;
            const diff = startX - endX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    changeSlide(1); // Swipe gauche
                } else {
                    changeSlide(-1); // Swipe droite
                }
                resetAutoPlay(); // Reset le timer après un swipe
            }
        });

        // Optionnel : Reset le timer aussi sur les boutons précédent/suivant
        document.querySelector('.caroussel-controle-precedent').addEventListener('click', () => {
            resetAutoPlay();
        });

        document.querySelector('.caroussel-controle-suivant').addEventListener('click', () => {
            resetAutoPlay();
        });
    </script>


<?php endif; ?>


