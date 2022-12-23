/*-----------------------------REGISTRAZIONE-----------------------*/
import {db} from '../../firebaseConfig.js';
import {ref, child, get, set} from 'https://www.gstatic.com/firebasejs/9.15.0/firebase-database.js';

document.getElementById('btn_submit_signup').addEventListener("click", function() {
    let nome=document.getElementById('nome');
    let email=document.getElementById('email');
    let pass1=document.getElementById('password1');
    let pass2=document.getElementById('password2');

    signup(nome, email, pass1, pass2);
});

function validation(nome, email, pass1, pass2) {
    let regexNome = /^[a-zA-Z\s]+$/; //Il nome non può contenere numeri ma solo lettere maiuscole-minuscole
    let regexMail = /^[a-zA-Z0-9-.]+@(studenti.poliba|poliba)\.it$/; //La mail deve essere nel formato studenti.poliba.it o poliba.it. Può contenere lettere e numeri e deve essere presente la @

    if(nome.value==null 
        || isEmptyOrSpaces(email.value) 
        || isEmptyOrSpaces(pass1.value) 
        || isEmptyOrSpaces(pass2.value)) {
            alert("All fields are required");
            return false;
        }

    if(nome.value && !regexNome.test(nome.value)) {
        alert("Name can only contain alphabets");
        return false;
    }
    if(email.value && !regexMail.test(email.value)) {
        console.log(email.value);
        alert("Enter a valid mail.\nMail should be in poliba domain.");
        return false;
    }

    if(checkPass(pass1, pass2))
        return true;
    else {
        alert("Password doesn't match.")
        return false;
    }
}

function isEmptyOrSpaces(str) {
    return str===null || str.match(/^ *$/)!==null;
}

function checkPass(pass1, pass2) {
    if(pass1.value===pass2.value) return true;
    else return false;
}

function checkDomain(email) {
    var domain=email.value.split("@")[1];
    if(domain.includes("studenti")) return "STU";
    else return "DOC";
}

function getUsername(email) {
    return email.value.split("@")[0].replace(".","");
}

function signup(nome,email, pass1, pass2) {
    if(!validation(nome, email, pass1, pass2)) return;
    var tipo=checkDomain(email);
    var username=getUsername(email);
    const dbRef=ref(db);

    //All'interno del db, cerca l'utente con data email
    get(child(dbRef, "UsersList/"+username)).then((snaphot) => {
        //snaphot=risultato ricerca. Se l'email esiste gia' nel db, l'utente esiste
        if(snaphot.exists()) {
            alert("Account already exists.");
        }
        else {
            set(ref(db, "UsersList/"+username),
            {
                fullname: nome.value,
                email: email.value,
                tipo: tipo,
                password: pass1.value
            }).then(() =>{
                alert("User added successfully");
            }).catch((error)=> {
                alert("Error "+error);
            });
        }
    });
}
