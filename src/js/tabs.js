$(document).ready(function(){
    $(".ui_tabs a").click(function(e) {
        e.preventDefault();
        $(this).parent().addClass("active");
        $(this).parent().siblings().removeClass("active");
        var tab = $(this).attr("href");
        $(".ui_tab_content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
});