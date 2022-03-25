function starsValorationGeneral(valoracion, cont) {
    if(valoracion == 0) {
        var noStar = document.getElementById("starhalf"+cont);
        noStar.checked = false;  
    }
    else if (valoracion > 0 && valoracion <= 0.1) {
        var starhalf = document.getElementById("starhalf"+cont);
        starhalf.checked = true;
    }
    else if (valoracion <= 0.2) {
        var star1 = document.getElementById("star1"+cont);
        star1.checked = true;
    }
    else if (valoracion <= 0.3) {
        var star1half = document.getElementById("star1half"+cont);
        star1half.checked = true;
    }
    else if (valoracion <= 0.4) {
        var star2 = document.getElementById("star2"+cont);
        star2.checked = true;
    }
    else if (valoracion <= 0.5) {
        var star2half = document.getElementById("star2half"+cont);
        star2half.checked = true;
    }
    else if (valoracion <= 0.6) {
        var star3 = document.getElementById("star3"+cont);
        star3.checked = true;
    }
    else if (valoracion <= 0.7) {
        var star3half = document.getElementById("star3half"+cont);
        star3half.checked = true;
    }
    else if (valoracion <= 0.8) {
        var star4 = document.getElementById("star4"+cont);
        star4.checked = true;
    }
    else if (valoracion <= 0.9) {
        var star4half = document.getElementById("star4half"+cont);
        star4half.checked = true;
    }
    else if (valoracion <= 1){
        var star5 = document.getElementById("star5"+cont);
        star5.checked = true;
    }
}

function starsValoration(valoracion) {
    if(valoracion == 0) {
        var noStar = document.getElementById("starhalf");
        noStar.checked = false;  
    }
    else if (valoracion > 0 && valoracion <= 0.1) {
        var starhalf = document.getElementById("starhalf");
        starhalf.checked = true;
    }
    else if (valoracion <= 0.2) {
        var star1 = document.getElementById("star1");
        star1.checked = true;
    }
    else if (valoracion <= 0.3) {
        var star1half = document.getElementById("star1half");
        star1half.checked = true;
    }
    else if (valoracion <= 0.4) {
        var star2 = document.getElementById("star2");
        star2.checked = true;
    }
    else if (valoracion <= 0.5) {
        var star2half = document.getElementById("star2half");
        star2half.checked = true;
    }
    else if (valoracion <= 0.6) {
        var star3 = document.getElementById("star3");
        star3.checked = true;
    }
    else if (valoracion <= 0.7) {
        var star3half = document.getElementById("star3half");
        star3half.checked = true;
    }
    else if (valoracion <= 0.8) {
        var star4 = document.getElementById("star4");
        star4.checked = true;
    }
    else if (valoracion <= 0.9) {
        var star4half = document.getElementById("star4half");
        star4half.checked = true;
    }
    else if (valoracion <= 1){
        var star5 = document.getElementById("star5");
        star5.checked = true;
    }
}
//meter una clase que solo llene las estrellas dependiendo de la nota poniendo los ids de cada input, si no que borre la clase en caso que esté*/