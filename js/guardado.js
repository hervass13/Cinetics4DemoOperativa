function guardat(){
    //var like = document.getElementById("liked");
    var save = document.getElementById("save");
    save.classList.remove('hoverSinLike');
    save.classList.add('likeDonat');

}
function noGuardat(){
    var save = document.getElementById("save");
    save.classList.remove('likeDonat');
    save.classList.add('hoverSinLike');
}