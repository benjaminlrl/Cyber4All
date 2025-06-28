<?php

namespace lib;

/**
 * Cette classe représente une notiifcation
 * sous le header sous forme de bandeau
 * Elle dispose d'un type (ERREUR, ATTENTION, INFO, SUCCES)
 */
class BandeauNotification
{
    private string $type;
    private string $titre;
    private string $details;

    /**
     * Initialise une nouvelle instance de la classe notification
     * @param string $type
     * @param string $titre
     * @param string $details
     */
    public function __construct(string $type, string $titre, string $details){
        $this->type = $type;
        $this->titre = $titre;
        $this->details = $details;
    }

    public function notificationInfo(BandeauNotification $bandeauNotification): string{
        $type = $bandeauNotification->type;
        $titre = $bandeauNotification->titre;
        $details = $bandeauNotification->details;
        if($type === "INFO"):
        $notification =("<!-- Bandeau de notification Info -->
            <div class='fr-notice fr-notice--info'>
                <div class='fr-notice__body'>
                    <div class='fr-notice__icon fr-notice__icon--info'>
                        <svg width='20' height='20' viewBox='0 0 24 24' fill='currentColor'>
                            <path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z'/>
                        </svg>
                    </div>
                    <div class='fr-notice__content'>
                        <h3 class='fr-notice__title'>".$titre ."</h3>
                        <p class='fr-notice__detail'>".$details."</p>
                    </div>
                </div>
                <button class='fr-notice__close' onclick='closeNotification(this)'>
                    <svg width='16' height='16' viewBox='0 0 24 24' fill='currentColor'>
                        <path d='M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59
                         6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z'/>
                    </svg>
                </button>
            </div><script>
                function closeNotification(button) {
                    const notification = button.parentElement;
                    notification.classList.add('closing');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            </script>");
        elseif($type === "SUCCES"):
            $notification =("<!-- Bandeau de notification Succès -->
            <div class='fr-notice fr-notice--success' style='margin-top: 1rem;'>
                <div class='fr-notice__body'>
                    <div class='fr-notice__icon fr-notice__icon--success'>
                        <svg width='20' height='20' viewBox='0 0 24 24' fill='currentColor'>
                            <path d='M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z'/>
                        </svg>
                    </div>
                    <div class='fr-notice__content'>
                        <h3 class='fr-notice__title'>".$titre ."</h3>
                        <p class='fr-notice__detail'>".$details."</p>
                    </div>
                </div>
                <button class='fr-notice__close' onclick='closeNotification(this)'>
                    <svg width='16' height='16' viewBox='0 0 24 24' fill='currentColor'>
                        <path d='M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 
                        12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z'/>
                    </svg>
                </button>
            </div><script>
                function closeNotification(button) {
                    const notification = button.parentElement;
                    notification.classList.add('closing');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            </script>");
        elseif($type === "ATTENTION"):
            $notification =("<!-- Bandeau de notification Avertissement -->
                <div class='fr-notice fr-notice--warning' style='margin-top: 1rem;'>
                    <div class='fr-notice__body'>
                        <div class='fr-notice__icon fr-notice__icon--warning'>
                            <svg width='20' height='20' viewBox='0 0 24 24' fill='currentColor'>
                                <path d='M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z'/>
                            </svg>
                        </div>
                        <div class='fr-notice__content'>
                            <h3 class='fr-notice__title'>".$titre ."</h3>
                            <p class='fr-notice__detail'>".$details."</p>
                        </div>
                    </div>
                    <button class='fr-notice__close' onclick='closeNotification(this)'>
                        <svg width='16' height='16' viewBox='0 0 24 24' fill='currentColor'>
                            <path d='M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59
                             6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z'/>
                        </svg>
                    </button>
                </div><script>
                function closeNotification(button) {
                    const notification = button.parentElement;
                    notification.classList.add('closing');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            </script>");
        elseif($type === "ERREUR"):
            $notification =("<!-- Bandeau de notification Erreur -->
                <div class='fr-notice fr-notice--error' style='margin-top: 1rem;'>
                    <div class='fr-notice__body'>
                        <div class='fr-notice__icon fr-notice__icon--error'>
                            <svg width='20' height='20' viewBox='0 0 24 24' fill='currentColor'>
                                <path d='M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52
                                 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z'/>
                            </svg>
                        </div>
                        <div class='fr-notice__content'>
                            <h3 class='fr-notice__title'>".$titre ."</h3>
                            <p class='fr-notice__detail'>".$details."</p>
                        </div>
                    </div>
                    <button class='fr-notice__close' onclick='closeNotification(this)'>
                        <svg width='16' height='16' viewBox='0 0 24 24' fill='currentColor'>
                            <path d='M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59
                             6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z'/>
                        </svg>
                    </button>
                </div><script>
                function closeNotification(button) {
                    const notification = button.parentElement;
                    notification.classList.add('closing');
                    setTimeout(() => {
                        notification.remove();
                    }, 300);
                }
            </script>");
        endif;
        return $notification;
    }

}