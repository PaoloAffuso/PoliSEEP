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
		else alert("Ops! Something went wrong. Try again.");
      	console.log(data)
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });
});

// ACCETTA/DECLINA RICHIESTA DI ISCRIZIONE 

var frm1 = $("#formADRequest");
var btn1 = $("#radioSendRequest");

frm1.submit(function (e) {
  e.preventDefault();

  var checkedVals = $('.cb:checkbox:checked').map(function() {
    return this.value;
  }).get();
  for(i=0;i<checkedVals.length;i++) 
  {
    console.log("FRm:"+frm.serialize())
    /*$.ajax({
      type: frm.attr("method"), // post
      url: frm.attr("action"), // dove deve andare (script php)
      data: frm.serialize(), // dati presi dal form
      success: function (data) { 
      if(data === "OK") alert("Request sent.");
      else alert("Ops! Something went wrong. Try again.");
          console.log(data)
      },
      error: function (data) {
        console.log("An error occurred.");
        console.log(data);
      },
    });*/

    console.log(checkedVals[i]);

  }


});

