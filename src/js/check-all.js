
 $(document).ready(function() {
     $('.ui_table .select_all').click(function() {
         if(this.checked) {
             $('.ui_table .checkbox').each(function() {
                 this.checked = true;
             });
         }
         else{
             $('.ui_table .checkbox').each(function() {
                 this.checked = false;          
                 });
             }
     });
 });