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
        let trickdatas = parsedata.payload

        let commentsrow = document.getElementById("comments")

        for (var i = 1; i < trickdatas.length; i ++ ){
            
            let newcomment = document.createElement("div")
            newcomment.className = "col-12"

            newcomment.innerHTML = ` 
            <p>${trickdatas[i].content}</p>
                `;
            commentsrow.append(newcomment);
            
            

        }

    })
    
})