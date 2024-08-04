document.getElementById('image_uploader').addEventListener('change', function(event) {
    const imagePreview = document.getElementById('profile_image');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagePreview.src =  e.target.result ;
        }
        reader.readAsDataURL(file);
    } 
   
});

