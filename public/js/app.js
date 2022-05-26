function poke(id, pokes) {
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
            if (data.response.conf) {
                console.log(idFull)
                document.getElementById(idFull).innerHTML = (parseInt(pokes)+1)

            }
        })


}
