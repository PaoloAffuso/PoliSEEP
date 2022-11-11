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

const toggleModal_1 = () => {
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

function cbChange(obj) {
    var cbs = document.getElementsByClassName("cb");
    for (var i = 0; i < cbs.length; i++) {
        cbs[i].checked = false;
    }
    obj.checked = true;
}
