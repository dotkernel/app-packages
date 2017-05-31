/**
 * Created by andrian on 30.05.2017.
 */

$(function(){
    var packages = $("#package-list .row");
    var timer;
    $("input#search").keyup(function(){
        clearTimeout(timer);
        var ms = 350; // milliseconds
        var needle = $(this).val().toLowerCase(), show;
        timer = setTimeout(function() {
            $("#package-list").hide();
            packages.each(function(){
                show = $(this).find("h4").text().toLowerCase().indexOf(needle) != -1;
                $(this).toggle(show);
            });
            $('#package-list').show();
        }, ms);
    }).focus();
    $('input#search').change(function(){
        window.location.hash = "!/" + $(this).val().toLowerCase();
    });
    $(window).on("hashchange", function() {
        var $input = $('input#search');
        if (window.location.hash.indexOf("#!/") == 0) {
            $input.val(window.location.hash.replace(/#!\//,"").toLowerCase());
            $input.trigger("keyup");
        } else {
            var $anchor = $("h3[id='"+window.location.hash.replace(/^#/,"")+"']");
            if ($anchor.length != $anchor.filter(":visible").length) {
                $input.val("").trigger("keyup");
                $anchor.get(0).scrollIntoView();
            }
        }
    });
});
