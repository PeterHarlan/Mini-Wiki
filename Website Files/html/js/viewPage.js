//a method that focus the user's cuser to the search box 
function getFocus()
{
  document.search_box.search.focus();
}

function signUp()
{
  location.href = "signUp.html";
}

//Log in button toggle
// function logInToggle()
// {
//   var a = document.getElementById('LogIn');
//   var b = document.getElementById('LogOut');
//   display(a);
//   display(b);
// }

//Function to toggle display of the buttons
// function display(x)
// {
//   if (x.style.display === 'none') {
// 	x.style.display = 'block';
//   } else {
// 	x.style.display = 'none';
//   }
// }
function isLoggedInModifyHomeDrop()
{
	var x = document.getElementById('SignOut');
	var y = document.getElementById('CreatePage');
	var z = document.getElementById('SignUp');
	var a = document.getElementById('LogIn');
	var b = document.getElementById('Email');
	var c = document.getElementById('Password');
	var d = document.getElementById('btnHolderName');
	var e = document.getElementById('EditPage');
	var f = document.getElementById('DeletePage');

	
	display(e);
	display(x);
	display(y);
	hide(z);
	hide(a);
	hide(b);
	hide(c);
	display(f);
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
	var e = document.getElementById('EditPage');
	var f = document.getElementById('DeletePage');
	
	hide(e);
	
	hide(x);
	hide(y);
	display(z);
	display(a);
	display(b);
	display(c);
	hide(f);
	//Adds user icon on log in button
	d.innerHTML = '<i class = "fa fa-user" aria-hidden="true"></i>  ';
}

//logout function
//@Override login.js
function logOut()
{
	//Destroy Session Variables
	destroySession();
	isLoggedOutModifyHomeDrop();
}

//The onload functions
//@Override login.js
function loadFunctions()
{	
	if(isLoggedIn()){
		isLoggedInModifyHomeDrop();
	}else{
		isLoggedOutModifyHomeDrop();
	}
}