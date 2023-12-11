window.onload = async () => {
	let login = document.querySelector("#loginbtn");
	let result = document.querySelector("#result");
	const form = document.querySelector("#login");
	let url = "login.php?";
	
    login.addEventListener("click", function(k) {
        k.preventDefault();
		console.log('db');
        const fdata = new FormData(form);
        const data = new URLSearchParams(fdata);

        fetch(url, {
            method: 'POST',
            body: data,
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
        })
        .then(response => {
            if (response.ok) {
                return response.text();
            } else {
                throw new Error('Network response was not ok.');
            }
        })
        .then(responseText => {
            console.log(responseText);
            result.innerHTML = responseText;
            
            if (responseText === "redirect") {
                form.reset();
                window.location.href = "dashboard.html";
            }
        })
        .catch(e => {
            console.error('There was a problem with the fetch operation:', e);
        });
    });

    
    

}



