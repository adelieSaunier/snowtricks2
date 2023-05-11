let loadmorebtn = document.getElementById('loadmoretricks');
// On met un écouteur d'évènements
loadmorebtn.addEventListener("click", function(e){
    // On empêche la navigation
    e.preventDefault();
    console.log(connecteduser)

    // On envoie la requête ajax
    fetch(this.getAttribute("href"), {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        body: {"connecteduser": connecteduser}
        
    }).then(response => response.json())

    .then(data => {
        
    })
    
});