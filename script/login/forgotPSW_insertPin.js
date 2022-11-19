var frm = $("#frm");

frm.submit(function (e) {
  e.preventDefault();

  $.ajax({
    type: frm.attr("method"),
    url: frm.attr("action"),
    data: frm.serialize(),
    success: function (response) {
      if (response == "ERRORE") // l'utente deve re-inserire il PIN che gli era stato inviato via mail
	  {
		  alert("Wrong PIN. Enter the correct pin.");
		  //window.location = "./forgotPSW_.html";
	  }
      else window.location = "./forgotPSW_updatePassword.html"; // l'utente viene indirizzato alla pagina per il cambio password
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });
});