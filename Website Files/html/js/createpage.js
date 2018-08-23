var entCount = 1;


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
		'<button type="button" onClick="addField(); style.display= \'none\'" class="btn btn-light btn-sm float-right mt-1"><b> + </b></button>\n'+
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
		var failed = false;
		var failedmessage = "";
		
		var pageSubject = document.getElementById('pageSubject').value;
		var pageSupportingTopic = document.getElementById('supportingTopic1').value;
		var pageSupportingContent = document.getElementById('supportingContent1').value;
		var pageSupportingTags = document.getElementById('contentTags').value;
		
		if(pageSubject.length==0){
			failed = true;
			failedmessage="The Page Subject field is empty";
		}
		
		if(pageSupportingTopic.length==0){
			failed = true;
			failedmessage="The Supporting Topic field is empty";
		}
		
		if(pageSupportingContent.length==0){
			failed = true;
			failedmessage="The Supporting Content field is empty";
		}
		
		if(pageSupportingTags.length==0){
			failed = true;
			failedmessage="The Tags field is empty, users won't be able to find your page if there are no search tags! You're required to specify at least 1 tag.";
		}
		
		if(failed==false){
			//The fields are not empty -- submit to create page script.
			//Create the xhttp request
			
			//Gather all the topicNames and use concatinate them together using ~1o0ajdksaxidgfau~ as a splitter
			pageSupportingTopic = "";
			pageSupportingContent = "";
			for(i=1;i<=entCount;i++){
				var topicTitle = document.getElementById('supportingTopic' + i).value;
				var toppicContent = document.getElementById('supportingContent' + i).value;
				pageSupportingTopic += (topicTitle + "1o0ajdksaxidgfau");
				pageSupportingContent += (toppicContent + "1o0ajdksaxidgfau");
			}	
			
			var xhttp = new XMLHttpRequest();
			var params = 'pageSubject='+pageSubject+'&pageSupportingTopic='+pageSupportingTopic+'&pageSupportingContent='+pageSupportingContent+'&pageSupportingTags='+pageSupportingTags;
			xhttp.open('POST', 'http://104.145.83.147/PHP/createpage.php', true);
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
		}else{
			alert(failedmessage);
		}
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