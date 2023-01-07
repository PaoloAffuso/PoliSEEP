import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update, remove} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");

window.onload = function(){
    let type = getLoggedType(username);
    if(type==="DOC") window.location.href = "/poliseep/teacher/teacher.html";

    printData(username, getCourseName(get_str));
};

async function printData(username, course_name) {
    get(child(dbRef, "UsersList/"+username+"/Courses/"+course_name)).then((snapshot) => {
        document.getElementById("course_name").innerHTML = capitalize(snapshot.val().course_name);
        document.getElementById("cfu").innerHTML = snapshot.val().cfu;
        document.getElementById("professor").innerHTML = capitalize(snapshot.val().professor);
        if(snapshot.val().course_goals==="")
            document.getElementById("course_goals").innerHTML = "Course goals for this course are still not available.";
        else
            document.getElementById("course_goals").innerHTML = snapshot.val().course_goals;
        
        if(snapshot.val().brief_description==="")
            document.getElementById("course_desc").innerHTML = "Description for this course is still not available.";
        else
            document.getElementById("course_desc").innerHTML = snapshot.val().brief_description;

        getDownloadURL(sRef(storage, snapshot.val().img_url)).then((url) => {
            document.getElementById("img_url").src = url;
        });    

        if(snapshot.val().learning_verification==="")
            document.getElementById("learning_verification").innerHTML = "Learning verification for this course is still not available.";
        else
            document.getElementById("learning_verification").innerHTML = snapshot.val().learning_verification;
        
    });
}