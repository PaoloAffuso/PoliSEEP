// Changing the style of scroll bar
// window.onscroll = function() {myFunction()};

// function myFunction() {
// 	var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
// 	var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
// 	var scrolled = (winScroll / height) * 100;
// 	document.getElementById("myBar").style.width = scrolled + "%";
// }


//funzione per la parte di ABOUT
function scrollAppear() {
  var introText = document.querySelector('.side-text');
  var sideImage = document.querySelector('.sideImage');
  var introPosition = introText.getBoundingClientRect().top;
  var imagePosition = sideImage.getBoundingClientRect().top;

  var screenPosition = window.innerHeight / 1.2;

  if(introPosition < screenPosition) {
    introText.classList.add('side-text-appear');
  }
  if(imagePosition < screenPosition) {
    sideImage.classList.add('sideImage-appear');
  }
}

window.addEventListener('scroll', scrollAppear);


//menù laterale
function sideMenu(side) {
  var menu = document.getElementById('side-menu');
  if(side==0) {
    menu.style = 'transform: translateX(0vh); position:fixed;';
  }
  else {
    menu.style = 'transform: translateX(-100%);';
  }
  side++;
}





// For switching between navigation menus in mobile mode
var i = 2;
function switchTAB() {
	var x = document.getElementById("list-switch");
	if(i%2 == 0) {
		document.getElementById("list-switch").style= "display: grid; height: 50vh; margin-left: 5%;";
		document.getElementById("search-switch").style= "display: block; margin-left: 5%;";
	}else {
		document.getElementById("list-switch").style= "display: none;";
		document.getElementById("search-switch").style= "display: none;";
	}
	i++;
}

// For LOGIN
var x = document.getElementById("login");
var y = document.getElementById("register");
var z = document.getElementById("btn");
var a = document.getElementById("log");
var b = document.getElementById("reg");
var w = document.getElementById("other");

function register() {
  x.style.left = "-400px";
  y.style.left = "50px";
  z.style.left = "110px";
  w.style.visibility = "hidden";
  b.style.color = "#fff";
  a.style.color = "#000";
}

function login() {
  x.style.left = "50px";
  y.style.left = "450px";
  z.style.left = "0px";
  w.style.visibility = "visible";
  a.style.color = "#fff";
  b.style.color = "#000";
}

// CheckBox Function
function goFurther(){
  if (document.getElementById("chkAgree").checked == true) {
    document.getElementById('btnSubmit').style = 'background: linear-gradient(to right, #FA4B37, #DF2771);';
  }
  else{
    document.getElementById('btnSubmit').style = 'background: lightgray;';
  }
}

function google() {
  	window.location.assign("https://accounts.google.com/signin/v2/identifier?service=accountsettings&continue=https%3A%2F%2Fmyaccount.google.com%2F%3Futm_source%3Dsign_in_no_continue&csig=AF-SEnbZHbi77CbAiuHE%3A1585466693&flowName=GlifWebSignIn&flowEntry=AddSession", "_blank");
}


// QUIZ Page
function quiz_teach(frame) {

  var children = document.getElementById("quiz-container").children; 
  for (var i = 0, len = children.length ; i < len; i++) 
  {
    document.getElementById(children[i].id).style='display: none;'
    console.log(children[i].id);
  }

  if(frame == 1) {
    document.getElementById('f1').style = 'display: block';
    document.getElementById('deleteButton').style.display = "none";
    //document.getElementById('addButton').style.display = "none";
  }

  else if(document.getElementById('frame' + frame).src == "about:blank") {
    document.getElementById('f' + frame).style = 'display: none';
    document.getElementById('deleteButton').style.display = "none";
    document.getElementById('addButton').style.display = "block";
    document.getElementById('quiz_non_creato').style.display = "block";
  }

  else if(document.getElementById('frame' + frame).src != "") {
    document.getElementById('f' + frame).style = 'display: block';
    //document.getElementById('addButton').style.display = "none";
    document.getElementById('deleteButton').style.display = "block";
    document.getElementById('quiz_non_creato').style.display = "none";
  }
  else alert('error');
}

// QUIZ Page
function quiz_stud(frame) {
  document.getElementById('f1').style='display: none;';
  document.getElementById('f2').style='display: none;';
  document.getElementById('f3').style='display: none;';
  document.getElementById('f4').style='display: none;';
  document.getElementById('f5').style='display: none;';
  document.getElementById('f6').style='display: none;';
  if(frame == 1) document.getElementById('f1').style = 'display: block';
  else if(frame == 2) document.getElementById('f2').style = 'display: block';
  else if(frame == 3) document.getElementById('f3').style = 'display: block';
  else if(frame == 4) document.getElementById('f4').style = 'display: block';
  else if(frame == 5) document.getElementById('f5').style = 'display: block';
  else if(frame == 6) document.getElementById('f6').style = 'display: block';
  else alert('error');
}


function startquiz() {
  document.getElementById('title').style = 'display: none;';

  document.getElementById('panel').style = 'display: inline-flex;';
  document.getElementById('left').style = 'display: block;';
  document.getElementById('right').style = 'display: block;';
}
function searchdisplay() {
  document.getElementById('searchpanel').style.display="block";
}

function display(n) {
  var img1 = document.getElementById('img1');
  var img2 = document.getElementById('img2');
  var img3 = document.getElementById('img3');
  var img4 = document.getElementById('img4');
  var s1 = document.getElementById('s1');
  var s2 = document.getElementById('s2');
  var s3 = document.getElementById('s3');
  var s4 = document.getElementById('s4');

  img1.style = 'display: none;';
  img2.style = 'display: none;';
  img3.style = 'display: none;';
  img4.style = 'display: none;';
  s1.style = 'background: #DF2771; color: #FFF;';
  s2.style = 'background: #DF2771; color: #FFF;';
  s3.style = 'background: #DF2771; color: #FFF;';
  s4.style = 'background: #DF2771; color: #FFF;';

  if(n==1) {
    img1.style = 'display: block;';
    s1.style = 'background: #E5E8EF; color: #DF2771;';
  }
  if(n==2) {
    img2.style = 'display: block;';
    s2.style = 'background: #E5E8EF; color: #DF2771;';
  }
  if(n==3) {
    img3.style = 'display: block;';
    s3.style = 'background: #E5E8EF; color: #DF2771;';
  }
  if(n==4) {
    img4.style = 'display: block;';
    s4.style = 'background: #E5E8EF; color: #DF2771;';
  }
}

//Ricerca chat
function search(ele) {
  if(event.key === 'Enter') {
    doSearch(ele.value);        
  }
}

function doSearch(search){
  $('#users-list').empty();
  $.ajax({
    url: "../doSearch.php",
    type: "post",
    data : {'search':search, 'type': 'STU'},
    success: function (response) {
      $('#users-list').append(response);
    }
  });
}

$(document).ready(function () {
  $("#searchBTN").click(function(){
    doSearch(document.getElementById('searchInput').value)
  });

  $("#searchInput").keyup(function() {
    //Se la barra di ricerca è vuota, mostra la lista originale
    if (!this.value) {
        $('#users-list').load(document.URL +  ' #users-list');
    }
  });

  var frm = $("#FrmSendMsg");

var frm_data = new FormData();

frm.submit(function (e) {
  e.preventDefault();

  frm_data.append("messaggio", $("input[name=messaggio]").val());
  frm_data.append("idStudenteHidden", $("input[name=idStudenteHidden]").val());
  frm_data.append("type", "DOC");
   
  $.ajax({
    url: "../send_message.php",
    type: "post",
    cache: false,
    contentType: false,
    processData: false,
    data: frm_data,
    success: function (data) {
      console.log(data)
      $('#chat-outgoing').append(data);
    },
    error: function (data) {
      console.log("An error occurred.");
      console.log(data);
    },
  });

  /*for (var key of frm_data.entries()) 
    console.log(key[0] + ', ' + key[1]);*/

});

});




