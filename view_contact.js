window.onload =  function() {
    let notesText = document.getElementById("note-text");
    let assign_button = document.getElementById("b1");
    let switch_button = document.getElementById("b2");
    let note_button = document.getElementById("b3");
    var msgResults = document.getElementById("msg-results-notes");
    let assingedplace = document.getElementById("para");

 
    

    assign_button.addEventListener("click", async (e) => {
        e.preventDefault();
        let response = await fetch(`view_contact.php?assigntoyou=true`);

        if(response.status === 200){
            let data = await response.text();
            assingedplace.innerHTML = data;
        } else {
            alert("There was a problem processing your request.");
        }
});

    switch_button.addEventListener("click", async (e) => {

        e.preventDefault();
       
      
        switchnew = "";
            if(switch_button.textContent == "Switch to Sales Lead"){
                switch_button.textContent = "";
                switch_button.innerHTML = '<i class="fa-solid fa-down-left-and-up-right-to-center" id="switch-icon"></i>Switch to Support';
                switch_button.classList.remove('lead-button');
                switch_button.classList.add('support-button');
                switchnew = "Sales Lead";
            } else{
                switch_button.textContent = "";
                switch_button.innerHTML = '<i class="fa-solid fa-down-left-and-up-right-to-center" id="switch-icon"></i>Switch to Sales Lead';
                switchnew = "Support";
                switch_button.classList.remove('support-button');
                switch_button.classList.add('lead-button');
            }

            let response = await fetch(`view_contact.php?switchto=${switchnew}`);

            if(response.status === 200){
                let data = await response.text();
                console.log(data);
            } else {
                alert("There was a problem processing your request.");
            }
       

    });



    note_button.addEventListener('click', async (e) => {
        noteValue = notesText.value;

        let response = await fetch(`view_contact.php?noteinfo=${noteValue}`);
        notesText.value = "";
        if(response.status === 200){
            let data = await response.text();
            console.log("cat");
            msgResults.innerHTML += data;
        } else {
            alert("There was a problem processing your request.");
        }
    })
}