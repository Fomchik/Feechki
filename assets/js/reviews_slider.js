document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('.reviews-slider');
    const slides = document.querySelectorAll('.reviews-slide');
    const prevArrow = document.querySelector('#reviews-prev');
    const nextArrow = document.querySelector('#reviews-next');
    let currentIndex = 0;

    // Инициализация слайдера
    function initSlider() {
        if (slides.length > 0) {
            // Скрываем все слайды, кроме первого
            slides.forEach((slide, index) => {
                if (index === 0) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }
    }

    function updateSliderPosition() {
        if (slides.length > 0) {
            // Просто переключаем классы active для плавного появления
            slides.forEach((slide, index) => {
                if (index === currentIndex) {
                    slide.classList.add('active');
                } else {
                    slide.classList.remove('active');
                }
            });
        }
    }

    // Обработка нажатий на стрелки
    if (prevArrow) {
        prevArrow.addEventListener('click', function(e) {
            e.preventDefault();
            // Исправленное направление
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : slides.length - 1;
            updateSliderPosition();
        });
    }

    if (nextArrow) {
        nextArrow.addEventListener('click', function(e) {
            e.preventDefault();
            // Исправленное направление
            currentIndex = (currentIndex < slides.length - 1) ? currentIndex + 1 : 0;
            updateSliderPosition();
        });
    }

    // Инициализация слайдера
    initSlider();
});