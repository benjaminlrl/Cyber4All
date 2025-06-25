<?php
?>
<div class="caroussel">
    <div class="caroussel__wrapper">
        <div class="caroussel-slides" id="slides">
            <div class="slide" id="slide-1" title="slide de Cryptographie asymétrique">
                <h3 class="slide__title">
                    Cryptographie asymétrique
                </h3>
                <p class="slide__definition">
                    Système de chiffrement utilisant une paire de clés publique/privée.
                </p>
                <a class="slide__categorie"
                   href="#"
                   title="lien vers la catégorie de la définition">
                    Catégorie
                </a>
            </div>

            <div class="slide" title="slide de Cryptographie asymétrique">
                <h3 class="slide__title">
                    Slide 2
                </h3>
                <p class="slide__definition">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci corporis labore maxime obcaecati
                    provident ratione, sequi tempore voluptate. Aliquid culpa earum, eos exercitationem incidunt
                    ipsam nemo nesciunt odio qui saepe.
                </p>
                <a class="slide__categorie"
                   href="#"
                   title="lien vers la catégorie de la définition">
                    Catégorie
                </a>
            </div>

            <div class="slide" title="slide de Cryptographie asymétrique">
                <h3 class="slide__title">
                    Slide 3
                </h3>
                <p class="slide__definition">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci corporis labore maxime obcaecati
                    provident ratione, sequi tempore voluptate. Aliquid culpa earum, eos exercitationem incidunt
                    ipsam nemo nesciunt odio qui saepe.
                </p>
                <a class="slide__categorie"
                   href="#"
                   title="lien vers la catégorie de la définition">
                    Catégorie
                </a>
            </div>
        </div>
    </div>

    <button class="caroussel-controle-precedent" onclick="changeSlide(-1)"></button>
    <button class="caroussel-controle-suivant" onclick="changeSlide(1)"></button>

    <div class="caroussel-indicateurs">
        <button class="caroussel-indicateur actif" onclick="currentSlide(1)"></button>
        <button class="caroussel-indicateur" onclick="currentSlide(2)"></button>
        <button class="caroussel-indicateur" onclick="currentSlide(3)"></button>
    </div>
</div>

<script>
    let currentSlideIndex = 0;
    const slides = document.getElementById('slides');
    const indicators = document.querySelectorAll('.caroussel-indicateur');
    const totalSlides = 3;

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

    // Auto-play (optionnel)
    setInterval(() => {
        changeSlide(1);
    }, 5000);

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
        }
    });
</script>

