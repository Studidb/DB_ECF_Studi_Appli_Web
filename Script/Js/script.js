// Fonctionnalité Responsive => Menu Burger ou pas

// Sélectionner les éléments du DOM
const burgerMenu = document.getElementById('burger-menu');
const Section_Navigation = document.getElementById('Section_Navigation');

// Ajouter un événement au clic sur le bouton burger
burgerMenu.addEventListener('click', (event) => {
event.stopPropagation();

// Alterner la classe 'active' pour afficher ou cacher le menu
Section_Navigation.classList.toggle('active');

// Cacher l'icône du menu burger lorsqu'il est actif
burgerMenu.classList.toggle('active');
});

// Fermer le menu si l'utilisateur clique en dehors
document.addEventListener('click', (event) => {

    // Vérifier si le clic n'a pas eu lieu dans le menu ou le bouton burger
    if (!burgerMenu.contains(event.target) && !Section_Navigation.contains(event.target)) {
    // Retirer la classe 'active' pour cacher le menu
    Section_Navigation.classList.remove('active');
    burgerMenu.classList.remove('active');
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
            const animalsContainer = habitatContainer.querySelector('.animal2');
            
            // Bascule l'affichage des animaux
            animalsContainer.style.display = animalsContainer.style.display === 'none' ? 'block' : 'none';
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
                if (element.style.display === 'none') {
                    element.style.display = 'block';
                } else {
                    element.style.display = 'none';
                }
            });
        }
    });
});

// Fonction pour afficher la popup
function ouvrirPopup() {
    document.getElementById("popupFormulaire").style.display = "flex";
}

// Fonction pour fermer la popup
function fermerPopup() {
    document.getElementById("popupFormulaire").style.display = "none";
}

// Fermer la popup si l'utilisateur clique en dehors du formulaire
window.onclick = function(event) {
    var popup = document.getElementById("popupFormulaire");
    if (event.target == popup) {
        popup.style.display = "none";
    }
}

function deconnexion() {
    window.location.href = 'deconnexion.php';
}