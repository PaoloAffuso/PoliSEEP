var frm = $("#frmInsertNewQuiz");

			frm.submit(function (e) {
			e.preventDefault();

			$.ajax({
				type: frm.attr("method"),
				url: frm.attr("action"),
				data: frm.serialize(),
				success: function (data) {
				console.log(data)
				if (data == "ERRORE") alert("Wrong credentials. Please, try again.");
				else if (data == "OK") 
                {
                    alert("Quiz added!");
                    window.location.reload();
                }
				},
				error: function (data) {
				console.log("An error occurred.");
				console.log(data);
				},
			});
});

function send_quiz(num, id_corso)
{
	console.log("Ciao gaione morbido pezzo grosso");
	$.ajax({
		url: "../student/send_Quiz.php",
		type: "post",
		data : {'num': num, 'id_corso' : id_corso},
		success: function (response) 
		{
			if(response=="OK") {alert("Quiz sent."); window.location.reload();}
			else {alert("Ops! Something went wrong. Try again.");}
		}
	});
}