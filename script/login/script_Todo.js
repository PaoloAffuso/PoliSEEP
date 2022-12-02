// Import di tutti gli elementi richiesti
const inputField = document.querySelector(".input-field textarea"),
  todoLists = document.querySelector(".todoLists"),
  pendingNum = document.querySelector(".pending-num"),
  clearButton = document.querySelector(".clear-button");

// Questa funzione viene richiamata durante l'aggiunta, l'eliminazione e la selezione/deselezione dei task
function allTasks() {
  let tasks = document.querySelectorAll(".pending");

  // Se non ci sono task, il contenuto del testo num in sospeso sarà no, in caso contrario il valore num in sospeso sarà pari al numero di task
  pendingNum.textContent = tasks.length === 0 ? "no" : tasks.length;

  let allLists = document.querySelectorAll(".list");
  if (allLists.length > 0) {
    todoLists.style.marginTop = "20px";
    clearButton.style.pointerEvents = "auto";
    return;
  }
  todoLists.style.marginTop = "0px";
  clearButton.style.pointerEvents = "none";
}

// Aggiunge un task quando si inserisce il valore nell'area di testo e si preme invio
inputField.addEventListener("keyup", (e) => {
  let inputVal = inputField.value.trim(); // La funzione .trim rimuove lo spazio davanti e dietro al valore immesso

  // Se si fa clic sul pulsante Invio e la lunghezza del valore assegnato è maggiore di 0
  if (e.key === "Enter" && inputVal.length > 0) {
    let liTag = ` <li class="list pending" onclick="handleStatus(this)">
          <input type="checkbox" />
          <span class="task">${inputVal}</span>
          <i class="uil uil-trash" onclick="deleteTask(this)"></i>
        </li>`;

    todoLists.insertAdjacentHTML("beforeend", liTag); // Inserimento del tag li all'interno del div todolist
    inputField.value = ""; // Rimuove il valore dal campo di input
    allTasks();
  }
});

// Seleziona e deseleziona la checkbox mentre si fa clic sul task
function handleStatus(e) {
  const checkbox = e.querySelector("input"); // Recupera la checkbox
  checkbox.checked = checkbox.checked ? false : true;
  e.classList.toggle("pending");
  allTasks();
}

// Elimina il task mentre si fa clic sull'icona di eliminazione
function deleteTask(e) {
  e.parentElement.remove(); // Ottiene l'elemento e lo rimuove
  allTasks();
}

// Cancella tutti i task mentre si fa clic sul pulsante clear.
clearButton.addEventListener("click", () => {
  todoLists.innerHTML = "";
  allTasks();
});
