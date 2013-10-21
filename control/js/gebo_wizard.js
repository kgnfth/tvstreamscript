/* [ ---- Gebo Admin Panel - wizard ---- ] */

	$(document).ready(function() {
		//* simple wizard
		gebo_wizard.simple();
		//* wizard with validation
		gebo_wizard.validation();
		//* add step numbers to titles
		gebo_wizard.steps_nb();
	});

	gebo_wizard = {
		simple: function(){
			$('#simple_wizard').stepy({
				titleClick	: true,
				nextLabel:      'Next <i class="icon-chevron-right icon-white"></i>',
				backLabel:      '<i class="icon-chevron-left"></i> Back'
			});
		},
		validation: function(){
			$('#validate_wizard').stepy({
				nextLabel:      'Forward <i class="icon-chevron-right icon-white"></i>',
				backLabel:      '<i class="icon-chevron-left"></i> Backward',
				block		: true,
				errorImage	: true,
				titleClick	: true,
				validate	: true
			});
			stepy_validation = $('#validate_wizard').validate({
				onfocusout: false,
				errorPlacement: function(error, element) {
					error.appendTo( element.closest("div.controls") );
				},
				highlight: function(element) {
					$(element).closest("div.control-group").addClass("error f_error");
					var thisStep = $(element).closest('form').prev('ul').find('.current-step');
					thisStep.addClass('error-image');
				},
				unhighlight: function(element) {
					$(element).closest("div.control-group").removeClass("error f_error");
					if(!$(element).closest('form').find('div.error').length) {
						var thisStep = $(element).closest('form').prev('ul').find('.current-step');
						thisStep.removeClass('error-image');
					};
				},
				rules: {
					'v_username'	: {
						required	: true,
						minlength	: 3
					},
					'v_email'		: 'email',
					'v_newsletter'	: 'required',
					'v_password'	: 'required',
					'v_city'		: 'required',
					'v_country'		: 'required'
				}, messages: {
					'v_username'	: { required:  'Username field is required!' },
					'v_email'		: { email	:  'Invalid e-mail!' },
					'v_newsletter'	: { required:  'Newsletter field is required!' },
					'v_password'	: { required:  'Password field is requerid!' },
					'v_city'		: { required:  'City field is requerid!' },
					'v_country'		: { required:  'Country field is requerid!' }
				},
				ignore				: ':hidden'
			});
		},
		//* add numbers to step titles
		steps_nb: function(){
			$('.stepy-titles').each(function(){
				$(this).children('li').each(function(index){
					var myIndex = index + 1
					$(this).append('<span class="stepNb">'+myIndex+'</span>');
				})
			})
		}
	};