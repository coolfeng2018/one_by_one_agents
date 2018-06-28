(function () {
    "use strict";
	
	console.log("ready!");

	console.log("isLoggedIn? " + localStorage.getItem("isLoggedIn"));

	var $elFormSignUp = $("#formSignUp"),
		$elFormLogIn  = $("#formLogIn"),
		$elBtnLogOut  = $("#btnLogOut"),
		$elUserEmail  = $(".userEmail");
	
	if (localStorage.getItem("isLoggedIn") === undefined || localStorage.getItem("isLoggedIn") === null || localStorage.getItem("isLoggedIn") === "") {
		console.log("is NOT logged in: " + localStorage.getItem("isLoggedIn"));
	} else {
		console.log("IS LOGGED IN: " + localStorage.getItem("isLoggedIn"));
		$(":mobile-pagecontainer").pagecontainer("change", "#pgHome", { "transition" : "flip", "allowSamePageTransition" : true, "reload" : true });
		$elUserEmail.html((localStorage.getItem("isLoggedIn")).toLowerCase());
	}

	function fnSignUp() {
		console.log("fnSignUp START");
		event.preventDefault();

		var $elInEmailSignUp			= $("#inEmailSignUp"),
			$elInPasswordSignUp 		= $("#inPasswordSignUp"),
			$elInPasswordConfirmSignUp 	= $("#inPasswordConfirmSignUp");

		console.log($elInEmailSignUp.val(), $elInPasswordSignUp.val(), $elInPasswordConfirmSignUp.val());

		if ($elInPasswordSignUp.val() !== $elInPasswordConfirmSignUp.val()) {
			console.log("password DON'T match");
			$elInPasswordSignUp.val("");
			$elInPasswordConfirmSignUp.val("");
			$("#popSignUpMismatch").popup();
			$("#popSignUpMismatch").popup("open", { "positionTo" : "open", "transition" : "flip" });
		} else {
			var tmpValInEmailSignUp = $elInEmailSignUp.val().toLowerCase(),
				tmpValInPasswordSignUp = $elInPasswordSignUp.val().toLowerCase();

			console.log("email " + tmpValInEmailSignUp, "pass " + tmpValInPasswordSignUp);

			if (localStorage.getItem(tmpValInEmailSignUp) === null) {
				console.log("tmpValInEmailSignUp dos not exist");
				localStorage.setItem(tmpValInEmailSignUp, tmpValInPasswordSignUp);
				console.log("saved " + tmpValInEmailSignUp);
				$elFormSignUp[0].reset();
				$("#popSignUpSuccess").popup();
				$("#popSignUpSuccess").popup("open", { "positionTo" : "window", "transition" : "flip" });
			} else {
				console.log("tmpValInEmailSignUp EXISTS");
				console.log("got " + localStorage.getItem(tmpValInEmailSignUp));
				$("#popSignUpExists").popup();
				$("#popSignUpExists").popup("open", { "positionTo" : "window", "transition" : "flip" }); 
			} // END if...else localStorage.getItem()
		} // END if...else check same PWD
		console.log("fnSignUp END");
	} // END fnSignUp()

	function fnLogIn() {
		console.log("fnLogIn START");
		event.preventDefault();

		var $elInPhoneLogIn 		= $("#inPhoneLogIn"),
			$elInPasswordLogIn 		= $("#inPasswordLogIn"),
			$token = $("input[name='_token']").val(),
			tmpValInPhoneLogIn 		= $elInPhoneLogIn.val().toLowerCase(),
			tmpValInPasswordLogIn	= $elInPasswordLogIn.val().toLowerCase();
			// token	= $token;

		console.log(tmpValInPhoneLogIn, tmpValInPasswordLogIn);

		//验证
		$.ajax( {
            type : "post",
            url : "/admin/check",
            dataType : 'json',
            data : {'_token':$token,user:tmpValInPhoneLogIn,pwds:tmpValInPasswordLogIn},
            success : function(data) {
                if(data.status){
                    window.location.href=data.url;
                }else{
                    $("#error").text(data.msg);
                    $("#error").popup();
					$("#error").popup("open", { "positionTo" : "window", "transition" : "flip" });
                }
            }
        });
        return false;

		if (localStorage.getItem(tmpValInPhoneLogIn) === null) {
			// console.log("tmpValInPhoneLogIn does NOT exist");
			$("#popLoginNonexistant").popup();
			$("#popLoginNonexistant").popup("open", { "positionTo" : "window", "transition" : "flip" }); 
		} else {
			console.log("tmpValInPhoneLogIn DOES exist");
			if (tmpValInPasswordLogIn === localStorage.getItem(tmpValInPhoneLogIn)) {
				console.log("passwords MATCH"); 
				localStorage.setItem("isLoggedIn", tmpValInPhoneLogIn);
				console.log("logged in " + localStorage.getItem("isLoggedIn")); 
				$(":mobile-pagecontainer").pagecontainer("change", "#pgHome", { "transition": "flip" });
				$elUserEmail.html(tmpValInPhoneLogIn.toLowerCase());
			} else {
				console.log("passwords DON'T match");
				$elInPasswordLogIn.val("");
				$("#popLoginIncorrect").popup();
				$("#popLoginIncorrect").popup("open", { "positionTo": "window", "transition": "flip" }); 
			} // END if...else of Passwords match
		} // END check if localStorage.getItem() exists
	} // END fnLogin()

	function fnLogOut() {
		console.log("about to log out");
		localStorage.setItem("isLoggedIn", "");
		$(":mobile-pagecontainer").pagecontainer("change", "#pgWelcome", { "transition": "flip" });
		console.log("Logged OUT: " + localStorage.getItem("isLoggedIn"));
	}

	$elFormSignUp.submit(function(event) { fnSignUp(event); });
	$elFormLogIn.submit(function (event) { fnLogIn(event); });
	$elBtnLogOut.on("click", fnLogOut);
} )();