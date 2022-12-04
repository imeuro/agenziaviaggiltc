addEventListener("DOMContentLoaded", (event) => {
	
    let formContainer = document.getElementById('customer_details');
	if (formContainer) {
		console.debug({formContainer});


		// spezzare pagina..
		let FormHeadings = document.querySelector('.col-2');
		let rawFormHeadings = FormHeadings.innerHTML;

		var result = rawFormHeadings.replace('<div class="form-row ltc_small_heading', 'PLACEHOLDER')
	          .replace(/\<div class="form-row ltc_small_heading/g, '<div class="checkoutSlides-new"><div class="form-row ltc_small_heading')
	          .replace('PLACEHOLDER', '<div class="form-row ltc_small_heading');

	    console.debug(result);
	    FormHeadings.innerHTML = result;

	    let muvit = document.querySelector('.checkoutSlides-new');
	    // lo wrappo in un div pero'
	    var w_muvit = document.createElement('div');
		// insert wrapper before muvit in the DOM tree
		muvit.parentNode.insertBefore(w_muvit, muvit);
		// move muvit into wrapper
		w_muvit.appendChild(muvit);
	    //_formContainer.append(slideNew);
	    FormHeadings.after(w_muvit);


	    _formContainer = document.getElementById('customer_details');
		_formContainer.parentNode.classList.add('checkoutSlider');
		_formContainer.classList = 'checkoutSlides';

		let formNav = document.createElement('nav');
		formNav.classList = 'form-navigation';
		_formContainer.parentNode.insertBefore(formNav, _formContainer);

		Array.from(_formContainer.children).forEach((el, i) => {
			console.debug({el});
			console.debug({i});
			let nslide = i+1;
			el.id="slide-"+nslide;
			let slidebutton = document.createElement('a');
			slidebutton.href="#slide-"+nslide;
			slidebutton.id="to_slide"+nslide;
			if (nslide==1) {
				slidebutton.classList="active";
			}

			slidebutton.addEventListener('click',(event)=>{
				event.preventDefault();
				prevYpos = window.pageYOffset;
				location.href = '#slide-'+nslide;
				window.scrollTo({
					top: prevYpos,
					left: 0,
					behavior: 'auto'
				});

				Array.from(document.querySelectorAll(".form-navigation a")).forEach((el)=>{
					el.classList='';
				})
				document.getElementById("to_slide"+nslide).classList.add('active');
			});

			slidebutton.innerHTML = nslide;
			formNav.appendChild(slidebutton);
		});

		// let formNavClone = formNav.cloneNode(true);
		// formNavClone.classList.add('bottom-nav');
		// _formContainer.append(formNavClone);

		
	}
});