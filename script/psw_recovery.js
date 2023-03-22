import {db} from '../firebaseConfig.js';
import {ref, child, get, update, set, remove} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';
const dbRef = ref(db);

window.sendEmailWithCode = sendEmailWithCode;


function sendEmailWithCode()
{
    let email = document.getElementById("email").value.trim(); // recupero email utente dal form
    let username=email.split("@")[0].replace(".",""); // dalla mail ricavo l'username
    let codice = Math.floor((Math.random() * 9999) + 1000); // genero il codice (numero casuale compreso tra 1000 e 9999)

    get(child(dbRef, "UsersList/"+username)).then((snapshot) => {
        let r=ref(db, 'UsersList/'+username);
        update(r, { 
            codice: codice // aggiungo/modifico il campo codice a DB
        }).then(()=>{
            $.ajax({
                url: "../send_email.php",
                type: "post",
                data: {"codice": codice, "email": email},
                success: function(data){
                    if(data==="KO")
                    {
                        alert("We sent an e-mail with a PIN. ");
                        window.location.href= "forgotPSW_insertPin.html";
                    }
                    else
                    {
                        alert("Ops... something went wrong. Please, try again.");
                    }
                }
            });
        });
    });
}

