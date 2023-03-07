import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update, push} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL, deleteObject, listAll, getMetadata} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

window.submitQuiz=submitQuiz;
let get_str = window.location.search.substring(1);
let course_name = getCourseName(get_str);

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

function getCourseName(str) {
    //Se il nome del corso contiene spazi, nell'url gli spazi saranno convertiti in %20 e gli ' con %27
    str=str.split("=")[1].replace(new RegExp("%20", "g"), ' ');
    str=str.replace(new RegExp("%27", "g"), "'");
    return str;
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

    for(let i=1; i<=count; i++){ // n° quesiti
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

                for(let j=1; j<=nanswerssaq; j++){ // n° affermazioni del quesito
                    console.log(document.querySelector("#d"+i+" #saq .vanswers #answer"+j).value);
                    answers[j-1] = document.querySelector("#d"+i+" #saq .vanswers #answer"+j).value;
                    checked[j-1] = document.querySelector("#d"+i+" #saq .vanswers #check"+j).checked;
                    explain[j-1] = document.querySelector("#d"+i+" #saq .vanswers #explain"+j).value;
                }
                console.log(answers);
                console.log(checked);
                console.log(explain);

                let capitolo = "capitolo x "+quizName; // dummy

                // chiamata al DB
                /*
                get(child(dbRef, 'UsersList/'+username+"/Courses/"+course_name+"/Quiz/"+capitolo)).then((snaphot) => {
                    let r=ref(db, 'UsersList/'+username+"/Courses/"+course_name+"/Quiz/"+capitolo);
                    set(r, {
                        quiz_name: quizName,
                        quiz_desc: quizDesc,
                        quiz_chapter: "1" // dummy
                    }).then(() => {
                        
                    });

                });*/
            break;

            case "checkboxquestion": 
                var nanswersmaq = document.querySelector("#d"+i+" #maq #nanswersmaq").value;
                var answers = []; // l'affermazione
                var checked = []; // indica se l'affermazione è corretta
                //checked = document.querySelectorAll("");
                var explain = []; // indica perché l'affermazione è sbagliata

                for(let j=1; j<=nanswersmaq; j++){ // n° affermazioni del quesito
                    console.log(document.querySelector("#d"+i+" #maq .vanswers #answer"+j).value);
                    answers[j-1] = document.querySelector("#d"+i+" #maq .vanswers #answer"+j).value;
                    checked[j-1] = document.querySelector("#d"+i+" #maq .vanswers #check"+j).checked;
                    explain[j-1] = document.querySelector("#d"+i+" #maq .vanswers #explain"+j).value;
                }
                console.log(answers);
                console.log(checked);
                console.log(explain);
            break;

            case "rispaperta": 
                var answers = document.querySelector("#d"+i+" #oq .explainopen #explain1").value;
                console.log(answers);
            break;

            default:
                alert("Select the question type!");
            break;
        }
    }
}



