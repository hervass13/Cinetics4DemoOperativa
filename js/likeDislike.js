function like(){
    //var like = document.getElementById("liked");
    var like = document.getElementById("liked");
    like.classList.remove('hoverSinLike');
    like.classList.add('likeDonat');

    var dislike = document.getElementById("dislike");
    if ( dislike.className.match(/(?:^|\s)likeDonat(?!\S)/)){
        dislike.classList.remove('likeDonat');  
        }
    dislike.classList.add('hoverSinLike');
}
function dislike(){
    var dislike = document.getElementById("dislike");
    dislike.classList.remove('hoverSinLike');
    dislike.classList.add('likeDonat');

    var like = document.getElementById("like");
    if ( like.className.match(/(?:^|\s)likeDonat(?!\S)/)){
          like.classList.remove('likeDonat');  
    }
    like.classList.add('hoverSinLike');
}
function likeDislike(){
    var like = document.getElementById("like");
    like.classList.remove('likeDonat');
    like.classList.add('hoverSinLike');

    var dislike = document.getElementById("dislike");
    dislike.classList.remove('likeDonat');
    dislike.classList.add('hoverSinLike');
    //document.getElementById("like").className = document.getElementById("like").className.replace( /(?:^|\s)likeDonat(?!\S)/g , '' )
    //document.getElementById("dislike").className = document.getElementById("dislike").className.replace( /(?:^|\s)dislikeDonat(?!\S)/g , '' )
}