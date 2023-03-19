/**
MEANINGFUL INTERACTION #1
It is used only in mobile devices with screen max-width smaller than 600px.
Navigation menu links toggle between showing and hiding when the user clicks on the hamburger menu icon.
*/

function mobileMenu() {
    let menu = document.querySelector("#menu-links");
    if (menu.style.display === "block") {
        menu.style.display = "none";
    } else {
        menu.style.display = "block";
    }
}


/**
MEANINGFUL INTERACTION #2
Expand and collapse the detail information in activities.html.
*/
document.addEventListener("readystatechange", function(event) {
    document.querySelector("body").classList.add("js");
    let activitiesExpandButtons = document.querySelectorAll("#activities article .expand-button a");
    for(let button of activitiesExpandButtons) {
        button.addEventListener("click", function(event) {
            this.parentElement.parentElement.classList.add("expanded");
            event.preventDefault();
        });
    }

    let activitiesCollapseButtons = document.querySelectorAll("#activities article .collapse-button a");
    for(let button of activitiesCollapseButtons) {
        button.addEventListener("click", function(event) {
            this.parentElement.parentElement.parentElement.classList.remove("expanded");
            event.preventDefault();
        });
    }
});

/**
MEANINGFUL INTERACTION #3
Check whether the booking information in book.html are all completed.
If the form is completed, it will show "Your reservation has been confirmed!".
*/

document.addEventListener("readystatechange", function(event) {
    const formButton = document.querySelector("#book-form form button");
	document.querySelector("#book-form form").addEventListener("submit", function(event) {

		let firstName = document.querySelector("#form-first-name").value;
		let lastName = document.querySelector("#form-last-name").value;
		let phone = document.querySelector("#form-phone").value;
		let email = document.querySelector("#form-email").value;
		let fromDate = document.querySelector("#form-from-date").value;
		let toDate = document.querySelector("#form-to-date").value;
		let roomNumber = document.querySelector("#form-room-number").value;
		let complete = false;

		console.log(firstName);
		console.log(lastName);
		console.log(phone);
		console.log(email);
		console.log(fromDate);
		console.log(toDate);
		console.log(roomNumber);

		if(firstName != "" && lastName != "" 
			&& phone != ""  && email != "" 
			&& fromDate != "" && toDate != "" 
			&& roomNumber != "") {
			complete = true;
		}

		if(firstName == "") {
			let formFirstName = document.querySelector("#form-first-name");
			formFirstName.classList.add("error");
			let formFirstNameLabel = formFirstName.closest(".form-item").querySelector("label");
			formFirstNameLabel.classList.add("error");
		}

		if(lastName == "") {
			let formLastName = document.querySelector("#form-last-name");
			formLastName.classList.add("error");
			let formLastNameLabel = formLastName.closest(".form-item").querySelector("label");
			formLastNameLabel.classList.add("error");
		}

		if(phone == "") {
			let formPhone = document.querySelector("#form-phone");
			formPhone.classList.add("error");
			let formPhoneLabel = formPhone.closest(".form-item").querySelector("label");
			formPhoneLabel.classList.add("error");
		}

		if(email == "") {
			let formemail = document.querySelector("#form-email");
			formemail.classList.add("error");
			let formEmailLabel = formemail.closest(".form-item").querySelector("label");
			formEmailLabel.classList.add("error");
		}

		if(fromDate == "") {
			let formfromDate = document.querySelector("#form-from-date");
			formfromDate.classList.add("error");
			let formfromDateLabel = formfromDate.closest(".form-item").querySelector("label");
			formfromDateLabel.classList.add("error");
		}

		if(toDate == "") {
			let formtoDate = document.querySelector("#form-to-date");
			formtoDate.classList.add("error");
			let formtoDateLabel = formtoDate.closest(".form-item").querySelector("label");
			formtoDateLabel.classList.add("error");
		}
		
		if(roomNumber == "") {
			let formroomNumber = document.querySelector("#form-room-number");
			formroomNumber.classList.add("error");
			let formroomNumberLabel = formroomNumber.closest(".form-item").querySelector("label");
			formroomNumberLabel.classList.add("error");
		}

		if(complete) {
			console.log("Complete Form");
			formButton.innerHTML = "Your reservation has been confirmed!";
			formButton.setAttribute("disabled", "true");
		}
		else {
			console.log("Incomplete Form");
			formButton.innerHTML = "Incomplete !";
		}
		event.preventDefault();
	});

	let formFields = document.querySelectorAll("#form-first-name, #form-last-name, #form-phone, #form-email, #form-room-number");
	for(let formField of formFields) {
		formField.addEventListener("keydown", function() {
			this.classList.remove("error");
			this.closest(".form-item").querySelector("label").classList.remove("error");
		});
	}
	
	let formDateFields = document.querySelectorAll("#form-from-date, #form-to-date");
	for(let formDateField of formDateFields) {
		formDateField.addEventListener("click", function() {
			this.classList.remove("error");
			this.closest(".form-item").querySelector("label").classList.remove("error");
		});
	}		

});

/**
MEANINGFUL INTERACTION #4
Login modal window. 
When the user logs in, an alert window which shows "Hello, (username). Welcome to Sweets Fantasyland Resort!" will popup, 
and the text of "Log in or Sign Up" button will change to "Hello, (username)".
*/
let loginModal = document.querySelector("#log-in-modal");
			
let loginbtn = document.querySelector("#log-in-button");
loginbtn.addEventListener("click", function(event) {
	loginModal.style.display = "block";
});

let closebtn = document.querySelector(".close");
closebtn.addEventListener("click", function(event) {
	loginModal.style.display = "none";
});

let cancelbtn = document.querySelector(".cancel-button");
cancelbtn.addEventListener("click", function(event) {
	loginModal.style.display = "none";
});

window.addEventListener("click", function(event) {
	if (event.target == loginModal) {
		loginModal.style.display = "none";
	}
});

document.querySelector("#log-in-modal form").addEventListener("submit", function(event) {
	let name = document.querySelector("#form-username").value;
	console.log(name);
	alert("Hello, " + name + ".\nWelcome to Sweets Fantasyland Resort!");
	loginbtn.innerHTML = "Hello, " + name +".";
	loginModal.style.display = "none";

	event.preventDefault();
});
	

		
	
