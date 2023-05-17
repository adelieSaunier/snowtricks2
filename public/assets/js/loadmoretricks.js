let loadmorebtn = document.getElementById('loadmoretricks');
// On met un écouteur d'évènements
loadmorebtn.addEventListener("click", function(e){
    // On empêche la navigation
    e.preventDefault();
    //console.log(connecteduser)

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
        const parsedata = JSON.parse(data)
        console.log(parsedata)
        //console.log(parsedata[1])
        //console.log(connecteduser)
        let tricksrow = document.getElementById("tricks")

        for (var i = 1; i < parsedata.length; i ++ ){
            if(connecteduser != 2) {
                let newtrick = document.createElement("div")
                newtrick.className = "col-lg-3 mb-3 col-md-4 col-sm-6"

                newtrick.innerHTML =` 
                    <div class="trick-card-main">
                        <div class="card h-100">
                            <a href="/figures/${parsedata[i].slug}">
                                <img src="/assets/uploads/main/mini/300x300-${parsedata[i].mainimage}" class="d-block w-100" alt="${parsedata[i].name}">  
                            </a>
    
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center col">
                                    <small class="text-muted">${parsedata[i].name}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                 `;
                tricksrow.append(newtrick);
            }
            else if(connecteduser == 2){
                let newtrick = document.createElement("div")
                newtrick.className = "col-lg-3 mb-3 col-md-4 col-sm-6"

                newtrick.innerHTML =`   
                    <div class="trick-card-main">
                        <div class="card h-100">
                            <a href="/figures/${parsedata[i].slug}">
                                <img src="/assets/uploads/main/mini/300x300-${parsedata[i].mainimage}" class="d-block w-100" alt="${parsedata[i].name}">  
                            </a>
    
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center col">
                                    <small class="text-muted">${parsedata[i].name}</small>
                                    <div class="btn-group m-0" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modal_${parsedata[i].id}">
                                            <img src="assets/icons/trash.png" alt="pen icon" height="16"/>
                                        </button>
                                        <a href="/figures/modification/${parsedata[i].id}" class="btn btn-sm btn-outline-secondary">
                                            <img src="assets/icons/edit.png" alt="pen icon" height="16"/>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;

                tricksrow.append(newtrick);
            }

        }
        


        
    })
    
});