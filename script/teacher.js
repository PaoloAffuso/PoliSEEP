import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

const dbRef = ref(db);

window.onload = function(){
    let email = localStorage.getItem("email"); 
    setNameByMail(email);
};

async function prova(){
    const prova=await get(query(ref(db, "UsersList"), orderByChild("fullname"), equalTo("prova")));
    console.log(prova.val())
}

function setNameByMail(email) {
    let username=email.split("@")[0].replace(".","");
    
    get(child(dbRef, "UsersList/"+username)).then((snaphot) => {
        if(snaphot.exists()) {
            document.getElementById("username").innerHTML = snaphot.val().fullname;
        }
    });
}


/*-------------------NUOVO CORSO----------------------*/
let file=null;

function setFile(f) {
    file=f;
}

function getFile(){
    return file;
}

//Quando clicco su "Choose course image", cambia il riferimento del file
document.getElementById('upload_img_btn').addEventListener("change", function(e) {
    file=e.target.files[0];
    setFile(file);
});

document.getElementById('form_course').addEventListener("click", function() {
    let course_name=document.getElementById('course_name');                     //Mandatory
    let cfu=document.getElementById('cfu');                                     //Mandatory
    let professor=document.getElementById('professor');                         //Da prendere da localStorage
    let course_goals=document.getElementById('course_goals');                   //Optional
    let num_ch=document.getElementById('num_ch');                               //Optional
    let brief_description=document.getElementById('brief_description');         //Optional
    let learning_verification=document.getElementById('learning_verification'); //Optional
    let f=getFile();                                                            //Mandatory
    let email = localStorage.getItem("email"); 
    let username=email.split("@")[0].replace(".","");
    
    const storo = sRef(storage, 'CoursesImages/'+f.name);
    uploadBytes(storo, f);

    let r=ref(db, 'UsersList/'+username+"/Courses/"+course_name.value);
    let newPostRef=set(r, {
        course_name: course_name.value,
        cfu: cfu.value,
        professor: professor.value,
        course_goals: course_goals.value,
        num_ch: num_ch.value,
        brief_description: brief_description.value,
        learning_verification: learning_verification.value,
        img_url: "CoursesImages/"+f.name
    });
    toggleModal();
});