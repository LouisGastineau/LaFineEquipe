document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector("form");
    const dateField = document.getElementById("workshop_date");

    if (!form || !dateField) {
        console.warn("Formulaire ou champ date non trouvé !");
        return;
    }

    form.addEventListener("submit", function (event) {
        const value = dateField.value;
        if (!value || isNaN(new Date(value).getTime())) {
            alert("Veuillez entrer une date et une heure valides.");
            event.preventDefault();
        }
    });
});