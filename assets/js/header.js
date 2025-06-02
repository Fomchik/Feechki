// JS для открытия/закрытия мобильного меню

document.addEventListener('DOMContentLoaded', function() {
    // Находим элементы бургера и меню
    const navToggle = document.querySelector('.nav-toggle');
    const navList = document.querySelector('.nav-list');

    if (navToggle && navList) {
        navToggle.addEventListener('click', function() {
            navList.classList.toggle('open');
        });
    }
}); 