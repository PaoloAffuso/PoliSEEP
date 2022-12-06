// Import di tutti gli elementi richiesti
const inputField = document.querySelector(".input-field textarea"),
  todoLists = document.querySelector(".todoLists"),
  pendingNum = document.querySelector(".pending-num"),
  clearButton = document.querySelector(".clear-button");

// Questa funzione viene richiamata durante l'aggiunta, l'eliminazione e la selezione/deselezione dei task
function allTasks() {
  // Se non ci sono task, il contenuto del testo num in sospeso sarà no, in caso contrario il valore num in sospeso sarà pari al numero di task
  let tasks = document.querySelectorAll(".pending");

  $.ajax({
    url: "../student/task.php",
    type: "post",
    data : {'call':'getPendingTask'},
    success: function (response) {
      if(response==="0") pendingNum.textContent="no";
      else pendingNum.textContent=response;

      let allLists = document.querySelectorAll(".list");
      if (allLists.length > 0) {
        todoLists.style.marginTop = "20px";
        clearButton.style.pointerEvents = "auto";
        return;
      }
      todoLists.style.marginTop = "0px";
      clearButton.style.pointerEvents = "none";
    }
  });
}

// Aggiunge un task quando si inserisce il valore nell'area di testo e si preme invio
inputField.addEventListener("keyup", (e) => {
  let inputVal = inputField.value.trim(); // La funzione .trim rimuove lo spazio davanti e dietro al valore immesso

  // Se si fa clic sul pulsante Invio e la lunghezza del valore assegnato è maggiore di 0
  if (e.key === "Enter" && inputVal.length > 0) {
    $.ajax({
      url: "../student/task.php",
      type: "post",
      data : {'call':'insertTask', 'descrizione':inputVal},
      success: function (response) {
        res=response.split("-");
        console.log(res);
        if(res[0]=="OK") {
          let liTag = ` <li class="list pending" onclick="handleStatus(this, ${res[1]})">
            <input type="checkbox" />
            <span class="task">${inputVal}</span>
            <i class="uil uil-trash" onclick="deleteTask(this, ${res[1]})"></i>
          </li>`;

          todoLists.insertAdjacentHTML("beforeend", liTag); // Inserimento del tag li all'interno del div todolist
          inputField.value = ""; // Rimuove il valore dal campo di input
          allTasks();
        }
        else alert("Errore durante l'inserimento");
      }
    });
  }
});

// Seleziona e deseleziona la checkbox mentre si fa clic sul task
function handleStatus(e, num) {
  const checkbox = e.querySelector("input"); // Recupera la checkbox
  const descrizione = e.querySelector("span").innerHTML;

  $.ajax({
    url: "../student/task.php",
    type: "post",
    data : {'call':'changeStatus', 'stato':!checkbox.checked, 'num': num},
    success: function (response) {
      if(response==="OK") {
        if(checkbox.checked) checkbox.checked=false;
        else checkbox.checked=true;
        e.classList.toggle("pending");
        allTasks();
      }
      else alert("Errore durante la modifica");
    }
  });
}

// Elimina il task mentre si fa clic sull'icona di eliminazione
function deleteTask(e, num) {
  $.ajax({
    url: "../student/task.php",
    type: "post",
    data : {'call':'delSingleTask', 'descrizione': e.id, 'num': num},
    success: function (response) {
      if(response==="OK") {
        e.parentElement.remove(); // Ottiene l'elemento e lo rimuove
        allTasks();
      }
      else alert("Errore durante la cancellazione");
    }
  });
  
}

// Cancella tutti i task mentre si fa clic sul pulsante clear.
clearButton.addEventListener("click", () => {
  $.ajax({
    url: "../student/task.php",
    type: "post",
    data : {'call':'delAllTask'},
    success: function (response) {
      if(response==="OK") {
        todoLists.innerHTML = "";
        allTasks();
      }
      else alert(response);
    }
  });
});

//Per assicurarsi che il numero di task pending sia corretto
window.onload = function() {
  allTasks();
}