import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL, deleteObject, listAll, getMetadata} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

window.openCourseModal=openCourseModal;

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
    updatePic();
    getTasks(username);
    pendingNum(username);
    getCountCourses();
    showCourses();
    showYourCourses(username);
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
        let pend = element.val().checked==="true"?"":"pending";
        let liTag = ` <li class="list `+pend+`" id="li-`+element.val().task_name+`" val="`+element.val().task_name+`">
          <input id="`+element.val().task_name+`" val="`+element.val().task_name+`" type="checkbox" `+chk+`/>
          <span class="task" onclick="handleStatus(getElementById('`+element.val().task_name+`'))">`+element.val().task_name+`</span>
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

async function showCourses() {
    const snapshot=await get(query(ref(db, "Courses")));
    snapshot.forEach(element => {
        get(child(dbRef, "Courses/"+element.val().course_name+"/Student/"+username)).then((snapshot) => {
            if(!snapshot.exists()){
                getDownloadURL(sRef(storage, element.val().img_url)).then((url) => {
                    //All'interno della onclick non posso passare gli ' perche' riconosciuti come fine funzione onclick. Ex. onclick('l'altro giorno') -> ci sono 3 '
                    let course_name = element.val().course_name.replace(new RegExp("'", "g"), "-");
                    document.getElementById("ccardbox").innerHTML += `
                    <div class="dcard" onclick="openCourseModal('`+course_name+`')" type="button">
                            <div class="fpart"><img src="`+url+`"></div>
                            <a><div class="spart">`+element.val().course_name+`</div></a>
                    </div>
                    `;
                });
            }
        });
    }); 
}

async function showYourCourses(username) {
    const snapshot=await get(query(ref(db, "UsersList/"+username+"/Courses")));

    console.log(username);
    console.log(snapshot);

    snapshot.forEach(element => {

        console.log(element.val().course_name);

        document.getElementById("ccardbox1").innerHTML += `
            <div class="dcard">
                <a href="courses_student.html?course_name=`+element.val().course_name+`">
                    <div class="fpart"><img src=""></div>
                    <div class="spart">`+element.val().course_name+`</div>
                </a>
            </div>
            `;

        /*
        getDownloadURL(sRef(storage, element.val().img_url)).then((url) => {
           
            document.getElementById("ccardbox1").innerHTML += `
            <div class="dcard">
                <a href="courses_student.html?course_name=`+element.val().course_name+`">
                    <div class="fpart"><img src="`+url+`"></div>
                    <div class="spart">`+element.val().course_name+`</div>
                </a>
            </div>
            `;
        });
        */
    }); 
}

async function getCountCourses() {
    get(child(dbRef, "Courses")).then((snapshot) => {
        let count=0;
        snapshot.forEach(function() {
            count++;
        });
        //La classe di your_courses deve essere unica. Il 0 sta perchè è univoca. Va fatto perché il css è stilizzato in base all'id
        document.getElementById("available_courses").innerHTML = count;
    });
}

async function openCourseModal(course_name) {
    document.getElementById("listprof").innerHTML = '';
    toggleModal();
    //Riconverto il nome del corso sostituendo i - con '
    course_name = course_name.replace(new RegExp("-", "g"), "'");
    let i=1; //Importante per la selezione della checkbox
    const snapshot=await get(query(ref(db, "Courses/"+course_name+"/Professor")));
    snapshot.forEach(element => {
        let prof = element.val().professor;
        //console.log(element.val().professor);
        document.getElementById("listprof").innerHTML += `
            <input class = "cb" courseName="${course_name}" listProf="checkbox" type="checkbox" id="prof${i}" name="prof${i}" value="${prof}" email="${element.val().email}" onchange="cbChange(this)">
            <label for="prof${i}"> ${prof}</label>
            `;
        i++;
    }); 
}

/*--*/
document.getElementById('btnSendRequest').addEventListener("click", function(){
    var checkedBoxes = document.querySelectorAll('input[listProf=checkbox]:checked');
    let teacher=checkedBoxes[0].getAttribute("email").split("@")[0].replace(".","");
    let r=ref(db, 'UsersList/'+teacher+"/Courses/"+checkedBoxes[0].getAttribute("courseName")+"/Pending/"+username);

    set(r, {
        student: username,
        email: email
    });/*.then(()=>{
        r=ref(db, 'Courses/'+checkedBoxes[0].getAttribute("courseName")+'/Student/'+username);
        set(r, {
            student: username,
            email: email
        });
    });*/

    alert("Request sent.");

    toggleModal();
});

/*------------------CAMBIO IMMAGINE PROFILO-----------*/
document.getElementById('file').addEventListener("change", function(e) {
    let img = e.target.files[0];
    const storo = sRef(storage, 'Profile/'+username+"/"+img.name); 

    uploadBytes(storo, img).then(() => {
        let r=ref(db, 'UsersList/'+username);
        update(r, {
            profile_pic: 'Profile/'+username+"/"+img.name
        });
    });
});

async function updatePic() {
    const snapshot=await get(query(ref(db, "UsersList"), orderByChild("email"), equalTo(email)));
    let path="";
    snapshot.forEach(element => {
        path=element.val().profile_pic;
    });

    let img = sRef(storage, path);
    getDownloadURL(img).then((url) => {
        document.getElementById("photo").src=url;
        $( "#photo" ).load(window.location.href + " #photo" );
    });
}