const toggleModal = () => {
    const {classList} = document.body;
    if(classList.contains("open")){
        classList.remove("open");
        classList.add("closed");
    }
    else{
        classList.remove("closed");
        classList.add("open");
    }
}

const toggleModal1 = () => {
    const {classList} = document.body;
    if(classList.contains("open1")){
        classList.remove("open1");
        classList.add("closed1");
    }
    else{
        classList.remove("closed1");
        classList.add("open1");
    }
}

function cbChange(obj) {
    var cbs = document.getElementsByClassName("cb");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
}


// Sezione dedicata alla selezione di tutti gli elementi della lista pending requests
const selectAll = document.querySelector('.form-group.select-all input');
const allCheckbox = document.querySelectorAll('.form-group:not(.select-all) input');
let listBoolean = [];

allCheckbox.forEach(item=> {
    item.addEventListener('change', function () {
        allCheckbox.forEach(i=> {
            listBoolean.push(i.checked);
        })
        if(listBoolean.includes(false)) {
            selectAll.checked = false;
        } else {
            selectAll.checked = true;
        }
        listBoolean = []
    })
})

selectAll.addEventListener('change', function () {
    if(this.checked) {
        allCheckbox.forEach(i=> {
            i.checked = true;
        })
    } else {
        allCheckbox.forEach(i=> {
            i.checked = false;
        })
    }
})
