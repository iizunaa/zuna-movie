document.querySelector('.scroll-right').addEventListener('click', function() {
    document.querySelector('.film-row').scrollLeft += 200;
});

document.querySelector('.scroll-left').addEventListener('click', function() {
    document.querySelector('.film-row').scrollLeft -= 200;
});
