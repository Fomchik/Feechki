document.addEventListener('DOMContentLoaded', function() {
  const sliderContainer = document.querySelector('.gallery-slider');
  const dotsContainer = document.querySelector('.slider-dots');
  const prevBtn = document.querySelector('#gallery-prev');
  const nextBtn = document.querySelector('#gallery-next');
  const filterBtns = document.querySelectorAll('.filter-btn');
  let allImages = [], filteredImages = [], currentIndex = 0, slideInterval;

  function loadGalleryImages() {
    fetch('/includes/get_gallery_images.php')
      .then(res => res.json())
      .then(data => {
        allImages = data.images || [];
        filteredImages = [...allImages];
        createSlides();
        showSlide(0);
        startAutoSlide();
      })
      .catch(() => {
        sliderContainer.innerHTML = '<div class="error">Ошибка загрузки галереи</div>';
      });
  }

  function createSlides() {
    sliderContainer.innerHTML = '';
    dotsContainer.innerHTML = '';

    filteredImages.forEach((imgObj, idx) => {
      const slide = document.createElement('div');
      slide.className = 'slider-slide';
      // Добавляем data-category атрибут
      slide.setAttribute('data-category', imgObj.category);
      slide.innerHTML = `<img src="${imgObj.path}" alt="${imgObj.alt}">`;
      sliderContainer.appendChild(slide);

      const dot = document.createElement('span');
      dot.className = 'dot';
      dot.dataset.index = idx;
      dot.addEventListener('click', () => {
        currentIndex = idx;
        showSlide(idx);
        startAutoSlide();
      });
      dotsContainer.appendChild(dot);
    });
  }

  function showSlide(idx) {
    document.querySelectorAll('.slider-slide').forEach((s, i) =>
      s.classList.toggle('active', i === idx));
    document.querySelectorAll('.dot').forEach((d, i) =>
      d.classList.toggle('active', i === idx));
  }

  function filterImages(category) {
    filteredImages = category === 'all' 
      ? [...allImages] 
      : allImages.filter(i => i.category === category);

    currentIndex = 0;
    createSlides();
    showSlide(0);
    startAutoSlide();
  }

  function startAutoSlide() {
    clearInterval(slideInterval);
    slideInterval = setInterval(() => {
      currentIndex = (currentIndex + 1) % filteredImages.length;
      showSlide(currentIndex);
    }, 5000);
  }

  prevBtn.addEventListener('click', e => {
    e.preventDefault();
    currentIndex = (currentIndex - 1 + filteredImages.length) % filteredImages.length;
    showSlide(currentIndex);
    startAutoSlide();
  });

  nextBtn.addEventListener('click', e => {
    e.preventDefault();
    currentIndex = (currentIndex + 1) % filteredImages.length;
    showSlide(currentIndex);
    startAutoSlide();
  });

  filterBtns.forEach(btn => btn.addEventListener('click', e => {
    e.preventDefault();
    filterBtns.forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    filterImages(btn.dataset.filter);
  }));

  const sliderWrap = document.querySelector('.gallery-slider-container');
  sliderWrap.addEventListener('mouseenter', () => clearInterval(slideInterval));
  sliderWrap.addEventListener('mouseleave', () => startAutoSlide());

  document.addEventListener('keydown', e => {
    if (e.key === 'ArrowLeft') prevBtn.click();
    if (e.key === 'ArrowRight') nextBtn.click();
  });

  loadGalleryImages();
});