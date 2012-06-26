$(document).ready(function(){
    
    var first_id = 0;
    function getData(){
        
        $.getJSON('http://aia.case-dev.com/get.php', {from:first_id}, function(data){
            var items = [];
            first_run = true;
            $i = 0;
            $.each(data, function(key, val) {
                sentimentImg = "";
                if(val.sentiment == "pos") {
                    sentimentImg = '<img style="width:25px;border-radius:15px;float:right;margin-top:-5px;margin-right:-35px; border:1px solid #5BB75B;padding:2px" src="/images/up.png">';
                } 
                if(val.sentiment == "neg") {
                    sentimentImg = '<img style="width:25px;border-radius:15px;float:right;margin-top:-5px;margin-right:-35px; border:1px solid #DA4F49;padding:2px" src="/images/down.png">';
                }
                
                items.push('<div class="alert-message block-message info" id="' + val._id.$id + '" style="padding-bottom:23px; min-height:40px" data_tw_id="'+val.id_str+'"><p>'+sentimentImg+'<img style="float:left; margin-right: 10px; border-radius:5px; display:inline-block;" src="'+ val.user.profile_image_url +' "/><strong>' + val.user.screen_name + ':</strong> '+replaceURLWithHTMLLinks(val.text)+'. <span class="label">'+val.created_at+'</span></p></div>');
               

                if(first_run){
                    first_id = val.id_str;
                    first_run = false;
                }
                $i++;
            });

            $('#data-display').prepend(items.join(''));
            console.log("Last id: "+first_id);
            console.log("Fetched: "+$i);
        })
    }

    getData();
    setInterval( function(){
        getData();
    }, 30000 );
});

function replaceURLWithHTMLLinks(text) {
    var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/i;
    return text.replace(exp,"<a target='_blank' href='$1'>$1</a>"); 
}

