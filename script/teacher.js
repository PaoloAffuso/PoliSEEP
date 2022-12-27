import {db} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';

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
document.getElementById('form_course').addEventListener("click", function() {
    
    /*let course_name=document.getElementById('course_name');
    let cfu=document.getElementById('cfu');
    let professor=document.getElementById('professor');
    let course_goals=document.getElementById('course_goals');
    let num_ch=document.getElementById('num_ch');
    let brief_description=document.getElementById('brief_description');
    let learning_verification=document.getElementById('learning_verification');
    //let upload_btn=document.getElementById('upload_btn');*/
    let email = localStorage.getItem("email"); 
    let username=email.split("@")[0].replace(".","");
    let r=ref(db, 'UsersList/'+username+"/prova");
    let newPostRef=set(r, {
        bla: "bla"
    });
    console.log(username);
});