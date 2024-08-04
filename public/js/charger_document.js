
var filename2 = "";

document.getElementById('inputGroupFile01').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const fileName = file.name;
    if (filename2 != fileName) {
        document.getElementById('naiccance').value = fileName;
        filename2=fileName;
    }else{
        document.getElementById('naiccance').style.color="red";
        document.getElementById('naiccance').innerHTML="vous avez déjà sélectionné ce fichier comme résidence";
        document.getElementById('inputGroupFile01').value='';
    }
});

document.getElementById('inputGroupFile02').addEventListener('change', function (event) {
    const file = event.target.files[0];
    const fileName = file.name;
    if(filename2!=fileName){
    document.getElementById('résidence').value = fileName;
    }else{
        document.getElementById('label_for_residence').style.color="red";
        document.getElementById('label_for_residence').innerHTML="vous avez déjà sélectionné ce fichier comme act de naiccance";
        document.getElementById('inputGroupFile02').value='';
    }
});