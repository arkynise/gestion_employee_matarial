function display_pdf(pdf,type){
    
    if(type=='R'){
        document.getElementById('pdf_holder').src="images/employees/residence/"+pdf;
    }else{
        document.getElementById('pdf_holder').src="images/employees/act_de_naissance/"+pdf;
    }
    
    document.getElementById('pdf_displayer').style.display="flex";
}


document.getElementById('pdf_displayer').addEventListener('click', function(event) {
    var targetDiv=document.getElementById('pdf_holder')
    if (!targetDiv.contains(event.target)) {
        document.getElementById('pdf_displayer').style.display="none";
    }
});