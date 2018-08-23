var entCount = 0;
var pageID = 0;
function addField()
{
	entCount++;
	var fieldDivs = document.getElementById('pageFields');
	var container = document.createElement("div");
	var newData =
	'<div>\n' +
	' <h2>Topic ' + entCount +'</h2>\n' +
	'  <input type="text" class="form-control" id="supportingTopic'+ entCount +'" placeholder="Supporting Topic">\n' + 
	'  <div class="form-group floating-label-form-group controls mt-2">\n' + 
	'	  <!-- <textarea rows="5" class="form-control" placeholder="Message" id="message" required data-validation-required-message="Please enter a message."></textarea>\n' + 
	'	  <p class="help-block text-danger"></p> -->\n' + 
	'	<textarea class="form-control" id="supportingContent'+ entCount +'" placeholder="Supporting Content"></textarea>\n' +  
	'  </div>\n'+
	'</div>\n';
	container.innerHTML = newData;
	fieldDivs.appendChild(container); 
}

function submitPage()
{
	//run a double check to make sure they're currently logged in and haven't timed out
	if(isLoggedIn()==false){
		redirHome();
	}else{
		//The fields are not empty -- submit to create page script.
		//Create the xhttp request
		
		//Gather all the topicNames and use concatinate them together using ~1o0ajdksaxidgfau~ as a splitter
		var pageSupportingTags = document.getElementById('contentTags').value;
		var pageSubject = document.getElementById('pageSubject').value;
		pageSupportingTopic = "";
		pageSupportingContent = "";
		for(i=1;i<=entCount;i++){
			var topicTitle = document.getElementById('supportingTopic' + i).value;
			var toppicContent = document.getElementById('supportingContent' + i).value;
			pageSupportingTopic += (topicTitle + "1o0ajdksaxidgfau");
			pageSupportingContent += (toppicContent + "1o0ajdksaxidgfau");
		}	
		
		var xhttp = new XMLHttpRequest();
		var params = 'pageSubject='+pageSubject+'&pageSupportingTopic='+pageSupportingTopic+'&pageSupportingContent='+pageSupportingContent+'&pageSupportingTags='+pageSupportingTags+'&pageID='+pageID;
		xhttp.open('POST', 'http://104.145.83.147/PHP/submitEdit.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
		xhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {
				  if(this.responseText == "FAIL"){
					  alert(this.responseText);
				  }else{
					  alert("Page Created");
					  location.href = "viewPage.php?pageID=" + this.responseText;
				  }
			}
		};
		xhttp.send(params);
	}	
			
}

function loadVals()
{
	var starterCount = document.getElementById('hiddenStarterPHPCount');
	entCount = starterCount.value;
	
	var pageIDHolder = document.getElementById('pageIDHolder');
	pageID = pageIDHolder.value;
}

//@ovveride login.js
//The onload functions
function loadFunctions()
{
	getFocus();
	loadVals();
	if(isLoggedIn()){
		isLoggedInModifyHomeDrop();
	}else{
		isLoggedOutModifyHomeDrop();
	}
}

//a method that focus the user's cuser to the search box 
function getFocus()
{
	var firstField = document.getElementById('pageSubject');
	firstField.focus();
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
	hide(y);
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
	d.innerText = "Account ";
}

//logout function
function logOut()
{
	//Destroy Session Variables
	destroySession();
	isLoggedOutModifyHomeDrop();
	redirHome();
}

//User can no longer create pages because they've been logged out
function redirHome()
{
	location.href = "index.html";
}