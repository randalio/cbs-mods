function homepageHero() {
  console.log('homepageHero');
  const windowWidth = window.innerWidth;
  const hero = document.getElementById('home_hero');

  if (hero) {
    const dataVideoLg = hero.getAttribute('data-video-lg');
    const dataVideoSm = hero.getAttribute('data-video-sm');

    const existingVideoContainer = hero.querySelector('.kb-blocks-bg-video-container');

    if (!existingVideoContainer) {
      const videoContainer = document.createElement('div');
      videoContainer.classList.add('kb-blocks-bg-video-container');
      hero.prepend(videoContainer);

      const videoElement = document.createElement('video');
      videoElement.classList.add('kb-blocks-bg-video');
      videoElement.setAttribute('autoplay', 'autoplay');
      videoElement.setAttribute('muted', 'muted');
      videoElement.setAttribute('loop', 'loop');
      videoElement.setAttribute('playsinline', '');
      videoContainer.appendChild(videoElement);

      // pick correct source
      const src = windowWidth < 768 && dataVideoSm ? dataVideoSm : dataVideoLg;

      if (src) {
        const sourceElement = document.createElement('source');
        sourceElement.setAttribute('src', src);
        sourceElement.setAttribute('type', 'video/mp4');
        videoElement.appendChild(sourceElement);

        // Ensure the video reloads and tries to autoplay
        videoElement.load();
        videoElement.play().catch(err => {
          console.warn("Autoplay blocked:", err);
        });
      }
    }
  }
}


document.addEventListener('DOMContentLoaded', function() {

  // query #home_hero

  const home = document.querySelector('body.home') ? true : false;

  if(home){

    //setTimeout( function(){
      homepageHero();
    ///}, 1000);

  
    // Optional: run again on resize
    window.addEventListener('resize', homepageHero);


        // Initialize your Swipers
        const servicesContentSwiper = new Swiper('.services_slider_content .swiper', {
          allowTouchMove: false,
          speed: 500,
        });
      
        const servicesImageSwiper = new Swiper('.services_slider_image .swiper', {
          allowTouchMove: false,
          loop: false,
          speed: 500,
          effect: "fade",
        });
    
      
        //console.log('[CustomSlider] Swipers initialized:', servicesContentSwiper, servicesImageSwiper);
      
        // Navigation buttons
        const servicesButtons = document.querySelectorAll('#services_slider .slider-navigation-buttons .button');
        //console.log(`[CustomSlider] Found ${servicesButtons.length} navigation buttons:`, servicesButtons);
      
        servicesButtons.forEach((button, index) => {
          button.addEventListener('click', (e) => {
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
            allowTouchMove: false,
          });
        
          const industriesCtaButtonSwiper = new Swiper('.industries_slider_cta_buttons .swiper', {
            loop: false,
            speed: 500,
            effect: "fade",
            allowTouchMove: false,
          });
    
          const industriesCtaButtonSwiperMobile = new Swiper('.industries_slider_cta_buttons_mobile .swiper', {
            loop: false,
            speed: 500,
            effect: "fade",
            allowTouchMove: false,
          });
        
          //console.log('[CustomSlider] Swipers initialized:', industriesContentSwiper, industriesCtaButtonSwiper);
        
          // Navigation buttons
          const industriesButtons = document.querySelectorAll('#industries_slider .slider-navigation-buttons .button');
          //console.log(`[CustomSlider] Found ${industriesButtons.length} navigation buttons:`, industriesButtons);
        
          industriesButtons.forEach((button, index) => {
            button.addEventListener('click', (e) => {
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
  

