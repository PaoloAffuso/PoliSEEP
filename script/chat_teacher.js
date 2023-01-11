import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, onValue, push, query, orderByChild, equalTo, limitToLast} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");
let get_str = window.location.search.substring(1);
let course_name=getCourseName(get_str);
let initialState=true; //Per sincronizzare la ricezione messaggi
let student="amezzina3"; //Dummy - deve essere dall'elenco chat docente. onclick cambia username_student all'interno del local storage

window.onload = function(){
    let type = getLoggedType(username);
    if(type==="STU") window.location.href = "/poliseep/student/student.html";
    
};

//Aggiorna lista messaggi studente realtime
onValue(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student), async ()=> {
    if(!initialState) {
        const snapshot=await get(query(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student), limitToLast(1)));
        snapshot.forEach(element => {
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
                        <img src="../images/student_/usrimg.png" alt="">
                        <div class="details">
                            <p>${element.val().message}</p>
                        </div>
                    </div>
                `;
            }
            
        });
        
    }
    else initialState=false;
    
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
        let inputVal=document.getElementById("message_box").value;
        console.log(inputVal)
        let r=ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student);

        push(r, {
            message: inputVal,
            sender: username,
            timestamp: Date.now()
        }).then(() => {
            document.getElementById("message_box").value="";
        });
    }
});