import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update, push} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL, deleteObject, listAll, getMetadata} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

window.submitQuiz=submitQuiz;

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");

window.onload = function(){
    let type = getLoggedType(username);
    if(type==="STU") window.location.href = "/poliseep/student/student.html"; //Pagina student ancora da fare


};

async function getLoggedType(username) {
    const snapshot=await get(query(ref(db, "UsersList"), orderByChild("email"), equalTo(email)));
    let type="";
    snapshot.forEach(element => {
        type=element.val().tipo;
    });
    return type;
}

function submitQuiz(){
    var quizName = document.getElementById("namequiz").value;
    var quizDesc = document.getElementById("descriptionquiz").value;
    //var quizChap = document.getElementById("namequiz").value;

    var container_div = document.getElementById('questions');
    console.log(container_div);
    var count = container_div.getElementsByTagName('section').length;
    console.log(count);
    console.log(quizName);
    console.log(quizDesc);

    for(let i=1; i<=count; i++){
        var question = document.querySelector("#d"+i+" .first2 #wrapper1 #question").value;
        var questionType = document.querySelector("#d"+i+" .first2 #wrapper2 #questiontype").value;
        console.log(question);
        console.log(questionType);
        switch(questionType)
        {
            case "radioquestion": 
                var nanswerssaq = document.querySelector("#d"+i+" #saq #nanswerssaq").value;
                var answers = []; // l'affermazione
                var checked = []; // indica se l'affermazione è corretta
                var explain = []; // indica perché l'affermazione è sbagliata

                for(let j=1; j<=nanswerssaq; j++){
                    console.log(document.querySelector("#d"+i+" #saq .vanswers #answer"+j).value);
                    answers[j-1] = document.querySelector("#d"+i+" #saq .vanswers #answer"+j).value;
                    checked[j-1] = document.querySelector("#d"+i+" #saq .vanswers #check"+j).checked;
                    explain[j-1] = document.querySelector("#d"+i+" #saq .vanswers #explain"+j).value;
                }
                console.log(answers);
                console.log(checked);
                console.log(explain);
            break;

            case "checkboxquestion": 
                console.log("2");
            break;

            case "rispaperta": 
                console.log("3");
            break;

            default:
                alert("Select the question type!");
            break;
        }
    }
}



