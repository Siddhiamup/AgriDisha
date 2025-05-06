document.querySelectorAll('.faq h3').forEach(item => {
    item.addEventListener('click', () => {
        const parent = item.parentElement;
        parent.classList.toggle('active');
    });
});

document.getElementById('show-more-btn').addEventListener('click', () => {
    const extraFaqs = document.querySelector('.extra-faq');
    const showMoreBtn = document.getElementById('show-more-btn');
    extraFaqs.style.display = extraFaqs.style.display === 'block' ? 'none' : 'block';
    showMoreBtn.textContent = extraFaqs.style.display === 'block' ? 'Show Less' : 'Show More';
});
