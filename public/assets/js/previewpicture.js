//preview Picture
document.getElementById("tricks_form_mainimage").addEventListener('change', function(e){
  
    // L'image img#image
    let image = document.getElementById('img');
    document.getElementById('bgtohide').appendChild(image)
        
    // e.files contient un objet FileList
    const [picture] = this.files

    // "picture" est un objet File
    if (picture && this.files[0]) {

        // L'objet FileReader
        var reader = new FileReader();

        // L'événement déclenché lorsque la lecture est complète
        reader.onload = function (e) {
            // On change l'URL de l'image (base64)
            image.src = e.target.result
        }

        // On lit le fichier "picture" uploadé
        reader.readAsDataURL(picture)
    } 
    
})