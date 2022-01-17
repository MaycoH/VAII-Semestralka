class Events {
    getAllEvents() {
        fetch('?c=events&a=getAllEvents')
            .then(response => response.json())
            .then(data => {
                let html = "";
                for (let message of data) {
                    html += '<div class="card mb-3 mx-3"><div class="card-body">'
                          + "<div class='event-time' id='title'> Začiatok udalosti: </div><div class='event-time'>" + new Date(Date.parse(message.startTime)).toLocaleString('sk') + "</div>"
                          + "<div class='event-time' id='title'> Koniec udalosti: </div><div class='event-time'>" + new Date(Date.parse(message.endTime)).toLocaleString('sk') + "</div>"
                          + "<div class='event-place' id='title'> Miesto konania: </div><div class='event-place'>" + message.place + "</div>"
                          + "<div class='event-description' id='title'> Popis udalosti: </div><div class='event-description'>" + message.eventDescription + "</div></div></div>";
                    document.getElementById("events").innerHTML = html;    // Vopchatie stringu do elementu
                }
            });
    }

    /** Automatické sťahovanie nových dát zo servera */
    startEventsReloading() {
        setInterval(() => {
            this.getAllEvents()
        }, 5000);
    }

    /** Funkcia pre odoslanie správy */
    createEvent() {
        let startTime = document.getElementById("startTime").value;
        let endTime = document.getElementById("endTime").value;
        let place = document.getElementById("place").value;
        let eventDescription = document.getElementById("eventDescription").value;

        let formData = new FormData;
        formData.append('zaciatok', startTime);
        formData.append('koniec', endTime);
        formData.append('miesto', place);
        formData.append('popis', eventDescription);

        let error = false;
        if (!Date.parse(startTime)) {
            setInputNG(document.getElementById("startTime"));
            error = true;
        } else
            setInputOK(document.getElementById("startTime"));

        if (!Date.parse(endTime)) {
            setInputNG(document.getElementById("endTime"));
            error = true;
        } else if (Date.parse(startTime) > Date.parse(endTime)) {
            setInputNG(document.getElementById("endTime"));
            alert("Udalosť nemôže skončiť skôr než začne!");
            error = true;
        } else if (Date.parse(endTime) < Date.now()) {
            setInputNG(document.getElementById("endTime"));
            alert("Event už skončil!");
            error = true;
        } else
            setInputOK(document.getElementById("endTime"));

        if (!(place.length > 5 && place.length < 255)) {
            setInputNG(document.getElementById("place"));
            error = true;
        } else
            setInputOK(document.getElementById("place"));

        if (eventDescription.length < 5) {
            setInputNG(document.getElementById("eventDescription"));
            error = true;
        } else
            setInputOK(document.getElementById("eventDescription"));
        if (error) return;

        /** V prípade, že text.length >= 3, správa sa odošle na server a on odošle odpoveď. */
        fetch("?c=events&a=createNewEvent", {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())  // Lebo odpoveď z Controllera vraciam v JSON. AK by som vracal ako String, použijem .text()
            .then(response => {                 // Odošleme odpoveď
                    if (response == "wrongStartDateTime")
                        alert("Odpoveď zo servera: \nZadaný neplatný čas začiatku udalosti!");
                    else if (response == "wrongEndDateTime")
                        alert("Odpoveď zo servera: \nZadaný neplatný čas konca udalosti!");
                    else if (response == "cantEndBeforeStart")
                        alert("Odpoveď zo servera: \nUdalosť nemôže skončiť skôr než začne!");
                    else if (response == "wrongDesc")
                        alert("Odpoveď zo servera: \nPopis udalosti je krátky! Musí obsahovať viac ako 5 znakov!");
                    else if (response == "endIsOver")
                        alert("Odpoveď zo servera: \nEvent už skončil!");
                    else if (response == "placeIsShort")
                        alert("Odpoveď zo servera: \nMiesto je neplatné. Musí obsahovať > 5 a < 255 znakov!");
                    else if (response == "notLogged")
                        alert("Odpoveď zo servera: \nNie ste prihlásení, nemôžete pridať novú udalosť!");
                }
            );
    }
}

/** inicializácia premennej (po tom, keď sa mi načíta celý dokument) */
window.onload = function () {
    var events = new Events();
    // Po implementovaní refresh intervalu mi vyššie uvedené netreba (keďže sa volá automaticky, nie po kliknutí)
    events.getAllEvents();
    events.startEventsReloading();

    document.getElementById("btn-odoslat").onclick = () => {
        events.createEvent();
        events.getAllEvents();
    }
    document.getElementById("btn-clear").onclick = () => {
        document.getElementById("startTime").value = null;
        document.getElementById("endTime").value = null;
        document.getElementById("place").value = null;
        document.getElementById("eventDescription").value = null;
    }
}

function checkInput(input) {
    let el = input, elid = input.id;
    switch (elid) {
        case "startTime":   // Kontrola platnosti času začiatku
            if (Date.parse(el.value)) {
                if (Date.parse(el.value) < Date.parse(document.getElementById("endTime").value)) {
                    setInputOK(el);
                    setInputOK(document.getElementById("endTime"));
                    return true;
                } else if (Date.parse(el.value) > Date.parse(document.getElementById("endTime").value)) {
                    setInputOK(el);
                    setInputNG(document.getElementById("endTime"));
                    document.getElementById("endError").innerText = "Udalosť nemôže skončiť skôr než začne!";
                    return false;
                } else {
                    setInputNG(document.getElementById("endTime"));
                    return false;
                }
            } else {
                setInputNG(el);
                return false;
            }
        case "endTime":     // Kontrola platnosti času konca
            if (Date.parse(el.value)) {
                if (Date.parse(el.value) < Date.parse(document.getElementById("startTime").value)) {
                    setInputNG(el);
                    document.getElementById("endError").innerText = "Udalosť nemôže skončiť skôr než začne!";
                    return false;
                } else if (new Date() > Date.parse(el.value)) {
                    setInputNG(el);
                    document.getElementById("endError").innerText = "Udalosť už skončila!";
                    return false;
                } else if (Date.parse(el.value) > Date.parse(document.getElementById("startTime").value)) {
                    setInputOK(el);
                    return true;
                } else {
                    setInputNG(el);
                    return false;
                }
            } else {
                setInputNG(el);
                document.getElementById("endError").innerText = "Zadaný neplatný čas konca!";
                return false;
            }
        case "place":       // Kontrola platnosti miesta
            if (el.value.length > 5 && el.value.length < 255) {
                setInputOK(el);
                return true;
            } else {
                setInputNG(el);
                return false;
            }
        case "eventDescription":    // Kontrola platnosti popisu
            if (el.value.length > 5) {
                setInputOK(el);
                return true;
            } else {
                setInputNG(el);
                return false;
            }
    }
}
