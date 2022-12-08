var frm = $("#frmUpdateCourse");

var frm_data = new FormData();

frm.submit(function (e) {
  e.preventDefault();

  if($("input[name=immagineCorso]")[0]!==undefined)
    var file=$("input[name=immagineCorso]")[0].files[0];
    
  frm_data.append("nomeCorso", $("input[name=nomeCorso]").val());
  frm_data.append("cfuCorso", $("input[name=cfuCorso]").val());
  frm_data.append("docenteCorso", $("input[name=docenteCorso]").val());
  frm_data.append("obiettivoCorso", $("textarea[name=obiettivoCorso]").val());
  frm_data.append("descrizioneCorso", $("textarea[name=descrizioneCorso]").val());
  frm_data.append("verificaCorso", $("input[name=verificaCorso]").val());
  frm_data.append("immagineCorso", file); // prende immagine (file)
   
  $.ajax({
    url: "../teacher/update_Course.php",
    type: "post",
    cache: false,
    contentType: false,
    processData: false,
    data: frm_data,
    success: function (data) {
      console.log(data)
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });

  /*for (var key of frm_data.entries()) 
    console.log(key[0] + ', ' + key[1]);*/

});