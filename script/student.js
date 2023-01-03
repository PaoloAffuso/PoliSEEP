import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL, deleteObject, listAll, getMetadata} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");

window.onload = function(){
    let type = getLoggedType(username);
    if(type==="DOC") window.location.href = "/poliseep/teacher/teacher.html";

    setFullname("username", email);
    getTasks(username);
    pendingNum(username);
};

async function setFullname(id, username){
    const snapshot=await get(query(ref(db, "UsersList"), orderByChild("email"), equalTo(email)));
    let name="";
    snapshot.forEach(element => {
        name=element.val().fullname;
    });
    name=capitalize(name);
    if(id==="username")
        document.getElementById(id).innerHTML = name;
    else if(id==="professor") {
        document.getElementById(id).disabled = true;
        document.getElementById(id).value = name;
    }
}

async function getLoggedType(username) {
    const snapshot=await get(query(ref(db, "UsersList"), orderByChild("email"), equalTo(email)));
    let type="";
    snapshot.forEach(element => {
        type=element.val().tipo;
    });
    return type;
}

async function getTasks(username) {
    let todoLists = document.querySelector(".todoLists");
    const snapshot=await get(query(ref(db, "UsersList/"+username+"/Tasks")));
    snapshot.forEach(element => {
        let chk = element.val().checked==="true"?"checked":"";
        let liTag = ` <li class="list pending">
          <input type="checkbox" `+chk+`/>
          <span class="task" onclick="handleStatus(this)" val="`+element.val().task_name+`">`+element.val().task_name+`</span>
          <i class="uil uil-trash" onclick="deleteTask(this)" val="`+element.val().task_name+`"></i>
        </li>`;

        todoLists.insertAdjacentHTML("beforeend", liTag);
    });
}

async function pendingNum(username) {
    get(child(dbRef, "UsersList/"+username+"/Tasks")).then((snapshot) => {
        let pending = document.querySelector(".pending-num")
        let count=0;
        snapshot.forEach(function(e) {
            if(e.val().checked==="false")
            count++;
        });
        pending.textContent = count === 0 ? "no" : count;
    });
}