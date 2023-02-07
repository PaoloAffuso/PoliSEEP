/*function sceglitipo() {
    var selectDaVerificare = document.getElementById("questiontype");
    var tiposelezionato = selectDaVerificare.value;

    //var indiceSelezionato = selectDaVerificare.selectedIndex;
    //var valoreSelezionato = selectDaVerificare.options[indiceSelezionato];
    //var tiposelezionato = valoreSelezionato.value;

    //document.getElementById('saq').style='display: none;';
    //document.getElementById('maq').style='display: none;';
    //document.getElementById('oq').style='display: none;';

    if(tiposelezionato == 'radioquestion') {
        document.getElementById('saq').style = 'display: block;';
        document.getElementById('maq').style='display: none;';
        document.getElementById('oq').style='display: none;';
    }
    else if(tiposelezionato == 'checkboxquestion') {
        document.getElementById('saq').style = 'display: none;';
        document.getElementById('maq').style='display: block;';
        document.getElementById('oq').style='display: none;';
    }
    else if(tiposelezionato == 'rispaperta') {
        document.getElementById('saq').style = 'display: none;';
        document.getElementById('maq').style='display: none;';
        document.getElementById('oq').style='display: block;';
    }  
}*/


$(document).ready(function() {
    $(".questiontype").on('change', function() {

        var tiposelezionato = $(this).val();
        var section = $(this).parent().parent().parent()

        if(tiposelezionato == 'radioquestion') {            
            section.children('#saq').css("display", "block");
            section.children('#maq').css("display", "none");
            section.children('#oq').css("display", "none");
            console.log(section.children())
        }
        else if(tiposelezionato == 'checkboxquestion') {
            section.children('#saq').css("display", "none");
            section.children('#maq').css("display", "block");
            section.children('#oq').css("display", "none");
        }
        else if(tiposelezionato == 'rispaperta') {
            section.children('#saq').css("display", "none");
            section.children('#maq').css("display", "none");
            section.children('#oq').css("display", "block");
        }

    });
});