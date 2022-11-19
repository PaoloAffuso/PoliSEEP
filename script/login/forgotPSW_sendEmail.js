var frm = $("#frm");

frm.submit(function (e) {
  e.preventDefault();

  $.ajax({
    type: frm.attr("method"),
    url: frm.attr("action"),
    data: frm.serialize(),
    success: function (response) {
      if (response == "ERRORE") 
	  {
		  alert("E-mail does not exist. Enter a valid e-mail.");
	  }
      else window.location = "../login/forgotPSW_insertPin.html"; 
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });
});