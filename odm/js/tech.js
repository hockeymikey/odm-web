function GetUrlValue(VarSearch){
    var SearchString = window.location.search.substring(1);
    var VariableArray = SearchString.split('&');
    for(var i = 0; i < VariableArray.length; i++){
        var KeyValuePair = VariableArray[i].split('=');
        if(KeyValuePair[0] == VarSearch){
            return KeyValuePair[1];
        }
    }
}

$(function() {
    var lng = "";
	lng = GetUrlValue('lang');
		if(typeof lng == "undefined") {
			var lng = "";
			}
	if (lng.length == 0 || lng === "" || lng != "de") {
		language = 'en';
	}
	else {
		language = GetUrlValue('lang');
		}
	$.ajax({
		url: 'lang/languages.xml',
		success: function(xml) {
			$(xml).find('translation').each(function(){
				var id = $(this).attr('id');
				var text = $(this).find(language).text();
				$("." + id).html(text);
			});
		}
	});
});

function loadUrl(newLocation)
{
  window.location.href = newLocation;
}

$(document).ready(
    function() {
        $('#open').click(
            function() {
                $('#overlay').show('slow',
                    function() {
                        $('#container').fadeIn('slow');
                        //$('#changeText').html('Dynamischer Inhalt');
                    }
                );
            }
        );
 
         $('#close').click(
            function() {
                $('#container').hide('slow',
                     function() {
                          $('#overlay').fadeOut();          
                     }    
                );
            }
        );  
    }
);