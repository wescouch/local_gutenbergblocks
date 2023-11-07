// Declare jQuery ($)
window.jQuery = window.$ = jQuery;

// Theme path variable to use throughout JS files
window.themePath = '/wp-content/themes/gutenbergblocks';

// Helper function to add scripts/styles to page after load and return promise
async function themeLoadScripts(scriptURLS) {
	const load = (scriptURL) => {
		return new Promise(function(resolve, reject) {
			let existingScript = document.querySelector(`[src*="${scriptURL}"]`)
			let existingStyle = document.querySelector(`[href*="${scriptURL}"]`)

			if (!!existingScript || !!existingStyle) {
				resolve();

				// If block script then refire their scripts
				if (!!existingScript && !!(existingScript.id).includes('ipro-block-')) {
					let scriptName = (existingScript.id).replace(/-/g, '_')
					setTimeout(() => { //Wait for scripts to exist to refire
						(new Function('return ' + scriptName)())();
					}, 1);
				}
			} else {
				if (!!scriptURL.includes('.js')) {
					// <script> creation
					let script = document.createElement('script');
					script.onload = resolve;
					script.type = 'text/javascript';
					script.src = scriptURL;
					if (!!scriptURL.includes('/page-templates/')) {
						blockName = scriptURL.split('/blocks/').pop().split('/')[0];
						script.id = 'ipro-block-' + blockName + '-js';
					}
					document.body.appendChild(script);
				} else {
					// <link style> creation
					let style = document.createElement('link');
					style.rel = 'stylesheet';
					style.type = 'text/css';
					style.href = scriptURL;
					style.media = 'all';
					document.body.appendChild(style);
				}
			}
		});
	}
	let promises = [];
	for (const scriptURL of scriptURLS) {
		promises.push(load(scriptURL));
	}
	await Promise.all(promises);
	for (const scriptURL of scriptURLS) {
		themeLoadScripts.loaded.add(scriptURL);
	}
}
themeLoadScripts.loaded = new Set();


/**
 * Helper function to get translate values (courtesy of zellwk.com)
 * @param {HTMLElement} element
 * @returns {Object}
 */
 const getTranslateValues = (element) => {
  const style = window.getComputedStyle(element)
  const matrix = style['transform'] || style.webkitTransform || style.mozTransform

  // No transform property. Simply return 0 values.
  if (matrix === 'none' || typeof matrix === 'undefined') {
    return {
      x: 0,
      y: 0,
      z: 0
    }
  }

  // Can either be 2d or 3d transform
  const matrixType = matrix.includes('3d') ? '3d' : '2d'
  const matrixValues = matrix.match(/matrix.*\((.+)\)/)[1].split(', ')

  // 2d matrices have 6 values
  // Last 2 values are X and Y.
  // 2d matrices does not have Z value.
  if (matrixType === '2d') {
    return {
      x: parseInt(matrixValues[4]),
      y: parseInt(matrixValues[5]),
      z: 0
    }
  }

  // 3d matrices have 16 values
  // The 13th, 14th, and 15th values are X, Y, and Z
  if (matrixType === '3d') {
    return {
      x: parseInt(matrixValues[12]),
      y: parseInt(matrixValues[13]),
      z: parseInt(matrixValues[14])
    }
  }
}


const scriptsInit = () => {
  const ajaxurl = '/wp-admin/admin-ajax.php';
	const prefersReducedMotion = window.matchMedia(`(prefers-reduced-motion: reduce)`) === true || window.matchMedia(`(prefers-reduced-motion: reduce)`).matches === true;

	// Get site header height
	const getHeaderHeight = () => {
		let headerHeight = $('#header').outerHeight(),
				adminBarExists = ($(window).outerWidth() >= 600 && !!$('#wpadminbar').length)

		if (adminBarExists) { headerHeight += $('#wpadminbar').outerHeight()}

		return headerHeight;
	}
	

	// Helper function to get scroll to position for animate({scrollTop}) functions like button anchors
	const getScrollToPos = (scrollToEl, negTopPos = 0) => {
		let topPos = (getHeaderHeight() + negTopPos)		
		let scrollToPos = scrollToEl.offset().top - topPos;
		
		return scrollToPos;
	}

	// Add Smooth scroll for passing has Id on anchor tag
  if (window.location.hash != "" && !!$(window.location.hash).length) {
    setTimeout(() => {
      $("html, body").animate({
				scrollTop: getScrollToPos($(window.location.hash)) //Scroll to positiion
			}, 500, 'swing');
    }, 500);
  }
  // Click hash link -> smooth scroll to anchor link
  $("a, a.mega-menu-link *").on('click', function(e) {
		let thisLink = this;
		if (!$(this).is('a')) {
			// If not a link then get the parent link (have to do for Mega Menu structure)
			thisLink = $(this).closest('a')[0];
		}

    let linkURL = thisLink.href.split('#')[0]

    // Make sure hash is on thisLink page && thisLink.hash has a value before overriding default behavior
    if (linkURL === (window.location.origin + window.location.pathname) && thisLink.hash !== "") {
			let scrollEl = $(thisLink.hash)
			
      // Prevent default anchor click behavior
      e.preventDefault();

      $('html, body').animate({
        scrollTop: getScrollToPos(scrollEl, 20) //Scroll to positiion
      }, 500, 'swing');
			scrollEl.focus() //Focus (accessibility)

			return false;
    }
  });
	// Click anchor button -> smooth scroll to anchor link
	$('button.anchor-button').on('click', function() {
		let scrollEl = $('#' + $(this).attr('data-anchor'));

		$('html, body').animate({
			scrollTop: getScrollToPos(scrollEl) //Scroll to positiion
		}, 500, 'swing');
		scrollEl.focus() //Focus (accessibility)
	})


	// Set sticky positioned elements below header
	const setStickyElsPosTop = () => {
		const stickyEls = $('.sticky-below-header')
		let headerHeight = getHeaderHeight(),
				stickyPos = headerHeight

		stickyEls.each(function() {
			let stickyEl = $(this),
					stickyAdjustment = parseInt(stickyEl.attr('data-sticky-adjustment'))

			// If there is a 'data-sticky-adjustment' data attribute then adjust by that amount
			if (!!stickyAdjustment) {
				stickyPos += stickyAdjustment
			}
			stickyEl.css('top', stickyPos)
			stickyPos = headerHeight //reset stickyPos amt
		})
	}
	//Call setStickyElsPosTop() fn on load/resize
	setStickyElsPosTop()
	$(window).on('resize', debounce(() => {
		setStickyElsPosTop()
	}, 100))


	// Hide Header when it hits Footer
	const keepHeaderAboveFooter = () => {
		let header = $('#header'),
				headerTranslateY = getTranslateValues(header[0])['y'],
				headerBottomPos = header.offset().top + header.outerHeight() - headerTranslateY,
				footer = $('footer'),
				footerTopPos = footer.offset().top

		if (headerBottomPos >= footerTopPos && headerTranslateY === 0) {
			gsap.to(header, {duration:0.3, yPercent:-100})
		} else if (headerBottomPos < footerTopPos && headerTranslateY !== 0) {
			gsap.to(header, {duration:0.3, yPercent:0})
		}
	}
	//Call keepHeaderAboveFooter() fn on scroll (throttle)
	$(window).on('scroll', throttle(() => {
		keepHeaderAboveFooter()
	}, 100))


	// Header Nav Max Mega Menu - Side Content support
	const navSideTabs = $('#header .nav-side-tab')
	if (!!navSideTabs.length) {
		setTimeout(() => { //Wait for elements to exist to fire eventlistener
      navSideTabs.on('mouseover click focusin', function(e) {
				let navTabContentNum = $(this).find('[data-tab]:not([data-tab=""]').attr('data-tab')

				$(this).addClass('active').siblings('.nav-side-tab').removeClass('active')

				if (!!navTabContentNum) {
					let newNavTabContent = $(`#header .nav-side-content.${navTabContentNum}`)

					newNavTabContent.show().siblings(`.nav-side-content:not(.${navTabContentNum})`).hide()
				}
			})

			navSideTabs.on('keyup', function(e) {
				if (e.which === 13) { //Enter key
					let navTabContentNum = $(this).find('[data-tab]:not([data-tab=""]').attr('data-tab'),
							nextSibling = e.currentTarget.nextSibling,
							nextSiblingTab = $(nextSibling).hasClass('nav-side-tab')

					if (!!nextSiblingTab && !!navTabContentNum) {
						let newNavTabContent = $(`#header .nav-side-content.${navTabContentNum}`),
								firstLink = newNavTabContent.find('a[href]').first()

						firstLink.focus()
					}
				}
			})
    }, 500);
	}


	//
	// Lazy Load images
	//
	// HOW TO USE
	// 1) Add class "lazy" to the image
	// 2) Add "data-" to the src/srcset/sizes attributes
	//
	// Example:
	// <img data-src="https://via.placeholder.com/250x250" alt="Image" class="lazy">
	//
	const lazyImgs = document.getElementsByClassName('lazy'),
				lazyImgsObserverOptions = {
					rootMargin: '500px', //Pixel amount away from element before observer will fire
				};
	if (!!lazyImgs.length) {
		let observer = new IntersectionObserver(function(entries, observer) {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					let el = entry.target;
					observer.unobserve(el);

					if (!!(el instanceof HTMLImageElement) && !!el.classList.contains('lazy')) {
						el.dataset.src ? el.src = el.dataset.src : ''
						el.dataset.srcset ? el.srcset = el.dataset.srcset : ''
						el.dataset.sizes ? el.sizes = el.dataset.sizes : ''
					}
				}
			});
		}, lazyImgsObserverOptions);

		for (let img of lazyImgs) {
			observer.observe(img);
		}
	}
	// Lazy loaded background images
	//
	// HOW TO USE
	// 1) Add class "lazy-bg" to the element
	// 2) Add "data-bg" with the image url as an attribute
	//
	// Example:
	// <section class="lazy-bg" data-bg="/assets/images/background-pattern.jpg"></section>
	//
	const lazyBgEls = document.getElementsByClassName('lazy-bg'),
				lazyBgElsObserverOptions = {
					rootMargin: '500px', //Pixel amount away from element before observer will fire
				};
	if (!!lazyBgEls.length) {
		let observer = new IntersectionObserver(function(entries, observer) {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					let el = entry.target;
					observer.unobserve(el);

					el.dataset.bg ? el.style.backgroundImage = `url("${el.dataset.bg}")` : ''
					
				}
			});
		}, lazyBgElsObserverOptions);

		for (let el of lazyBgEls) {
			observer.observe(el);
		}
	}


	// Magnific Popup via buttons and target attribute [utilizes /assets/js/jquery.magnific-popup.min.js]
	// https://github.com/dimsemenov/Magnific-Popup
	let magnificPopupEls = $('[data-mfp-src]:not([data-mfp-src=""]'); //Target button with non-empty data-mfp-src attribute
	if (!!magnificPopupEls.length) {
		// Load Magnific Popup script then add popup listener to els
		(async () => {
			await themeLoadScripts([
					`${themePath}/assets/js/third-party/jquery.magnific-popup.min.js`
			]);

			magnificPopupEls.magnificPopup({
				removalDelay: 300,
				mainClass: 'mfp-with-fade', //Automatically add a class to modal that can adjust via CSS
				callbacks: {}
			});
		})();
	}
	

	// Copy to Clipboard
	$('a.copy-clipboard').each(function() {
		$(this).on('click', function(e) {
			e.preventDefault();
			
			let copyURL = $(this).attr('href'),
					tooltip = $(this).find('.copied-tooltip'),
					tooltipTL = gsap.timeline({})

			// Show Clipboard Tooltip for a few seconds (using GSAP)
			const showClipboardTooltip = (tooltipText) => {
				tooltip.text(tooltipText) //Update text

				tooltipTL.fromTo(tooltip, {opacity:0, y:-10}, {duration:0.3, opacity:1, y:0, ease: "none"})
				tooltipTL.to(tooltip, {duration: 0.5, opacity:0, ease: "none"}, '3')
			}

			// Use Clipboard API
			// https://developer.mozilla.org/en-US/docs/Web/API/Clipboard_API
			navigator.clipboard.writeText(copyURL).then(function() {
				showClipboardTooltip('Copied to clipboard')
			}, function(err) {
				showClipboardTooltip(err)
			});
		})
	})

	// Keen-Slider keyboard accessibility enhancements
	const keenSliderAccessibility = (slider) => {
		let sliderContainer = $(slider.container)
		let slidesPerView = slider.options.slides.perView
		let sliderWrapper = sliderContainer.parents('.keen-navigation-wrapper')
		
		// Left/Right arrow key support
		sliderWrapper.on('keyup', function(e) {
			if (e.which === 37) { //Left arrow
				slider.prev()
			}
			if (e.which === 39) { //Right arrow
				slider.next()
			}
		})

		// Focus active slide (set tabindex of all siblings to null then set tabindex of active so we can focus it for keyboard tabbing)
		const focusActiveSlide = () => {
			let sliderAutoplay = (sliderContainer.attr('data-autoplay') === 'true' ? true : false);
			let activeSlideNumRel = slider.track.details.rel;
			let activeSlideEl = $(slider.slides[activeSlideNumRel]);
			let x = window.scrollX;
			let y = window.scrollY;

			// If single slide carousel set visibility so hidden slides can't be tabbed
			if (slidesPerView === 1) {
				activeSlideEl.siblings().css('visibility', 'hidden');
				activeSlideEl.css('visibility', 'visible');
			}

			activeSlideEl.siblings().attr('tabIndex', '');
			activeSlideEl.attr('tabIndex', -1);

			// If slider didn't autoplay (made via custom JS per-slider) then focus active slide
			if (!sliderAutoplay) {
				activeSlideEl.focus()
				window.scrollTo(x, y) //reset scroll position so we don't scroll to focused element
			}
			//reset data attribute
			sliderContainer.attr('data-autoplay', 'false');
		}
		slider.on("slideChanged", () => {
			focusActiveSlide()
		})
	}

	// Keen-Slider navigation
	window.keenSliderNavigation = function keenSliderNavigation(slider, customArrowLeft, customArrowRight) {
		let wrapper, dots, arrows, arrowLeft, arrowRight

		// If passing custom arrows use those as variables
		if (!!customArrowLeft) {
			arrowLeft = customArrowLeft
		}
		if (!!customArrowRight) {
			arrowRight = customArrowRight
		}

		const markup = (remove) => {
			wrapperMarkup(remove)
			arrowMarkup(remove)
			dotMarkup(remove)
		}
	
		const removeElement = (elment) => {
			elment.parentNode.removeChild(elment)
		}
		const createDiv = (className) => {
			let div = document.createElement("div")
			let classNames = className.split(" ")
			classNames.forEach((name) => div.classList.add(name))
			return div
		}
		const createButton = (className) => {
			let div = document.createElement("button")
			let classNames = className.split(" ")
			classNames.forEach((name) => div.classList.add(name))
			return div
		}

		const dotMarkup = (remove) => {
			if (remove) {
				removeElement(dots)
				return
			}
			dots = createDiv("keen-dots")
			slider.track.details.slides.forEach((_e, idx) => {
				let dot = createButton("keen-dot")
				let dotNumRel = idx + 1
				dot.textContent = dotNumRel
				dot.setAttribute('type', 'button')
				dot.addEventListener("click", () => slider.moveToIdx(idx))
				dots.appendChild(dot)
			})
			wrapper.appendChild(dots)
		}
	
		const arrowMarkup = (remove) => {
			if (!customArrowLeft && !customArrowRight) {
				if (remove) {
					removeElement(arrows)
					return
				}

				let numSlides = slider.track.details.slides.length
				let numSlidesStr = numSlides.toString();
				let numSlidesPadded = numSlidesStr.padStart(2, '0'); //Change 1 into 01, 2 into 02, etc.

				arrows = createDiv("keen-nav")
				arrowLeft = createButton("keen-arrow arrow-left")
				arrowLeft.setAttribute("aria-label", "Previous item");
				arrowLeft.textContent = "←"
				arrowRight = createButton("keen-arrow arrow-right")
				arrowRight.setAttribute("aria-label", "Next item");
				arrowRight.textContent = "→"
				slideNumbers = createDiv("keen-numbers")
				slideNumbers.innerHTML = `<span class="current-slide">01</span> / <span class="total-slides">${numSlidesPadded}</span>`
		
				arrows.appendChild(arrowLeft)
				arrows.appendChild(arrowRight)
				arrows.appendChild(slideNumbers)
				wrapper.appendChild(arrows)
			}

			arrowLeft.addEventListener("click", () => slider.prev())
			arrowRight.addEventListener("click", () => slider.next())
		}
	
		const wrapperMarkup = (remove) => {
			if (remove) {
				let parent = wrapper.parentNode
				while (wrapper.firstChild)
					parent.insertBefore(wrapper.firstChild, wrapper)
					removeElement(wrapper)
				return
			}
			wrapper = createDiv("keen-navigation-wrapper")
			slider.container.parentNode.appendChild(wrapper)
			wrapper.appendChild(slider.container)
		}
	
		const updateClasses = () => {
			let slide = slider.track.details.rel
			if (slider.options.loop == false) {
				let numSlides = slider.track.details.slides.length
				let perView = slider.options.slides.perView
				let lastSlideVisible = numSlides - perView

				slide === 0
					? arrowLeft.setAttribute('disabled', true)
					: arrowLeft.removeAttribute('disabled')
				slide === lastSlideVisible
					? arrowRight.setAttribute('disabled', true)
					: arrowRight.removeAttribute('disabled')
			}
			Array.from(dots.children).forEach((dot, idx) => {
				idx === slide
					? dot.classList.add("dot-active")
					: dot.classList.remove("dot-active")
			})
		}

		const updateSlideNum = () => {
			let currentSlideTextEl = slideNumbers.querySelector('.current-slide')
			let activeSlideNum = slider.track.details.rel + 1;
			let activeSlideNumStr = activeSlideNum.toString();
			let activeSlideNumPadded = activeSlideNumStr.padStart(2, '0'); //Change 1 into 01, 2 into 02, etc.

			currentSlideTextEl.textContent = activeSlideNumPadded;
		}
	
		slider.on("created", () => {
			markup()
			updateClasses()
			keenSliderAccessibility(slider) //Add keyboard accessibility to slider
		})
		slider.on("optionsChanged", () => {
			markup(true)
			markup()
			updateClasses()
			keenSliderAccessibility(slider) //Add keyboard accessibility to slider
		})
		slider.on("slideChanged", () => {
			updateClasses()
			updateSlideNum()
		})
		slider.on("destroyed", () => {
			markup(true)
		})
	}

	// Keen - Adaptive Height carousels
	window.keenAdaptiveHeight = function keenAdaptiveHeight(slider) {
		//wrap slides in .slide-inner div
		const wrapSlides = () => {
			slider.slides.forEach((slide) => {
				let div = document.createElement("div")
				div.classList.add('slide-inner')
				while(slide.firstChild)
						div.appendChild(slide.firstChild);

				slide.appendChild(div);
			})
		}
		// Use .slide-inner divs to get real height of active slide and adjust slider container height
		const updateSliderHeight = () => {
			let currentSlideEl = slider.slides[slider.track.details.rel]

			slider.container.style.height = currentSlideEl.querySelector('.slide-inner').offsetHeight + "px"
		}

		slider.on("created", () => {
			slider.container.parentNode.classList.add('adaptive-height-carousel')
			wrapSlides()
			setTimeout(() => {
				updateSliderHeight()
			}, 500);
		})
		slider.on("slideChanged", () => {
			updateSliderHeight()
		})
		$(window).on('resize', debounce(() => {
			updateSliderHeight()
		}, 100))
	}


	// Blog/Resources page
	const blogPage = $('body.blog');
	if (!!blogPage.length) {
		let resourceStickyCarousel = blogPage.find('.sticky-posts-container .keen-slider')
		let resourceArchiveCarousels = blogPage.find('.posts-archive .keen-slider')

		// Carousel of sticky posts
		if (!!resourceStickyCarousel.length) {
			let stickyCarousel = new KeenSlider( resourceStickyCarousel[0],
				{
					loop: true,
					slides: {	perView: 1,	spacing: 30	}
				},
				[
					(slider) => {
						keenSliderNavigation(slider)
						keenAdaptiveHeight(slider)
					}
				]
			)
		}
	}

};
scriptsInit()


// Post paths that we will check for post transitions
const postPaths = [
	'/resources/articles/',
	'/resources/company-culture/',
	'/resources/infographics/',
	'/resources/press-release/',
	'/resources/product-updates/',
	'/resources/trainings/',
	'/resources/videos/',
	'/resources/webinars/',
	'/resources/white-papers/',
]

//
// Barba.js - Page transition init
//
barba.init({
  debug: true,
  transitions: [
		{
			name: 'default-transition',
			async leave(data) {
				await leaveAnimFade(data.current.container)
			},
			enter(data) {
				enterAnimFade(data.next.container)
			}
		},
		{
			name: 'post-transition',
			to: {
				custom: (data) => {
					let nextPath = data.next.url.path

					// Check if nextPath has any of the postPaths
					return postPaths.some(postPath => nextPath.includes(postPath))
				}
			},
			async leave(data) { //Transition from other page TO home
				await leaveAnimFade(data.current.container)
			},
			enter(data) { //Transition upon arriving ON home
				enterAnimPosts(data.next.container)
			}
		}
	]
})
// Barba - Leave Wipe Animation
const leaveAnimWipe = function(currentContainer) {
  let transitionEl = currentContainer.querySelector('.bjs-transition')
  let leaveTL = gsap.timeline({defaults: {duration:0.6, ease:"expo.inOut"}})

	leaveTL.fromTo(transitionEl, {left:"-100%"}, {left: 0})
	leaveTL.call(function() {
		document.documentElement.classList.add('is-transitioning')
	}, null)
	
  return leaveTL;
}
// Barba.js - Enter Wipe Animation
const enterAnimWipe = function(nextContainer) {
  let transitionEl = nextContainer.querySelector('.bjs-transition')
  let enterTL = gsap.timeline({defaults: {duration:0.6, ease:"expo.inOut"}})

	enterTL.fromTo(transitionEl, {left: 0}, {left:"100%"}, '<0.2') //Slight delay so user can tell page will be different
	enterTL.call(function() {
		document.documentElement.classList.remove('is-transitioning')
	}, null)
  
  return new Promise(resolve => {
    enterTL
    resolve()
  });
}

// Barba - Leave/Fade Animation
const leaveAnimFade = function(currentContainer) {
  let contentFooterContainers = currentContainer.querySelectorAll('#content, #footer')
  let leaveTL = gsap.timeline({defaults: {duration:0.45, ease:"expo.inOut"}})

	leaveTL.fromTo(contentFooterContainers, {opacity:1, y:0}, {opacity:0, y:15})
	leaveTL.call(function() {
		document.documentElement.classList.add('is-transitioning')
		window.scrollTo(0, 0);
	}, null)
	
  return leaveTL;
}
// Barba.js - Enter/Fade Animation
const enterAnimFade = function(nextContainer) {
  let contentFooterContainers = nextContainer.querySelectorAll('#content, #footer')
  let enterTL = gsap.timeline({defaults: {duration:0.6, ease:"sine.inOut"}})

	enterTL.fromTo(contentFooterContainers, {opacity:0, y:-15}, {opacity:1, y:0}, '<0.2') //Slight delay so user can tell page will be different
	enterTL.call(function() {
		document.documentElement.classList.remove('is-transitioning')
	}, null)
  
  return new Promise(resolve => {
    enterTL
    resolve()
  });
}

///////////////////////////////////////
// TESTING
///////////////////////////////////////

// Barba.js - Enter Posts Animation
const enterAnimPosts = function(nextContainer) {
	let pageIntro = nextContainer.querySelector('.page-intro')
	let entryContent = nextContainer.querySelector('.entry-content')
  let enterTL = gsap.timeline({defaults: {duration:0.6, ease:"sine.inOut"}})

	enterTL.fromTo(pageIntro, {opacity:0, y:-15}, {opacity:1, y:0})
	enterTL.fromTo(entryContent, {opacity:0, y:-15}, {opacity:1, y:0}, '>-0.2')
	enterTL.call(function() {
		document.documentElement.classList.remove('is-transitioning')
	}, null)
  
  return new Promise(resolve => {
    enterTL
    resolve()
  });
}


///////////////////////////////////////
// END TESTING
///////////////////////////////////////

// Global Barba events
// 
barba.hooks.beforeEnter((data) => {
	// Scroll to top of page
	window.scrollTo(0, 0);
	
	// Set <body> class to next page classes
	let newBodyClasses = data.next.html.match(/<body.+?class="([^""]*)"/i);
	document.body.setAttribute('class', (newBodyClasses && newBodyClasses.at(1)) ?? '');

	// Get all block CSS/JS from page and add to arrays we'll use
	let blockCSSJS = newHtmlDoc.querySelectorAll('[id*=ipro-block-], [id*=ipro-component-]')
	let blockCSSJSArr = []
	let blockJSArr = []
	if (!!blockCSSJS.length) {
		for (let tag of blockCSSJS) {
			let tagURL = '';
			if (!!tag.href || !!tag.getAttribute("data-pmdelayedstyle")) {
				tagURL = (!!tag.href ? tag.href : tag.getAttribute("data-pmdelayedstyle"))
				tagURL = tagURL.replace(/\?ver=(.*)/g, '')
			} else {
				tagURL = (tag.src).replace(/\?ver=(.*)/g, '')
				blockJSArr.push((tag.id).replace(/-/g, '_'))
			}
			blockCSSJSArr.push(tagURL)
		}
	}

	(async () => {
		// Refire scripts in this file
		scriptsInit()

		// Refire all Perfmatters.io delayed scripts/styles
		// pmSortDelayedScripts()
		// await pmLoadDelayedScripts(pmDelayedScripts.normal);
		// await pmLoadDelayedScripts(pmDelayedScripts.defer);
		// await pmLoadDelayedScripts(pmDelayedScripts.async);
		document.querySelectorAll("link[data-pmdelayedstyle]").forEach((e) => {
			e.setAttribute("href", e.getAttribute("data-pmdelayedstyle"));
		});

		// If any blocks with CSS/JS on page, load their assets
		if (!!blockCSSJSArr.length) {
			await themeLoadScripts(
				blockCSSJSArr
			);
		}
	})();
});