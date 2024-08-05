



    setInterval(function() {
        console.log(typeof $);
        var idtmp = "#content-container";
        $.ajax({
            url: '/dashboard/side_bar', // URL to fetch the partial content
            method: 'GET',
            dataType: 'json',
        success: function(response) {
            // Update the content with the count value
            if(response.count>0){
                var li="<a href='/notefecation/"+response.count+"'><strong style='color:red;'>"+response.count+" Nontefecation</strong></a> ";
                document.getElementById('notefecation_button').innerHTML=li;
            }
           
        },
        error: function() {
            console.error('Failed to fetch data.');
        }
        });
    }, 5000);
    