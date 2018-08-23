function submitReg()
{
	var failed = false;
	var failedmessage = "";
	
	//get the values from the page
	//var name = document.getElementById("txtName").value;
	var fname = document.getElementById("firstName").value;
	var lname = document.getElementById("lastName").value;
	var email = document.getElementById("email").value;
	var pass = document.getElementById("password").value;
	var confirmpass = document.getElementById("confirmPassword").value;
	
	if(fname.length==0){
		failed = true;
		failedmessage="The first name field is empty";
	}
	
	if(lname.length==0){
		failed = true;
		failedmessage="The last name field is empty";
	}
	
	if(email.length==0){
		failed = true;
		failedmessage="The email field is empty";
	}
	
	if(pass.length==0){
		failed = true;
		failedmessage="The password field is empty";
	}
	
	if(confirmpass.length==0){
		failed = true;
		failedmessage="The confirm password field is empty";
	}
	
	if(confirmpass != pass){
		failed = true;
		failedmessage="The password fields do not match up!";
	}
	
	
	
	if(failed==false){
		//The fields are not empty -- submit to php registration script.
		//Create the xhttp request
		var xhttp = new XMLHttpRequest();
		var params = 'fname='+fname+'&lname='+lname+'&email='+email+'&password='+pass;
		xhttp.open('POST', 'http://104.145.83.147/PHP/register.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				  if(this.responseText == "1"){
					  //1 Indicates a successful registration
					  //Redirect to homescreen
					  location.href = "http://104.145.83.147/";
				  }else if(this.responseText=="2"){
					  //2 Indicates that the email already exists
					  alert("There's an email already associated with this account! (Error: 1002")
				  }else if(this.reponseText=="3"){
					  //3 Indicates error occured.
					  alert("Unhandled Exception - Please try again later! (Error: 1003)")
				  }else{
					  //Shouldn't reach here - failsafe
					  alert("Unhandled Exception - Please try again later! (Error: case else - 1004+)" + this.responseText)
				  }
			}
		};
		xhttp.send(params);
	}else{
		alert(failedmessage);
	}
}