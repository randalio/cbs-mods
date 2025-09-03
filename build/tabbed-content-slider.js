/******/ (() => { // webpackBootstrap
/*!**************************************!*\
  !*** ./src/tabbed-content-slider.js ***!
  \**************************************/
function homepageHero() {
  console.log('homepageHero');
  const windowWidth = window.innerWidth;
  const hero = document.getElementById('home_hero');
  if (hero) {
    const dataVideoLg = hero.getAttribute('data-video-lg');
    const dataVideoSm = hero.getAttribute('data-video-sm');

    // Use existing video container
    const existingVideoContainer = hero.querySelector('.kb-blocks-bg-video-container');
    if (existingVideoContainer) {
      const videoElement = existingVideoContainer.querySelector('.kb-blocks-bg-video');
      if (videoElement) {
        // Determine which source to use based on screen size
        const src = windowWidth > 500 && dataVideoLg ? dataVideoLg : dataVideoSm;
        console.log('Window width:', windowWidth, 'Selected src:', src); // Debug log

        if (src) {
          const currentSrc = videoElement.getAttribute('src');
          console.log('Current src:', currentSrc, 'New src:', src); // Debug log

          // Only update if the source has changed
          if (currentSrc !== src) {
            console.log('Updating video source to:', src); // Debug log
            videoElement.setAttribute('src', src);

            // Reload and play the video with new source
            videoElement.load();
            videoElement.play().catch(err => {
              console.warn("Autoplay blocked:", err);
            });
          }
        }
      }
    }
  }
}
document.addEventListener('DOMContentLoaded', function () {
  // query #home_hero

  const home = document.querySelector('body.home') ? true : false;
  if (home) {
    homepageHero();

    // Optional: run again on resize
    window.addEventListener('resize', homepageHero);

    // Initialize your Swipers
    const servicesContentSwiper = new Swiper('.services_slider_content .swiper', {
      allowTouchMove: false,
      speed: 500
    });
    const servicesImageSwiper = new Swiper('.services_slider_image .swiper', {
      allowTouchMove: false,
      loop: false,
      speed: 500,
      effect: "fade"
    });

    //console.log('[CustomSlider] Swipers initialized:', servicesContentSwiper, servicesImageSwiper);

    // Navigation buttons
    const servicesButtons = document.querySelectorAll('#services_slider .slider-navigation-buttons .button');
    //console.log(`[CustomSlider] Found ${servicesButtons.length} navigation buttons:`, servicesButtons);

    servicesButtons.forEach((button, index) => {
      button.addEventListener('click', e => {
        e.preventDefault();
        //console.log(`[CustomSlider] Button ${index + 1} clicked → going to slide ${index}`);

        // Go to the matching slide in both Swipers
        servicesContentSwiper.slideTo(index);
        servicesImageSwiper.slideTo(index);

        // Active state handling
        servicesButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
      });
    });

    // Set first button as active
    if (servicesButtons.length > 0) {
      //console.log('[CustomSlider] Setting first button as active.');
      servicesButtons[0].classList.add('active');
    }

    // Initialize your Swipers
    const industriesContentSwiper = new Swiper('.industries_slider_content .swiper', {
      loop: false,
      speed: 500,
      effect: "fade",
      allowTouchMove: false
    });
    const industriesCtaButtonSwiper = new Swiper('.industries_slider_cta_buttons .swiper', {
      loop: false,
      speed: 500,
      effect: "fade",
      allowTouchMove: false
    });
    const industriesCtaButtonSwiperMobile = new Swiper('.industries_slider_cta_buttons_mobile .swiper', {
      loop: false,
      speed: 500,
      effect: "fade",
      allowTouchMove: false
    });

    //console.log('[CustomSlider] Swipers initialized:', industriesContentSwiper, industriesCtaButtonSwiper);

    // Navigation buttons
    const industriesButtons = document.querySelectorAll('#industries_slider .slider-navigation-buttons .button');
    //console.log(`[CustomSlider] Found ${industriesButtons.length} navigation buttons:`, industriesButtons);

    industriesButtons.forEach((button, index) => {
      button.addEventListener('click', e => {
        e.preventDefault();
        //console.log(`[CustomSlider] Button ${index + 1} clicked → going to slide ${index}`);

        // Go to the matching slide in both Swipers
        industriesContentSwiper.slideTo(index);
        industriesCtaButtonSwiper.slideTo(index);
        industriesCtaButtonSwiperMobile.slideTo(index);

        // Active state handling
        industriesButtons.forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
      });
    });

    // Set first button as active
    if (industriesButtons.length > 0) {
      //console.log('[CustomSlider] Setting first button as active.');
      industriesButtons[0].classList.add('active');
    }
  }
});
/******/ })()
;
//# sourceMappingURL=tabbed-content-slider.js.map