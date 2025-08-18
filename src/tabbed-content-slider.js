document.addEventListener('DOMContentLoaded', function() {



  // get window width


  function homepageHero(){
    const windowWidth = window.innerWidth;


  
      const hero = document.getElementById('home_hero');
  
      if (hero) {
          const video = hero.querySelector('.kb-blocks-bg-video');
          const videoSrc = video.getAttribute('src');
          const mobileSrc = "https://cbsteam.mystagingwebsite.com/wp-content/uploads/mobile-video-sm.mp4";
          
          if (windowWidth < 768) {
            //setTimeout(() => {
              // If on mobile, change the video source
                video.setAttribute('src', mobileSrc);
                video.load(); // Reload the video with the new source
            //}, 1000); // Delay to ensure the video is loaded
          }
      }
  }
  homepageHero();



    //console.log('[CustomSlider] DOM loaded — initializing Swipers…');


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

  });
  