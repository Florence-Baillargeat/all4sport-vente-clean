{
    const modal = document.getElementById('modalDiv');

    function newModal(text, duree) {
        console.log("New Modal Function Call")

        modal.children[0].innerHTML = text;
        modal.classList.toggle("mActif")

        setTimeout( () => {

            modal.classList.toggle("mActif")
        }, duree * 1000)
    }

}