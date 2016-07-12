$(document).on("ready", function() {

	var user_h = $("#user_h").attr("value");

	//Load num. messages unread
	$.ajax({
		url: "../../src/ajax/load_messagesunread.php",
		type: "POST",
		data: {user_h:user_h},
		success: function(num_messages_unread) {
			$("#num_messages_unread").html("("+num_messages_unread+")");
		}
	});

	//Autocomplete
	var elements = [];
        $(function() {
        	function split( val ) {
              return val.split( /,\s*/ );
            }
            function extractLast( term ) {
              return split( term ).pop();
            }
            
            $( "#birds" )
              // don't navigate away from the field on tab when selecting an item
              
                .bind( "keydown", function( event ) {
                    if ( event.keyCode === $.ui.keyCode.TAB &&
                        $( this ).autocomplete( "instance" ).menu.active ) {
                            event.preventDefault();
                    }
                })
              
              
                .autocomplete({
                    source: function( request, response ) {
                        $.getJSON( "../../src/ajax/searchbox.php", {
                            term: extractLast( request.term )
                        }, response );
                    },
                    search: function() {
                        // custom minLength
                        var term = extractLast( this.value );
                        if ( term.length < 2 ) {
                            return false;
                        }
                    },
                    focus: function() {
                        // prevent value inserted on focus
                        return false;
                    },
                    select: function( event, ui ) {
                        var terms = split( this.value );
                        // remove the current input
                        terms.pop();
                        // add the selected item
                        terms.push( ui.item.value );
                        // add placeholder to get the comma-and-space at the end
                        //~ terms.push( "" );
                        //~ this.value = terms.join( ", " );
                        this.value = "";
                        elements.push(ui.item.id);
                        $( "#users" ).append( "<div class='"+ui.item.id+" searchbox_usr'> " + ui.item.value +" <i id='"+ui.item.id+"' class='fa fa-times erase'></i> </div>" );
                        //~$( "#users" ).append( "<div class='"+ui.item.id+" searchbox_usr'><img src='"+ui.item.icon+"'> " + ui.item.value +" <i id='"+ui.item.id+"' class='fa fa-times erase'></i> </div>" );
                        
                        return false;
                    }
                });
            });

	//Delete messages
	$(".delete").on("click", function() {
        var msg = $(this);
        var posting = $.post( "../../src/ajax/delmsg.php", {h:$(this).attr("id")});
          // Put the results in a div
          posting.done(function( data ) {
            //~ alert($("."+$(this).attr("id")).text());
            $("div."+msg.attr("id")).toggle("slide");
            //~ var content = $( data ).find( "#content" );
            //~ $( "#result" ).empty().append( content );
          });
    });

	$(".message").on("mouseover", function() {
		$(this).children(".msg_actions").css("opacity", "1");
	});

	$(".message").on("mouseout", function() {
		$(this).children(".msg_actions").css("opacity", "0");
	});



	//Erase users from the list
	$("body").on("click", ".erase" , function() {
		var index = elements.indexOf($(this).attr("id"));
		$("div."+$(this).attr("id")).slideUp();
		if (index > -1) {
			elements.splice(index, 1);
		}
	});


	// Attach a submit handler to the form
	/*$( "#addusers" ).submit(function( event ) {

		// Get some values from elements on the page:
		var $form = $( this ),
		term = elements,
		url = $form.attr( "action" );
		///////////////////////////var sender = '.json_encode($_SESSION['h']).'
        
	});*/

});