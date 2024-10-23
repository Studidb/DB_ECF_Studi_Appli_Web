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