// Fonctionnalité Responsive => Menu Burger ou pas

// Sélectionner les éléments du DOM
const burgerMenu = document.getElementById('burger-menu');
const sectionNavigation = document.getElementById('Section_Navigation');

// Ajouter un événement au clic sur le bouton burger
burgerMenu?.addEventListener('click', (event) => {
    event.stopPropagation();

    // Alterner la classe 'active' pour afficher ou cacher le menu
    sectionNavigation?.classList.toggle('active');

    // Cacher l'icône du menu burger lorsqu'il est actif
    burgerMenu?.classList.toggle('active');
});

// Fermer le menu si l'utilisateur clique en dehors
document.addEventListener('click', (event) => {
    // Vérifier si le clic n'a pas eu lieu dans le menu ou le bouton burger
    if (!burgerMenu?.contains(event.target) && !sectionNavigation?.contains(event.target)) {
        // Retirer la classe 'active' pour cacher le menu
        sectionNavigation?.classList.remove('active');
        burgerMenu?.classList.remove('active');
    }
});

// Fonctionnalité Habitats => Affichage des animaux au clic
document.addEventListener('DOMContentLoaded', () => {
    // Sélectionne toutes les images d'habitats
    const habitatImages = document.querySelectorAll('.habitat_image');

    habitatImages.forEach((image) => {
        image.addEventListener('click', () => {
            // Récupère le conteneur parent de l'image d'habitat
            const habitatContainer = image.parentElement;

            // Sélectionne le conteneur des animaux associés à cet habitat
            const animalsContainer = habitatContainer?.querySelector('.animal2');

            // Bascule l'affichage des animaux
            if (animalsContainer) {
                animalsContainer.style.display = animalsContainer.style.display === 'none' ? 'block' : 'none';
            }
        });
    });
});

// Script JavaScript pour cacher un élément avec la classe "Cache" et l'afficher ou le cacher au clic
document.addEventListener('DOMContentLoaded', function() {
    // Récupérer tous les éléments ayant la classe "Cache"
    const elementsCaches = document.querySelectorAll('.Cache');

    // Parcourir chaque élément et ajouter des écouteurs d'événements
    elementsCaches.forEach(function(element) {
        // Cacher initialement l'élément
        element.style.display = 'none';

        // Ajouter un événement de clic sur la div parente pour afficher ou cacher l'élément
        if (element.parentNode) {
            element.parentNode.addEventListener('click', function() {
                // Alterner l'affichage de l'élément caché
                element.style.display = element.style.display === 'none' ? 'block' : 'none';
            });
        }
    });
});

// Fonction pour afficher la popup Connexion
function ouvrirPopup() {
    const popupFormulaire = document.getElementById("popupFormulaire");
    if (popupFormulaire) {
        popupFormulaire.style.display = "flex";
    }
}

// Fonction pour fermer la popup Connexion
function fermerPopup() {
    const popupFormulaire = document.getElementById("popupFormulaire");
    if (popupFormulaire) {
        popupFormulaire.style.display = "none";
    }
}

// Fermer la popup si l'utilisateur clique en dehors du formulaire
window.onclick = function(event) {
    const popupFormulaire = document.getElementById("popupFormulaire");
    const popupAvis = document.getElementById("popupAvis");

    // Fermer la popup de connexion si l'utilisateur clique en dehors
    if (event.target === popupFormulaire) {
        popupFormulaire.style.display = "none";
    }

    // Fermer la popup d'avis si l'utilisateur clique en dehors
    if (event.target === popupAvis) {
        popupAvis.style.display = "none";
    }
};

// Fonction déconnexion
function deconnexion() {
    window.location.href = 'deconnexion.php';
}

// Fonction pour afficher la popup Laissez un avis
function ouvrirPopupAvis() {
    console.log("ouvrirPopupAvis() appelée");
    const popupAvis = document.getElementById("popupAvis");
    if (popupAvis) {
        popupAvis.style.display = "flex";
    }
}

// Fonction pour fermer la popup Avis
function fermerPopupAvis() {
    const popupAvis = document.getElementById("popupAvis");
    if (popupAvis) {
        popupAvis.style.display = "none";
    }
}

// Associer la fonction ouvrirPopupAvis au bouton
document.addEventListener('DOMContentLoaded', () => {
    const avisButton = document.querySelector('button[onclick="ouvrirPopupAvis()"]');
    avisButton?.addEventListener('click', ouvrirPopupAvis);

    const closeAvisButton = document.querySelector('.close-btn[onclick="fermerPopupAvis()"]');
    closeAvisButton?.addEventListener('click', fermerPopupAvis);
});

// Supprimer le blocage de l'envoi de formulaire (event.preventDefault())
document.getElementById('envoiAvis').addEventListener('click', function() {
    console.log("Bouton d'envoi d'avis cliqué");
    
    // Vérification des données avant l'envoi (optionnelle)
    let pseudo = document.getElementById("pseudo").value;
    let message = document.getElementById("message").value;

    console.log("Pseudo: " + pseudo);
    console.log("Message: " + message);
});

// Fonction du compteur

document.getElementById('')
