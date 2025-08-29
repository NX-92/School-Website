const openMenuBtn = document.querySelector('.open-menu-btn');
const closeMenuBtn = document.querySelector('.close-menu-btn');
const menu = document.querySelector('.menu');
const dropdowns = menu.querySelectorAll('.dropdown > i')

openMenuBtn.addEventListener('click', () => {
    openMenuBtn.classList.toggle('active');
    menu.classList.toggle('active');
    
});

closeMenuBtn.addEventListener('click', () => {
    closeMenuBtn.classList.remove('active');
    menu.classList.remove('active');
});

dropdowns.forEach((arrow) => {
    arrow.addEventListener('click', function() {
        this.closest('.dropdown').classList.toggle('active');
    });
});