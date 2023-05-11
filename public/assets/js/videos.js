let collection, boutonAjout, span;
window.onload = () => {
    collection = document.querySelector(".videos");
    span = collection.querySelector("span");
    boutonAjout = document.createElement("button");
    boutonAjout.className = "ajout-video btn btn-success";
    boutonAjout.innerText = "Ajouter une vidéo";

    let nouveauBouton = span.append(boutonAjout);

    collection.dataset.index = collection.querySelectorAll("input").length;
    boutonAjout.addEventListener("click", function(){
        addButton(collection, nouveauBouton);
    });
}

function addButton(collection, nouveauBouton){
    //corriger l'index qui doit commencer au nombre de videos déjà ajoutées avec videosCount 
    let videosCount =  document.querySelector("#videosCount");
    let counttoadd = videosCount ? videosCount.value : null;
    let prototype = collection.dataset.prototype;
    let index = collection.dataset.index + counttoadd;
    prototype = prototype.replace(/__name__/g, index);
    let content = document.createElement("html");
    content.innerHTML = prototype;
    let newForm = content.querySelector("div");
    let boutonSuppr = document.createElement("button");
    boutonSuppr.type = "button";
    boutonSuppr.className = "btn btn-danger";
    boutonSuppr.id = "delete-video-" + index;
    boutonSuppr.innerText = "Supprimer cette vidéo";
    newForm.append(boutonSuppr);
    collection.dataset.index++;
    let boutonAjout = collection.querySelector(".ajout-video");
    span.insertBefore(newForm, boutonAjout);
    
    boutonSuppr.addEventListener("click", function(){
        this.previousElementSibling.parentElement.remove();
    })
}