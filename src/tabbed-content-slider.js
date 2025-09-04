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
          const currentSrc = videoElement.getAttribute('src') || videoElement.src;
          console.log('Current src:', currentSrc, 'New src:', src); // Debug log
          
          // Only update if the source has changed
          if (currentSrc !== src) {
            console.log('Updating video source to:', src); // Debug log
            
            // Pause the video first
            videoElement.pause();
            
            // Set the new source
            videoElement.setAttribute('src', src);
            videoElement.src = src; // Set both ways for better compatibility
            
            // Force reload
            videoElement.load();
            
            // Try to play, but don't worry if it fails
            const playPromise = videoElement.play();
            if (playPromise !== undefined) {
              playPromise.then(() => {
                console.log('Video playing successfully');
              }).catch(err => {
                console.log("Autoplay blocked or failed:", err);
                // Video will still be loaded with correct src, just not playing
              });
            }
          }
        }
      }
    }
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', homepageHero);
} else {
  homepageHero();
}

  // Optional: run again on resize
  let resizeTimeout;
  window.addEventListener('resize', () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(() => {
      homepageHero();
    }, 250); // Debounce resize calls
  });


  


document.addEventListener('DOMContentLoaded', function() {

  // query #home_hero

  const home = document.querySelector('body.home') ? true : false;

  if(home){

      homepageHero();

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
    
          // const industriesCtaButtonSwiperMobile = new Swiper('.industries_slider_cta_buttons_mobile .swiper', {
          //   loop: false,
          //   speed: 500,
          //   effect: "slide",
          //   allowTouchMove: false,
          // });
        
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
              //industriesCtaButtonSwiperMobile.slideTo(index);
        
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
  

