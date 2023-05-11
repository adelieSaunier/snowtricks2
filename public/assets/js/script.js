/*  

*/



/*
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
    
});*/


/*
function showLoadMore(show) {
    console.log(show);
      if(!show) {
        btn = document.getElementById('loadMore');
        btn.remove();
    }
}
  

function loadMore() {
    let comments = document.getElementById('comments');
    $.ajax({

        url: "{{ path('loadmore_comments', {'id': trick.id}) }}",
        type: "POST",
        data: {},

        success: function(result) {
            jsonContent = $.parseJSON(result);
            $.each(jsonContent, function(key, value){
            if(key === 0) {
                console.log(value);
                showLoadMore(value.showLoadMore);
    
            } else {
  
                date = new Date(value.createdAt.date);
                html = "";
                html += '			 <div class="row shadow-sm m-3" id="Commentary_' + value.id + '">';
                html += '                      <div class="col col-1 mx-3">';
                html += '                      <div class="row">';
                //var avatarRoute = '{{ asset(avatars_directory ~ 'image') }}';
                //avatarRoute = avatarRoute.replace("image", value.user.avatar);
                html += '<img src="'+ avatarRoute +'" class="bd-placeholder-img card-img-top" width="32" height="32" alt="image"/>';
                html += value.user.username + '<br>';
                html += date.getFullYear() + '-' + ("0" + (date.getMonth() + 1)).slice(-2) + '-' + ("0" + (date.getDate())).slice(-2);
                html += '                      </div>';
                html += '                      </div>';
                html += '                      <div class="col">';
                html += '                      <div class="row">';
                html += '                        <p>' + value.commentary + '</p>';
                html += '                      </div>';
                html += '                      </div>';
                html += '</div>';

                comments.appendChild(html);	
    
            }
            });
        }
    })
}*/


