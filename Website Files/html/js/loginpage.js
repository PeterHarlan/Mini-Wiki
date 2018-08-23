//a method that focus the user's cuser to the search box 
function getFocus()
{
	document.search_box.search.focus();
}

function isLoggedInModifyHomeDrop()
{
	var x = document.getElementById('SignOut');
	var y = document.getElementById('CreatePage');
	var z = document.getElementById('SignUp');
	var a = document.getElementById('LogIn');
	var b = document.getElementById('Email');
	var c = document.getElementById('Password');
	var d = document.getElementById('btnHolderName');
	
	display(x);
	display(y);
	hide(z);
	hide(a);
	hide(b);
	hide(c);

	
	d.innerText = glob_firstname + " ";
}

function isLoggedOutModifyHomeDrop()
{
	var x = document.getElementById('SignOut');
	var y = document.getElementById('CreatePage');
	var z = document.getElementById('SignUp');
	var a = document.getElementById('LogIn');
	var b = document.getElementById('Email');
	var c = document.getElementById('Password');
	var d = document.getElementById('btnHolderName');
	
	hide(x);
	hide(y);
	display(z);
	display(a);
	display(b);
	display(c);

	
	//Adds user icon on log in button
	d.innerHTML = '<i class = "fa fa-user" aria-hidden="true"></i>  ';
	// d.innerText = "Account ";
}

//logout function
function logOut()
{
	//Destroy Session Variables
	destroySession();
	isLoggedOutModifyHomeDrop();
}

//View Western Kentucky University
function viewWKU()
{
	location.href = "http://104.145.83.147/viewPage.php?pageID=5";
}