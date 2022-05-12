document.getElementById("processRegister").addEventListener("click", function(event){
    event.preventDefault()

    // hide previous errors and confirmations
    let element = document.getElementById('error');
    element.classList.add('d-none');
    element = document.getElementById('confirmation');
    element.classList.add('d-none');

    //  @todo: implement
    console.log('labas');
    // let data = [];
    // data['username'] = document.getElementById("username").value;
    // data['email'] = document.getElementById("email").value;
    // data['pass'] = document.getElementById("pass1").value;
    // data['passx'] = document.getElementById("pass2").value;

    let data = {
        username: document.getElementById("username").value,
        email: document.getElementById("email").value,
        pass1: document.getElementById("pass1").value,
        passx: document.getElementById("pass2").value
    };
    console.log('bandymas');
    if (data) {
        console.log(data);
        let sendData = {"data" : data};
        fetch("/register", {
            method: "POST",
            body: JSON.stringify(sendData),
            header: {
                "Content-Type": "application/json; charset=UTF-8",
            },
        })
            // .then((response) => response.json())
            // .then((data) => {
            //     if (data.response['confirmation']) {
            //         let element = document.getElementById("confirmation");
            //         element.classList.remove("d-none");
            //         // JS template literals
            //         element.innerHTML = `Your email ${data.response['email']} was successfully added!`;
            //     }
            //     if (data.response['error'] && data.response['error'] !== "") {
            //         let element = document.getElementById("error");
            //         element.classList.remove("d-none");
            //         element.innerHTML = data.response['error'];
            //     }
            // })
    }
});