import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, onValue, push, query, orderByChild, equalTo, limitToLast} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {ref as sRef, getDownloadURL, uploadBytes} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

window.changeStudent=changeStudent;
window.sendAttachments=sendAttachments;

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

    let get_str = window.location.search.substring(1);
    document.getElementById("href_file").href+="?"+get_str;
    document.getElementById("href_courses").href+="?"+get_str;
    document.getElementById("href_quiz").href+="?"+get_str;
    document.getElementById("url_file").href+="?"+get_str;
    document.getElementById("url_courses").href+="?"+get_str;
    document.getElementById("url_quiz").href+="?"+get_str;
    
    getChats();

    if(student!==null) {
        let img_path = await getStudentPic(student);
        let student_name = await getStudentName(student);
        getDownloadURL(sRef(storage, img_path)).then((url) => {
            document.getElementById("firstpart").innerHTML = `
            <img src="${url}" alt="">
            <div class="details">
                <span>${student_name}</span>
                <p></p>
            </div>
            `;
        });
        const snapshot=await get(query(ref(db, "Courses/"+course_name+"/Professor/"+username+"/Chat/"+student+"/Messages"), orderByChild("timestamp")));

        getDownloadURL(sRef(storage, img_path)).then(async (url) => {
            //snapshot.forEach(async (element)=>{
            for(let message in snapshot.val()) {
                await get(child(dbRef, "Courses/"+course_name+"/Professor/"+username+"/Chat/"+student+"/Messages/"+message)).then(async (element) => {
                    if(element.val().sender===username) {
                        if(element.val().type == "allegato") {
                            var path = element.val().message;
                            var messaggio = path.split("/").pop();
                            await getDownloadURL(sRef(storage, path)).then((url) => {
                                document.getElementById("chat-box").innerHTML+=`
                                    <div class="chat outgoing">
                                        <div class="details">
                                            <a href="${url}" target="_blank"><p>${messaggio}</p></a>
                                        </div>
                                    </div>
                                `;
                            });
                        }
                        else {
                            document.getElementById("chat-box").innerHTML+=`
                                <div class="chat outgoing">
                                    <div class="details">
                                        <p>${element.val().message}</p>
                                    </div>
                                </div>
                            `;
                        }
                    } else {
                        if(element.val().type == "allegato") {
                            var path = element.val().message;
                            var messaggio = path.split("/").pop();
                            await getDownloadURL(sRef(storage, path)).then((url_file) => {
                                document.getElementById("chat-box").innerHTML+=`
                                    <div class="chat incoming">
                                        <img src="${url}" alt="">
                                        <div class="details">
                                            <a href="${url_file}" target="_blank"><p>${messaggio}</p></a>
                                        </div>
                                    </div>
                                `;
                            });
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
                        
                    }
                });
            }//);
        });

        
    }
};


//Aggiorna lista messaggi studente realtime
onValue(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student), async ()=> {
    if(!initialState) {
        const snapshot=await get(query(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student+"/Messages"), limitToLast(1)));
        snapshot.forEach(async element => {
            if(element.val().sender===username) {
                if(element.val().type == "allegato") {
                    var path = element.val().message;
                    var messaggio = path.split("/").pop();
                    getDownloadURL(sRef(storage, path)).then((url) => {
                        document.getElementById("chat-box").innerHTML+=`
                            <div class="chat outgoing">
                                <div class="details">
                                    <a href="${url}" target="_blank"><p>${messaggio}</p></a>
                                </div>
                            </div>
                        `;
                    });
                }
                else {
                    document.getElementById("chat-box").innerHTML+=`
                        <div class="chat outgoing">
                            <div class="details">
                                <p>${element.val().message}</p>
                            </div>
                        </div>
                    `;
                }
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
    if (event.key === 'Enter' && document.getElementById("message_box").value!="") {
        await sendMessage();
    }
});

document.getElementById("searchInput").addEventListener("keyup", async function(event) {
    let inputVal=document.getElementById("searchInput").value;
    if(inputVal==="") {
        document.getElementById("users-list").innerHTML="";
        getChats();
    }
    if (event.key === 'Enter') {
        inputVal=document.getElementById("searchInput").value;
        const snapshot=await get(query(ref(db, 'Courses/'+course_name+'/Professor/'+username+'/Chat')));
        snapshot.forEach(element=>{
            if(element.val().email.includes(inputVal) || element.val().fullname.toLowerCase().includes(inputVal.toLowerCase())){
                document.getElementById("users-list").innerHTML="";
                let username=element.val().email.split("@")[0].replace(".","");
                getFilteredChat(username);
            }
               
        });
    }
});


document.getElementById("send_btn").addEventListener("click", async function(){
    if(document.getElementById("message_box").value!=""){
        await sendMessage();
    }

});


async function sendMessage() {
    let inputVal=document.getElementById("message_box").value;
    let r=ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student+"/Messages");

    push(r, {
        message: inputVal,
        sender: username,
        timestamp: Date.now()
    }).then(() => {
        document.getElementById("message_box").value="";
    });
}

$(document).ready(function() {
    $(window).keydown(function(event){
      if(event.keyCode == 13) {
        event.preventDefault();
        return false;
      }
    });
});

document.getElementById("uploadFile").addEventListener("change", async function(e) {
    let doc = e.target.files[0];
    doc = renameFile(doc, doc.name + Date.now());
    console.log(doc.name);
    
    const storo = sRef(storage, 'Chat/'+username+"/"+doc.name);
    await uploadBytes(storo, doc).then(async () => {
        let r=ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+student+"/Messages");
        await push(r, {
            message: "Chat/"+username+"/"+doc.name,
            sender: username,
            timestamp: Date.now(),
            type: "allegato"
        });
    });
});

function renameFile(originalFile, newName) {
    return new File([originalFile], newName, {
        type: originalFile.type,
        lastModified: originalFile.lastModified,
    });
}

function sendAttachments(){
    document.getElementById("uploadFile").click();
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
                    const q=await get(query(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+stud+"/Messages"), limitToLast(1)));
                    q.forEach((message)=>{
                        if(message.val().type == "allegato") {
                            document.getElementById("users-list").innerHTML+=`
                            <a href="#" onclick="changeStudent('${stud}')">
                                <div class="content">
                                    <img src="${url}" alt="">
                                    <div class="details">
                                        <span>${snapshot.val().fullname}</span>
                                        <p>${message.val().message.split("/").pop()}</p>
                                    </div>
                                </div>
                                <div class="new-messages">
                                    <p>1</p>
                                </div>
                            </a>
                            `;
                        } else {
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
                        }
                    });
                }); 
            });
        }
    });
}

async function getFilteredChat(stud){
    get(child(dbRef, "UsersList/"+stud)).then(async (snapshot) => {
        let img_path = snapshot.val().profile_pic;
        getDownloadURL(sRef(storage, img_path)).then(async (url) => {
            const q=await get(query(ref(db, 'Courses/'+course_name+"/Professor/"+username+"/Chat/"+stud+"/Messages"), limitToLast(1)));
            q.forEach((message)=>{
                if(message.val().type == "allegato") {
                    document.getElementById("users-list").innerHTML+=`
                    <a href="#" onclick="changeStudent('${stud}')">
                        <div class="content">
                            <img src="${url}" alt="">
                            <div class="details">
                                <span>${snapshot.val().fullname}</span>
                                <p>${message.val().message.split("/").pop()}</p>
                            </div>
                        </div>
                        <div class="new-messages">
                            <p>1</p>
                        </div>
                    </a>
                    `;
                } else {
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
                }
            });
        }); 
    });
}

function changeStudent(student) {
    localStorage.setItem('student', student);
    location.reload();
}