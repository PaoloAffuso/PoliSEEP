function acceptRequest() {
    var checkedVals = $('.cb:checkbox:checked').map(function() {
        return this.value;
      }).get();
    for(var i=0;i<checkedVals.length;i++) {
        $.ajax({
            url: "../teacher/course_request.php",
            type: "post",
            data : {'call':'acceptRequest', 'id_studente': checkedVals[i]},
            success: function (response) {
              if(response==="OK") {
                alert(checkedVals.length+" richieste accettate");
              }
              else console.log(response);
            }
          });
    }
    location.reload();
}

function declineRequest() {
    var checkedVals = $('.cb:checkbox:checked').map(function() {
        return this.value;
      }).get();
    for(var i=0;i<checkedVals.length;i++) {
        $.ajax({
            url: "../teacher/course_request.php",
            type: "post",
            data : {'call':'declineRequest', 'id_studente': checkedVals[i]},
            success: function (response) {
              if(response==="OK") {
                alert(checkedVals.length+" richieste rifiutate");
              }
              else console.log(response);
            }
          });
    }
    //location.reload();
}