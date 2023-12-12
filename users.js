window.onload = async function() {
    console.log("User Page loaded");
    const httpRequest =new XMLHttpRequest;
    let contact_name=document.getElementById("Name_Contact");
    var result = document.getElementById("results");
    var response = await fetch('users.php');
    


    if(response.status === 200) {
        var data = await response.text();
        console.log(data);
        result.innerHTML = data;
    } 
    
    else {
        alert("There was a problem processing your request.");
    }      

    
    
}