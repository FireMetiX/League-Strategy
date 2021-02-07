// FORM VALIDATION 

// VARIABELN SETZEN
const submit = document.querySelector("#submit");
const vorname = document.querySelector("#vorname");
const nachname = document.querySelector("#name");
const email = document.querySelector("#email");
const message = document.querySelector("#message");

// Form Validation click Event
submit.addEventListener("click", (e) => {

    // Alle existierenden spans löschen
    if (document.querySelector("form span")) {
        document.querySelectorAll("form span").forEach(e =>{
            e.remove();
        })
    }

    e.preventDefault();
    validateFormular();
    // console.log(e);

});

// Function definieren
function validateFormular(){
    // Input speichern
    let formInput = {};
    // Error messages speichern
    let errorMessages = {};

    // formInput object daten geben
    formInput.vorname = vorname.value;
    formInput.nachname = nachname.value;
    formInput.email = email.value;
    formInput.message = message.value;

    // Form Validieren / daten im errorMessages obj eintragen
    // Vorname
    if (!formInput.vorname) {
        errorMessages.vorname = `Bitte Vorname eingeben!`;
    } else {
        console.log(`Vorname eingegeben`)
    }

    // Nachname
    if (!formInput.nachname) {
        errorMessages.nachname = `Bitte Nachname eingeben!`;
    } else {
        console.log(`Nachname eingegeben`)
    }

    // Email
    if (!formInput.email) {
        errorMessages.email = `Bitte Email eingeben!`;
    } else {
        let emailRegExp = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
        // Prüfen ob die Email eine Email ist
        if (!emailRegExp.test(formInput.email)) {
            errorMessages.email = `Du musst eine Email Adresse eingeben!`;
        }else{
            console.log("Email eingegeben")
        }
    }

    // Message
    if (!formInput.message) {
        errorMessages.message = `Bitte eine Nachricht eingeben!`;
    } else if (formInput.message.length > 250) {
        errorMessages.message = `Du hast mehr als 250 Zeichen eingegeben!`;
    } else {
        console.log(`Nachricht eingegeben`)
    }

    // Prüfen, ob es irgendwelche Fehler gab
    if (Object.keys(errorMessages).length > 0) {
        displayErrors(errorMessages);
    } else {
        //SENDE DATEN ZUM BACKEND
        console.log("Sende zum Backend")
    }

};

// Fehler eingeben DOM
function displayErrors (errorMessages) {
    if (errorMessages.nachname) {
        const errorcontainer = document.createElement("span");
        errorcontainer.setAttribute("class", "contactErrors");
        errorcontainer.innerHTML = errorMessages.nachname;
        document.querySelector("#name").after(errorcontainer);
    }

    if (errorMessages.vorname) {
        const errorcontainer = document.createElement("span");
        errorcontainer.setAttribute("class", "contactErrors");
        errorcontainer.innerHTML = errorMessages.vorname;
        document.querySelector("#vorname").after(errorcontainer);
    }

    if (errorMessages.email) {
        const errorcontainer = document.createElement("span");
        errorcontainer.setAttribute("class", "contactErrors");
        errorcontainer.innerHTML = errorMessages.email;
        document.querySelector("#email").after(errorcontainer);
    }

    if (errorMessages.message) {
        const errorcontainer = document.createElement("span");
        errorcontainer.setAttribute("class", "contactErrors");
        errorcontainer.innerHTML = errorMessages.message;
        document.querySelector("#message").after(errorcontainer);
    }

}