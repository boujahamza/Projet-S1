var current_selected = "";

angle = 0;
$("#show-button").click(function(){
    $("#participants-container").slideToggle();
    angle = (angle + 180)%360;
    $('#show-button').css("transform","rotate("+angle+"deg)");
});

function select(id){
    if(current_selected != ""){
        document.getElementById(current_selected).style.cssText = "background-color: #d1d8d4; color: black";
    }
    document.getElementById(id).style.cssText = "background-color: #e61f17;color: white";
    current_selected = id;
    $.get (
        'generer_selection.php',
        'id_user='+id,
        show_selection,
        'text'
    );
}

function show_selection(results){
    $("#selected").html(results);
    $("#not-selected").slideUp();
    $("#selected").slideDown();
}


