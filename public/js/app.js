function poke(id, pokes) {

    let id_button = id
    id = id.slice(5)
    let idFull = 'pokeall-'+id
    let sendData = {"id" : id};
    fetch("/poke", {
        method: "POST",
        body: JSON.stringify(sendData),
        header: {
            "Content-Type": "application/json; charset=UTF-8",
        },
    })
        .then((response) => response.json())
        .then((data) => {
            console.log(data)
            document.getElementById(id_button).classList.add("disabled");
            if (data.response.conf) {
                document.getElementById(idFull).innerHTML = (parseInt(pokes)+1)
            }
        })
}
