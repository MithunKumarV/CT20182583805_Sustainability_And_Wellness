$(document).ready(function(){
	$("#usersform").validate({
		rules:{
			firstname   :	{ required:true },
			lastname   	:	{ required:true },
			aadhar   	:	{ required:true, minlength:12 , maxlength:12 },
			email   	:	{ required:true, email:true },
			mobile   	:	{ required:true, minlength:10 , maxlength:10 },
			gender   	:	{ required:true },
			status   	:	{ required:true },
			dob   		:	{ required:true },
			address   	:	{ required:true },
			city   		: 	{ required:true },
			country   	:	{ required:true },
			zipcode   	:	{ required:true, minlength:6, maxlength:6 },
			 },
		messages:{
			firstname:	{ required:'Firstname is required' },
			lastname :	{ required:'Lastname is required' },
			aadhar   :	{ required:'Aadhar is required', minlength:'Aadhar must be 12 digit', maxlength:'Aadhar must be 12 digit'},
			email    :	{ required:'email is required', email:'Please enter valid email' },
			mobile   :	{ required:'Mobile is required', minlength:'Mobile must be 10 digit', maxlength:'Mobile must be 10 digit'  },
			gender   :	{ required:'Gender is required' },
			status   :	{ required:'Status is required' },
			dob   	 :	{ required:'DOB is required' },
			address  :	{ required:'Address is required' },
			city   	 :	{ required:'City is required' },
			country  :	{ required:'State is required' },
			zipcode  :	{ required:'Zipcode is required', minlength:'Zipcode must be 6 digit', maxlength:'Zipcode must be 6 digit' },
		}
	});
	$("#userloginform").validate({
		rules:{
			useremail   	:	{ required:true },
			password   		:	{ required:true },
			 },
		messages:{
			useremail:	{ required:'Username is required' },
			password :	{ required:'Password is required' },
		}
	});
});

