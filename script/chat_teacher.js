import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, onValue, push, query, orderByChild, equalTo, limitToLast} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {ref as sRef, getDownloadURL} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

window.changeStudent=changeStudent;

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");
let get_str = window.location.search.substring(1);
let course_name=getCourseName(get_str);
let initialState=true; //Per sincronizzare la ricezione messaggi
let initChats=true; //Per sincronizzare l'elenco chat
//let student="amezzina3"; //Dummy - deve essere dall'elenco chat docente. onclick cambia username_student all'interno del local storage
let student = localStorage.getItem("student");

window.onload = async function(){
    let type = getLoggedType(username);
    if(type==="STU") window.location.href = "/poliseep/student/student.html";
    
    getChats();

    if(student!==null) {
        let img_path = await getStudentPic(student);
        let student_name = await getStudentName(student);
        getDownloadURL(sRef(storage, img_path)).then((url) => {
            document.getElementById("firstpart").innerHTML = `
            <img src="${url}" alt="">
            <div class="details">
                <span>${student_name}</span>
                <p>Active now</p>
            </div>
            `;
        });
        const snapshot=await get(query(ref(db, "Courses/"+course_name+"/Professor/"+username+"/Chat/"+student), orderByChild("timestamp")));

        getDownloadURL(sRef(storage, img_path)).then((url) => {
            snapshot.forEach((element)=>{
                if(element.val().sender===username) {
                    document.getElementById("chat-box").innerHTML+=`
                        <div class="chat outgoing">
                            <div class="details">
                                <p>${element.val().message}</p>
                            </div>
                        </div>
                    `;
                } else {
                    document.getElementById("chat-box").innerHTML+=`
                        <div class="chat incoming">
                            <img src="${url}" alt="">
                            <div class="details">
                                <p>${element.val().message}</p>
                            </div>
                        </div>
                    `;
                }
            });
        });

        
    }

    
};

//Aggiorna lista messaggi studente realtime
onValue(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student), async ()=> {
    if(!initialState) {
        const snapshot=await get(query(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student), limitToLast(1)));
        snapshot.forEach(async element => {
            if(element.val().sender===username) {
                document.getElementById("chat-box").innerHTML+=`
                    <div class="chat outgoing">
                        <div class="details">
                            <p>${element.val().message}</p>
                        </div>
                    </div>
                `;
            } else {
                let img_path=await getStudentPic(student);
                getDownloadURL(sRef(storage, img_path)).then((url) => {
                    document.getElementById("chat-box").innerHTML+=`
                        <div class="chat incoming">
                            <img src="${url}" alt="">
                            <div class="details">
                                <p>${element.val().message}</p>
                            </div>
                        </div>
                    `;
                });
            }
            
        });
        
    }
    else initialState=false;
});

onValue(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat"), async ()=> {
    if(!initChats) {
        document.getElementById("users-list").innerHTML = "";
        getChats();
    }
    else initChats=false;
});


function getCourseName(str) {
    //Se il nome del corso contiene spazi, nell'url gli spazi saranno convertiti in %20 e gli ' con %27
    str=str.split("=")[1].replace(new RegExp("%20", "g"), ' ');
    str=str.replace(new RegExp("%27", "g"), "'");
    return str;
}

async function getLoggedType(username) {
    const snapshot=await get(query(ref(db, "UsersList"), orderByChild("email"), equalTo(email)));
    let type="";
    snapshot.forEach(element => {
        type=element.val().tipo;
    });
    return type;
}

document.getElementById("message_box").addEventListener("keyup", async function(event) {
    if (event.key === 'Enter') {
        await sendMessage();
    }
});

document.getElementById("send_btn").addEventListener("click", async function(){
    await sendMessage();
});

async function sendMessage()
{
    let inputVal=document.getElementById("message_box").value;
    let r=ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student);

    push(r, {
        message: inputVal,
        sender: username,
        timestamp: Date.now()
    }).then(() => {
        document.getElementById("message_box").value="";
    });
}

async function getStudentPic(student) {
    const snapshot=await get(query(ref(db, "UsersList/"+student)));
    return snapshot.val().profile_pic;
}

async function getStudentName(student) {
    const snapshot=await get(query(ref(db, "UsersList/"+student)));
    return snapshot.val().fullname;
}

async function getChats() {
    get(child(dbRef, "Courses/"+course_name+"/Professor/"+username+"/Chat")).then((snapshot) => {
        for(let stud in snapshot.val()) {
            get(child(dbRef, "UsersList/"+stud)).then(async (snapshot) => {
                let img_path = snapshot.val().profile_pic;
                getDownloadURL(sRef(storage, img_path)).then(async (url) => {
                    const q=await get(query(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+stud), limitToLast(1)));
                    q.forEach((message)=>{
                        document.getElementById("users-list").innerHTML+=`
                        <a href="#" onclick="changeStudent('${stud}')">
                            <div class="content">
                                <img src="${url}" alt="">
                                <div class="details">
                                    <span>${snapshot.val().fullname}</span>
                                    <p>${message.val().message}</p>
                                </div>
                            </div>
                            <div class="new-messages">
                                <p>1</p>
                            </div>
                        </a>
                        `;
                    });
                }); 
            });
        }
    });
}

function changeStudent(student) {
    localStorage.setItem('student', student);
    location.reload();
}