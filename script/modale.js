const toggleModal = () => {
    const {classList} = document.body;
    if(classList.contains("open")){
        classList.remove("open");
        classList.add("closed")
    }
    else{
        classList.remove("closed");
        classList.add("open")
    }
}
