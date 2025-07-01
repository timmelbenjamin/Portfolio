const carouselInner = document.querySelector('.carousel-inner');
        const items = document.querySelectorAll('.carousel-item');
        let currentIndex = 0;
    
        document.getElementById('next').addEventListener('click', () => {
            currentIndex = (currentIndex + 1) % items.length;
            updateCarousel();
        });
    
        document.getElementById('prev').addEventListener('click', () => {
            currentIndex = (currentIndex - 1 + items.length) % items.length;
            updateCarousel();
        });
    
        function updateCarousel() {
            const offset = -currentIndex * 100;
            carouselInner.style.transform = `translateX(${offset}%)`;
        }


        let currentIndex_materiel = 0;

        function moveSlide_materiel(direction) {
            const slides = document.querySelector('.slides_materiel');
            const totalSlides = document.querySelectorAll('.slide_materiel').length+1;
        
            currentIndex_materiel += direction;
        
            if (currentIndex_materiel < 0) {
                currentIndex_materiel = 0;
            } else if (currentIndex_materiel > totalSlides - 5) {
                currentIndex_materiel = totalSlides - 4;
            }
        
            slides.style.transform = `translateX(-${currentIndex_materiel * 35}%)`;
        }

        let currentIndex_cours = 0;

        function moveSlide_cours(direction) {
            const slides = document.querySelector('.slides_cours');
            const totalSlides = document.querySelectorAll('.slide_cours').length+1;
        
            currentIndex_cours += direction;
        
            if (currentIndex_cours < 0) {
                currentIndex_cours = 0;
            } else if (currentIndex_cours > totalSlides - 5) {
                currentIndex_cours = totalSlides - 4;
            }
        
            slides.style.transform = `translateX(-${currentIndex_cours * 35}%)`;
        }

        let currentIndex_tuto = 0;

        function moveSlide_tuto(direction) {
            const slides = document.querySelector('.slides_tuto');
            const totalSlides = document.querySelectorAll('.slide_tuto').length+1;
        
            currentIndex_tuto += direction;
        
            if (currentIndex_tuto < 0) {
                currentIndex_tuto = 0;
            } else if (currentIndex_tuto > totalSlides - 5) {
                currentIndex_tuto = totalSlides - 4;
            }
        
            slides.style.transform = `translateX(-${currentIndex_tuto * 35}%)`;
        }


        const mobileMenu = document.getElementById('mobile-menu');
        const navUl = document.querySelector('nav ul');
    
        mobileMenu.addEventListener('click', () => {
            navUl.classList.toggle('active'); 
        });
