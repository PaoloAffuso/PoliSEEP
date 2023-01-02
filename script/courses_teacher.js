import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");

window.onload = function(){
    let type = getLoggedType(username);
    if(type==="STU") window.location.href = "/poliseep/student/student.html"; //Pagina student ancora da fare

    let get_str = window.location.search.substring(1);

    document.getElementById("href_file").href+="?"+get_str;
    printData(username, getCourseName(get_str));
    updatePic();
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

async function getLoggedType(username) {
    const snapshot=await get(query(ref(db, "UsersList"), orderByChild("email"), equalTo(email)));
    let type="";
    snapshot.forEach(element => {
        type=element.val().tipo;
    });
    return type;
}

function getCourseName(str) {
    return str.split("=")[1];
}