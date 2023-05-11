let loadmorecommentsbtn = document.getElementById('loadmorecomments');
// écouteur d'évènements
loadmorecommentsbtn.addEventListener("click", function(e){
    // On empêche la navigation
    e.preventDefault();
    
    // On envoie la requête ajax
    fetch(this.getAttribute("href"), {
        method: "POST",
        headers: {
            "X-Requested-With": "XMLHttpRequest",
            "Content-Type": "application/json"
        },
        
    }).then(response => response.json())

    .then(data => {
        const parsedata = JSON.parse(data)
        console.log(parsedata.payload)
        console.log(parsedata.showLoadMore)
    })
    
})