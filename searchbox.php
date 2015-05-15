<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>jQuery UI Autocomplete - Multiple, remote</title>
        <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <script src="ckeditor/ckeditor.js"></script>
        <link rel="stylesheet" href="/resources/demos/style.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <style>
            .ui-autocomplete-loading {
            background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
            }
        </style>
        <script>
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
                        $.getJSON( "search.php", {
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
                        //~ alert(elements);
                        $( "#users" ).append( "<div class='"+ui.item.id+"' style='color: white; width:200px; border-radius:5px; background: #242424'><img style='border-radius:50px; width:64px' src='"+ui.item.icon+"'> " + ui.item.value +" <i id='"+ui.item.id+"' class='fa fa-times erase'></i> </div>" );
                        return false;
                    }
                });
            });
        </script>
    </head>
    <body>
     
    <div class="ui-widget">
      <input id="birds" size="50">
      <div id="users"></div>
      <?php session_start();?>
    </div>
    
    
    <form action="sendmessage.php" id="addusers">
        <p></p>
        Asunto: <input type="text" name="subject" value="">
        <p></p>
        <textarea cols="80" id="editor1" name="editor1" rows="10"></textarea>
        <p></p>
        <input type="submit" value="Envíar">
    </form>
        <script type="text/javascript">  
                CKEDITOR.replace( "editor1", { 
                enterMode: CKEDITOR.ENTER_BR,
                skin : "office2013",
                toolbar : [
                    { name: "document", groups: [ "mode", "document", "doctools" ], items: [ "Source", "-", "Save", "Preview", "-", "Templates" ] },
                    { name: "clipboard", groups: ["undo"], items: ["Undo", "Redo" ] },
                    { name: "editing", groups: [ "find", "selection"], items: ["Replace", "-", "SelectAll"] },
                    "/",
                    { name: "basicstyles", groups: [ "basicstyles", "cleanup" ], items: [ "Bold", "Italic", "Underline", "Strike", "Subscript", "Superscript", "-", "RemoveFormat" ] },
                    { name: "paragraph", groups: [ "list", "indent", "blocks", "align"], items: [ "NumberedList", "BulletedList", "-", "Outdent", "Indent", "-", "Blockquote", "CreateDiv", "-", "JustifyLeft", "JustifyCenter", "JustifyRight", "JustifyBlock" ] },
                    "/",
                    { name: "links", items: [ "Link", "Unlink" ] },
                    { name: "insert", items: [ "Image", "Flash", "Table", "HorizontalRule", "Smiley", "SpecialChar", "Iframe" ] },
                    "/",
                    { name: "styles", items: [ "Styles", "Format", "Font", "FontSize" ] },
                    { name: "colors", items: [ "TextColor", "BGColor" ] },
                    { name: "tools", items: [ "Maximize"] }
                ]
                });      
        </script>
     
    <script>            
        $("body").on('click', ".erase" , function() {
            var index = elements.indexOf($(this).attr("id"));
            $("div."+$(this).attr("id")).slideUp();
            if (index > -1) {
                elements.splice(index, 1);
            }
            //~ alert(elements);
        });
    </script>
    <script>
        // Attach a submit handler to the form
        $( "#addusers" ).submit(function( event ) {
         
          //~ alert($form.find( "textarea[name='editor1']" ).val());
          // Stop form from submitting normally
          event.preventDefault();
         
          // Get some values from elements on the page:
          var $form = $( this ),
            term = elements,
            url = $form.attr( "action" );
          var sender = <?php echo json_encode($_SESSION['h']); ?>; 
         
          // Send the data using post
          var posting = $.post( url, { users: term, subject: $form.find( "input[name='subject']" ).val(), content: CKEDITOR.instances['editor1'].getData(), sender: sender});
          // Put the results in a div
          posting.done(function( data ) {
              alert(data);
            //~ var content = $( data ).find( "#content" );
            //~ $( "#result" ).empty().append( content );
          });
        });
    </script>
    </body>
</html>
