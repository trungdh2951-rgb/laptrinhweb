document.addEventListener('DOMContentLoaded', () => {
    const currentPath = window.location.pathname;
    document.querySelectorAll('.main-nav a').forEach((link) => {
        if (currentPath.endsWith(link.getAttribute('href'))) {
            link.style.fontWeight = '700';
            link.style.color = '#ffffff';
        }
    });

    // Premium Slider Logic
    const track = document.getElementById('mainSliderTrack');
    if (track) {
        const slides = track.querySelectorAll('.slider-slide');
        const prevBtn = document.getElementById('sliderPrevBtn');
        const nextBtn = document.getElementById('sliderNextBtn');
        const dotsContainer = document.getElementById('sliderDots');
        
        let currentIndex = 0;
        const totalSlides = slides.length;
        let slideInterval;

        let startX = 0;
        let isDragging = false;
        let currentTranslate = 0;
        let prevTranslate = 0;

        // Create dots
        slides.forEach((_, index) => {
            const dot = document.createElement('div');
            dot.classList.add('slider-dot');
            if (index === 0) dot.classList.add('active');
            dot.addEventListener('click', () => goToSlide(index));
            dotsContainer.appendChild(dot);
        });

        const dots = dotsContainer.querySelectorAll('.slider-dot');

        function updateSlider() {
            track.style.transition = 'transform 0.5s cubic-bezier(0.25, 1, 0.5, 1)';
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach(dot => dot.classList.remove('active'));
            dots[currentIndex].classList.add('active');
            prevTranslate = -currentIndex * track.clientWidth;
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentIndex = (currentIndex - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function goToSlide(index) {
            currentIndex = index;
            updateSlider();
            resetInterval();
        }

        function resetInterval() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 5000);
        }

        if (nextBtn) nextBtn.addEventListener('click', () => {
            nextSlide();
            resetInterval();
        });

        if (prevBtn) prevBtn.addEventListener('click', () => {
            prevSlide();
            resetInterval();
        });

        // Touch & Drag events
        track.addEventListener('mousedown', dragStart);
        track.addEventListener('touchstart', dragStart, {passive: true});
        track.addEventListener('mousemove', dragAction);
        track.addEventListener('touchmove', dragAction, {passive: true});
        track.addEventListener('mouseup', dragEnd);
        track.addEventListener('mouseleave', dragEnd);
        track.addEventListener('touchend', dragEnd);

        function dragStart(e) {
            if (e.type.includes('mouse')) e.preventDefault();
            startX = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
            isDragging = true;
            resetInterval();
            track.style.transition = 'none'; // Disable transition while dragging
        }

        function dragAction(e) {
            if (!isDragging) return;
            const currentPosition = e.type.includes('mouse') ? e.pageX : e.touches[0].clientX;
            const diff = currentPosition - startX;
            currentTranslate = prevTranslate + diff;
            track.style.transform = `translateX(${currentTranslate}px)`;
        }

        function dragEnd() {
            if (!isDragging) return;
            isDragging = false;
            const movedBy = currentTranslate - prevTranslate;

            // if moved enough -> change slide
            if (movedBy < -100 && currentIndex < totalSlides - 1) currentIndex += 1;
            else if (movedBy > 100 && currentIndex > 0) currentIndex -= 1;
            else if (movedBy < -100 && currentIndex === totalSlides - 1) currentIndex = 0; // Wrap around to start
            else if (movedBy > 100 && currentIndex === 0) currentIndex = totalSlides - 1; // Wrap around to end

            updateSlider();
            resetInterval();
        }
        
        // Init calculate
        setTimeout(() => {
            prevTranslate = -currentIndex * track.clientWidth;
        }, 100);

        // Start auto slide
        slideInterval = setInterval(nextSlide, 5000);
    }
});
