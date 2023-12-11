window.onload = async function() {
    console.log("User Page loaded");
    const httpRequest =new XMLHttpRequest;
    let contact_name=document.getElementById("Name_Contact");
    var result = document.getElementById("results");
    var response = await fetch('users.php');
    let add_button= document.getElementById("add_user");
    

    add_button.addEventListener("click", () => {
        console.log("Add User Button Clicked");          
        
        
    });

    if(response.status === 200) {
        var data = await response.text();
        result.innerHTML = data;
    } 
    
    else {
        alert("There was a problem processing your request.");
    }      

    
    
}