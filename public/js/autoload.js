
document.getElementById('menu_holder').addEventListener('click', playAudio);

function playAudio() {
    const audio = new Audio('/js/audio/notefecation.wav');
    audio.play();
}




var count=0;

setInterval(function () {
    var title=document.getElementById('notefecation_title').innerHTML;
    

    $.ajax({
        url: '/dashboard/side_bar', // URL to fetch the partial content
        method: 'GET',
        dataType: 'json',
        success: function (response) {
            // Update the content with the count value
            if (response.count > 0 && response.count!=count && title!=(response.count + " Nontefecation")) {
                var li = "<a href='/notefecation/" + response.count + "'><strong style='color:red;' id='notefecation_title'>" + response.count + " Nontefecation</strong></a> ";
                document.getElementById('notefecation_button').innerHTML = li;
                document.getElementById('menu_holder').click();
                count=response.count;
            }

        },
        error: function () {
            console.error('Failed to fetch data.');
        }
    });
}, 5000);
