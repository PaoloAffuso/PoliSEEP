// INVIO RICHIESTA DI ISCRIZIONE 

var frm = $("#formSendRequest");
var btn = $("#radioSendRequest");
//frm.append('id_corso', btn.val());

frm.submit(function (e) {
  e.preventDefault();

  $.ajax({
    type: frm.attr("method"), // post
    url: frm.attr("action"), // dove deve andare (script php)
    data: frm.serialize(), // dati presi dal form
    success: function (data) { 
		if(data === "OK") alert("Request sent.");
		else if(data=="DUPLICATE") alert("You are already subscribed to this course.");
    else alert("Ops! Something went wrong. Try again.");
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });
});

