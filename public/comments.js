class Comments {
    /** Stiahnutie dát zo servera a vloženie do stránky */
    getAllComments() {
        let searchParams = new URLSearchParams(window.location.search);
        let postId = searchParams.get('postid');
        fetch('?a=getAllComments&postid='+postId)
            .then(response => response.json())
            .then(data => {
                let html = "";
                for (let message of data.reverse()) {
                    html += "<div class='comment-nick'>" + message.author_id + ":</div>" + "<div class='comment-text'>" + message.comment + "</div>";
                    document.getElementById("comments").innerHTML = html;    // Vopchatie stringu do elementu
                }
            });
    }

    /** Automatické sťahovanie nových dát zo servera */
    startCommentsReloading() {
        setInterval(() => {
            this.getAllComments()
        }, 1000);
    }

    /** Funkcia pre odoslanie správy */
    sendComment() {
        let commentText = document.getElementById("comment-text").value;
        let searchParams = new URLSearchParams(window.location.search);
        let postId = searchParams.get('postid');

        let formData = new FormData;
        formData.append('comment', commentText);
        formData.append('post_id', postId);

        if (commentText.length < 2) {
            alert("Komentár je moc krátky.");
            return;
        }
        /** V prípade, že text.length >= 3, správa sa odošle na server a on odošle odpoveď. */
        fetch("?a=addComment", {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())  // Lebo odpoveď z Controllera vraciam v JSON. AK by som vracal ako String, použijem .text()
            .then(response => {                 // Odošleme odpoveď
                if (response == "Error") {
                    alert("Komentár je moc krátky. SERVER");
                } else if (response == "notLogged") {
                    alert("Nie ste prihlásený!");
                }
            });
    }
}

/** inicializácia premennej (po tom, keď sa mi načíta celý dokument) */
window.onload = function () {
    var comments = new Comments();
    // Po implementovaní refresh intervalu mi vyššie uvedené netreba (keďže sa volá automaticky, nie po kliknutí)
    comments.getAllComments();
    comments.startCommentsReloading();

    document.getElementById("btn-odoslat").onclick = () => {
        comments.sendComment();
        comments.getAllComments();
    }
}