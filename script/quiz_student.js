import {db} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, remove} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';

window.viewSingleQuiz = viewSingleQuiz;
window.viewCorrectedQuiz = viewCorrectedQuiz;
window.submitQuiz = submitQuiz;
window.retakeQuiz = retakeQuiz;

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
    if(type==="DOC") window.location.href = "/poliseep/teacher/teacher.html"; //Pagina student ancora da fare

    document.getElementById("href_file").href+="?"+get_str;
    document.getElementById("href_chat").href+="?"+get_str;
    document.getElementById("href_info").href+="?"+get_str;

    getQuizList();
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

async function getQuizList(){
    //Prima recupero il docente della materia, poi stampo la lista
    await getTeacher().then((teacher)=> {
        get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz")).then(async (snapshot) => {
            var count=1;
            for(let quiz in snapshot.val()) {
                await get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/Question 1/"+username)).then((snap1) => {
                    if(snap1.exists()) {
                        document.getElementById("ul_left").innerHTML+=`<li
                        onclick="viewCorrectedQuiz('${quiz}', '${teacher}', 'quiz${count}')" id="quiz${count}">
                        ${quiz}</li>`;
                    } else {
                        document.getElementById("ul_left").innerHTML+=`<li
                        onclick="viewSingleQuiz('${quiz}', '${teacher}')" id="quiz${count}">
                        ${quiz}
                        <div class="new-quiz">
                            <p>NEW</p>
                        </div></li>`;
                    }
                });
                count++;
            }
        });
    })
}

async function getTeacher() {
    const snapshot=await get(query(ref(db, "UsersList/"+username+"/Courses/"+course_name)));
    return snapshot.val().teacher;
}

//Quiz effettuato dall'utente. Visualizza anche le risposte
function viewCorrectedQuiz(quiz, teacher, id) {
    document.getElementById('quiz3').style.display='block'; document.getElementById('noquiz').style.display='none';
    document.querySelector("#quiz3 .container").innerHTML="";
    document.querySelector("#quiz3 .container").innerHTML+=`<button class="send-button" onclick="retakeQuiz('${quiz}','${teacher}', '${id}')">Retake quiz</button>`;

    get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz)).then((snapshot) => {
        for(let question in snapshot.val()) {
            if(question.includes("Question")) {
                let number = question.split(" ")[1];
                var questionType="";
                get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question)).then((snap) => {
                    questionType=snap.val().questionType;
                    document.querySelector("#quiz3 .container").innerHTML+=`
                            <section id="p${number}">
                                <h3>${number} - ${snap.val().question}</h3>
                            </section>
                        `;
                    
                    for(let answer in snap.val()) {
                        if(answer.includes("Answer")) {
                            get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+answer)).then((snap1) => {
                                if(snap1.val().checked===false) {
                                    get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+username+"/"+answer)).then((snap2) => {
                                        if(snap2.exists()) {
                                            document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                                <label class="wrong_label">
                                                    Given answer: ${snap1.val().answer}
                                                    <br><br>Explanation: ${snap1.val().explain}
                                                </label>
                                            `;
                                        } else {
                                            document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                                <label class="wrong_label">
                                                    ${snap1.val().answer}
                                                    <br><br>Explanation: ${snap1.val().explain}
                                                </label>
                                            `;
                                        }
                                    });
                                    
                                } else {
                                    get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+username+"/"+answer)).then((snap2) => {
                                        if(snap2.exists()) {
                                            document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                                <label class="right_label">
                                                    Given answer: ${snap1.val().answer}
                                                </label>
                                            `;
                                        } else {
                                            document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                                <label class="right_label">
                                                    ${snap1.val().answer}
                                                </label>
                                            `;
                                        }
                                    });
                                }
                            });
                        }
                    }
                });
                /*
                get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+username)).then((snap) => {
                    for(let studentAnswer in snap.val()) {
                        get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+studentAnswer)).then((snap1) => {
                            console.log(snap1.val().explain);
                            if(snap1.val().checked===false) {
                                document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                        <label class="wrong_label">
                                            ${snap1.val().answer}
                                            <br><br>Explanation: ${snap1.val().explain}
                                        </label>
                                    `;
                            } else {
                                document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                        <label class="right_label">
                                            ${snap1.val().answer}
                                        </label>
                                    `;
                            }
                        });
                    }
                });*/
            }
        }
    });
}

//Quiz non ancora effettuato quindi "pulito"
function viewSingleQuiz(quiz, teacher){
    document.getElementById('quiz3').style.display='block'; document.getElementById('noquiz').style.display='none';
    document.querySelector("#quiz3 .container").innerHTML="";
    document.querySelector("#quiz3 .container").innerHTML+=`<button class="send-button" onclick="submitQuiz('${quiz}','${teacher}')">Send</button>`;
    get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz)).then((snapshot) => {
        document.getElementById("quiz_name").innerHTML=snapshot.val().quiz_name;
        document.getElementById("quiz_desc").innerHTML=snapshot.val().quiz_desc;
        for(let question in snapshot.val()) {
            if(question.includes("Question")) { //Filtro solo le domande
                let number = question.split(" ")[1];
                let questionType = "";
                get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question)).then((snap) => {
                    questionType=snap.val().questionType;
                    document.querySelector("#quiz3 .container").innerHTML+=`
                        <section id="p${number}">
                            <h3>${number} - ${snap.val().question}</h3>
                        </section>
                    `;

                    for(let answer in snap.val()) {
                        if(answer.includes("Answer")) {
                            get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+answer)).then((snap1) => {
                                if(questionType==="radioquestion") {
                                    document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                        <label>
                                            <input type="radio" value="${answer.split(" ")[1]}" name="p1" id="a${answer.split(" ")[1]}">${snap1.val().answer}
                                        </label>
                                    `;
                                } else if(questionType==="checkboxquestion") {
                                    document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                        <label>
                                            <input type="checkbox" value="${snap1.val().answer}" name="p${answer.split(" ")[1]}" id="a${answer.split(" ")[1]}">${snap1.val().answer}
                                        </label>
                                    `;
                                } else {
                                    document.querySelector("#quiz3 .container #p"+number).innerHTML+=`
                                        <label>
                                            <textarea rows="5" placeholder="You can type here your answer" id="a${answer.split(" ")[1]}">${snap1.val().answer}</textarea>
                                        </label>
                                    `;
                                }
                            });
                        }
                    }
                });
            }
        }
    });
}

async function submitQuiz(quiz, teacher) {
    var container_div = document.getElementById('container_div');
    var count = container_div.getElementsByTagName('section').length;
    for(let i=1;i<=count;i++) {
        var label = document.querySelector("#quiz3 .container #p"+i).getElementsByTagName('label').length;
        for(let l=1;l<=label;l++) {
            var answer = document.querySelector("#quiz3 .container #p"+i+" #a"+l);
            if(answer.checked) {
                await get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/Question "+i+"/"+username+"/")).then(async (snapshot) => {
                    let r=await ref(db, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/Question "+i+"/"+username+"/Answer "+l);
                    await set(r, {
                        answer: answer.value,
                    });
                });
            }
        }
    }
    alert("Quiz submitted");
    window.location.reload();
}

function retakeQuiz(quiz, teacher, id) {
    get(child(dbRef, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz)).then(async (snapshot) => {
        for(let question in snapshot.val()) {
            if(question.includes("Question")) {
                let r = ref(db, "UsersList/"+teacher+"/Courses/"+course_name+"/Quiz/"+quiz+"/"+question+"/"+username);
                await remove(r);
            }
        }
    }).then(()=>{
        alert('Now you can retake the quiz "'+quiz+'"');
        //document.getElementById(id).click(); Non funziona - serve il refresh
        window.location.reload();
    });
}