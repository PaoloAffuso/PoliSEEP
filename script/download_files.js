import {db, storage} from '../firebaseConfig.js';
import {ref, child, get, query, equalTo, orderByChild, set, update, remove} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
import {uploadBytes, ref as sRef, getDownloadURL, deleteObject, listAll, getMetadata} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-storage.js';

if (localStorage.getItem("email") === null) {
    window.location.href = "/poliseep";
}

const dbRef = ref(db);
let email = localStorage.getItem("email"); 
let username=email.split("@")[0].replace(".","");
let currentDate = new Date();
let get_str = window.location.search.substring(1);

window.onload = function(){
    let type = getLoggedType(username);
    if(type==="DOC") window.location.href = "/poliseep/teacher/teacher.html";
    
    document.getElementById("href_info").href+="?"+get_str;
    showFiles(username);
};

async function showFiles(username) {
    let course_name = getCourseName(get_str);
    const snapshot=await get(query(ref(db, "UsersList/"+username+"/Courses/"+course_name+"/Documents")));
    snapshot.forEach(element => {
        getDownloadURL(sRef(storage, element.val().path)).then((url) => {
            document.getElementById("file_table").innerHTML += `
            <tr>
                <td><input type='checkbox' name='checkbox' class='item_id' option_id='`+element.val().id+`'> </td>
                <td>`+element.val().doc_name+`</td>
                <td>`+element.val().upload_date+`</td>
                <td>`+element.val().weight+" kB"+`</td>
            </tr>
            `;
        });
    }); 
}