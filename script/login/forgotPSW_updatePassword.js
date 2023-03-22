var frm = $("#frm");

frm.submit(function (e) {
  e.preventDefault();

  $.ajax({
    type: frm.attr("method"),
    url: frm.attr("action"),
    data: frm.serialize(),
    success: function (response) {
      if (response == "OK") 
	  {
		  alert("Successful password recovery. Redirect to login...");
		  window.location = "../../login/login.html"; /*PHP*/
	  }
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });
});