import {Lightbox} from "./Lightbox";
import * as Util from "../Util";

export class PhotonicLightGallery extends Lightbox {
	constructor() {
		super();
	}

	soloImages() {
		const a = document.querySelectorAll('a[href]');
		const solos = Array.from(a).filter(elem => /(\.jpg|\.jpeg|\.bmp|\.gif|\.png)/i.test(elem.getAttribute('href')))
			.filter(elem => !elem.classList.contains('photonic-lb'));
		solos.forEach(solo => {
			solo.classList.add("photonic-" + Photonic_JS.slideshow_library);
			solo.classList.add(Photonic_JS.slideshow_library);
			solo.setAttribute('rel', 'photonic-' + Photonic_JS.slideshow_library);
		});
	};

	changeVideoURL(element, regular, embed) {
		element.setAttribute('href', regular);
	};

	hostedVideo(a) {
		const html5 = a.getAttribute('href').match(new RegExp(/(\.mp4|\.webm|\.ogg)/i)),
			css = a.classList.contains('photonic-lb');

		if (html5 !== null && !css) {
			a.classList.add(Photonic_JS.slideshow_library + "-html5-video");
			let videos = document.querySelector('#photonic-html5-videos');
			if (videos == null) {
				videos = document.createElement('div');
				videos.innerHTML = '<div style="display:none;" id="photonic-html5-videos"></div>';
				document.body.appendChild(videos);
			}
			videos.insertAdjacentHTML('beforeend', '<div id="photonic-html5-video-' + this.videoIndex + '"><video class="lg-video-object lg-html5" controls preload="none"><source src="' + a.getAttribute('href') + '" type="video/mp4">Your browser does not support HTML5 video.</video></div>');

			a.setAttribute('data-html5-href', a.getAttribute('href'));
			a.setAttribute('href', '');
			a.setAttribute('data-html', '#photonic-html5-video-' + this.videoIndex);
			a.setAttribute('data-sub-html', (a.getAttribute('title') ? a.getAttribute('title') : ''));

			this.videoIndex++;
		}
	};

	initialize(selector, selfSelect) {
		this.handleSolos();
		const self = this;

		let selection;
		if (selector instanceof NodeList) {
			selection = selector;
		}
		else if (selector instanceof Element) {
			selection = [selector];
		}
		else {
			selection = document.querySelectorAll(selector);
		}

		selection.forEach(function (current) {
			const lguid = current.getAttribute('lg-uid');
			if (lguid != null && lguid !== '') {
				window.lgData[lguid].destroy(true);
			}

			let thumbs;
			thumbs = current.querySelectorAll('a.photonic-lightgallery');
			let rel = '';
			if (thumbs.length > 0) {
				rel = thumbs[0].getAttribute('rel');
			}

			current.addEventListener('onAfterSlide', event => {
				thumbs = current.querySelectorAll('a.photonic-lightgallery'); // Need to fetch again, since the next line causes issues after "Add More".
				let thumb;
				if (thumbs.length !== 0) {
					thumb = thumbs[event.detail.index];
				}
				else if (current.classList.contains('photonic-lightgallery')) {
					thumb = current;
				}

				if (thumb != null) {
					self.setHash(thumb);
					const shareable = {
						'url': location.href,
						'title': Util.getText(thumb.getAttribute('data-title')),
						'image': thumb.getAttribute('href')
					};
					self.addSocial('.lg-toolbar', shareable);
				}
			}, false);

			current.addEventListener('onCloseAfter', function () {
				self.unsetHash();
			});

			const lightbox = lightGallery(current,{
				selector: (selfSelect === undefined || !selfSelect) ? 'a[rel="' + rel + '"]' : 'this',
				counter: selfSelect === undefined || !selfSelect,
				pause: Photonic_JS.slideshow_interval,
				mode: Photonic_JS.lg_transition_effect,
				download: Photonic_JS.lg_enable_download,
				loop: Photonic_JS.lightbox_loop,
				hideBarsDelay: Photonic_JS.lg_hide_bars_delay,
				speed: Photonic_JS.lg_transition_speed,
				getCaptionFromTitleOrAlt: false
			});
		});
	};

	initializeForNewContainer(containerId) {
		this.initialize(containerId);
	};
}
