function Controllo() {
    var q=document.getElementById("quiz1").value;
    if (q.length == 0)
    {
       window.alert("Quiz not present, you have to add it!");
       document.getElementById("deleteButton").style.display = none;
       document.getElementbyId("addButton").style.display = block;
    }
    else
    {
       document.getElementbyId("addButton").style.display = none;
       document.getElementById("delete_button").style.display = block;
    }
    }