// JavaScript pour afficher les images agrandies dans une fenêtre modale

// Récupérer les éléments nécessaires
var modal = document.getElementById('myModal');
var images = document.querySelectorAll('.img-thumbnail');
var modalImg = document.getElementById("img01");
var closeButton = document.getElementsByClassName("close")[0];

// Fonction pour afficher une image dans la fenêtre modale
function displayImage(imageSrc) {
    modal.style.display = "block";
    modalImg.src = imageSrc;
}

// Attacher un événement de clic à chaque image pour afficher l'image dans la fenêtre modale
images.forEach(function(image) {
    image.addEventListener('click', function() {
        var imageSrc = this.src;
        displayImage(imageSrc);
    });
});

// Attacher un événement de clic au bouton de fermeture pour fermer la fenêtre modale
closeButton.addEventListener('click', function() {
    modal.style.display = "none";
});
