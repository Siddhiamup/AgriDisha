// Enhanced DOM Elements
const header = document.querySelector('.header-container');
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const navLinks = document.getElementById('navLinks');
const searchInput = document.getElementById('searchInput');
const searchButton = document.getElementById('searchButton');
const languageSelector = document.getElementById('languageSelector');

// Toast Notification System
class ToastNotification {
    constructor() {
        this.toastContainer = document.createElement('div');
        this.toastContainer.className = 'toast';
        document.body.appendChild(this.toastContainer);
    }

    show(message, type = 'info', duration = 3000) {
        this.toastContainer.textContent = message;
        this.toastContainer.className = `toast show ${type}`;
        
        setTimeout(() => {
            this.toastContainer.className = 'toast';
        }, duration);
    }
}

const toast = new ToastNotification();

// Enhanced Mobile Menu
class MobileMenu {
    constructor(btn, nav) {
        this.button = btn;
        this.nav = nav;
        this.isOpen = false;
        this.init();
    }

    init() {
        this.button.addEventListener('click', () => this.toggle());
        document.addEventListener('click', (e) => this.handleClickOutside(e));
        window.addEventListener('resize', () => this.handleResize());
    }

    toggle() {
        this.isOpen = !this.isOpen;
        this.nav.classList.toggle('active');
        this.button.classList.toggle('active');
        this.animateButton();
    }

    animateButton() {
        const spans = this.button.querySelectorAll('span');
        if (this.isOpen) {
            spans[0].style.transform = 'rotate(45deg) translate(6px, 6px)';
            spans[1].style.opacity = '0';
            spans[2].style.transform = 'rotate(-45deg) translate(6px, -6px)';
        } else {
            spans[0].style.transform = 'none';
            spans[1].style.opacity = '1';
            spans[2].style.transform = 'none';
        }
    }

    handleClickOutside(event) {
        if (!this.nav.contains(event.target) && 
            !this.button.contains(event.target) && 
            this.isOpen) {
            this.toggle();
        }
    }

    handleResize() {
        if (window.innerWidth > 768 && this.isOpen) {
            this.toggle();
        }
    }
}

// Enhanced Search System
class SearchSystem {
    constructor(input, button) {
        this.input = input;
        this.button = button;
        this.timeout = null;
        this.init();
    }

    init() {
        this.input.addEventListener('input', (e) => this.handleInput(e));
        this.button.addEventListener('click', () => this.performSearch());
        this.input.addEventListener('keypress', (e) => this.handleKeyPress(e));
    }

    handleInput(event) {
        clearTimeout(this.timeout);
        this.timeout = setTimeout(() => {
            this.showSuggestions(event.target.value);
        }, 300);
    }

    async showSuggestions(query) {
        if (query.length < 2) return;
        
        try {
            // Simulate API call
            const suggestions = await this.fetchSuggestions(query);
            this.renderSuggestions(suggestions);
        } catch (error) {
            console.error('Error fetching suggestions:', error);
        }
    }

    async fetchSuggestions(query) {
        // Implement your suggestion fetching logic here
        return ['Suggestion 1', 'Suggestion 2', 'Suggestion 3'];
    }

    renderSuggestions(suggestions) {
        // Implement your suggestion rendering logic here
    }

    async performSearch() {
        const query = this.input.value.trim();
        if (!query) return;

        this.button.classList.add('loading');
        
        try {
            // Implement your search logic here
            await new Promise(resolve => setTimeout(resolve, 1000)); // Simulate API call
            toast.show(`Search completed for: ${query}`);
        } catch (error) {
            toast.show('Search failed. Please try again.', 'error');
        } finally {
            this.button.classList.remove('loading');
        }
    }

    handleKeyPress(event) {
        if (event.key === 'Enter') {
            this.performSearch();
        }
    }
}

// Enhanced Header System
class HeaderSystem {
    constructor(header) {
        this.header = header;
        this.lastScroll = 0;
        this.init();
    }

    init() {
        window.addEventListener('scroll', () => this.handleScroll());
    }

    handleScroll() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll <= 0) {
            this.header.classList.remove('scroll-up');
            return;
        }
        
        if (currentScroll > this.lastScroll && !this.header.classList.contains('scroll-down')) {
            this.header.classList.remove('scroll-up');
            this.header.classList.add('scroll-down');
        } else if (currentScroll < this.lastScroll && this.header.classList.contains('scroll-down')) {
            this.header.classList.remove('scroll-down');
            this.header.classList.add('scroll-up');
        }
        
        this.lastScroll = currentScroll;
    }
}

// Initialize Systems
document.addEventListener('DOMContentLoaded', () => {
    const mobileMenu = new MobileMenu(mobileMenuBtn, navLinks);
    const searchSystem = new SearchSystem(searchInput, searchButton);
    const headerSystem = new HeaderSystem(header);
    
    // Initialize language preferences
    const savedLanguage = localStorage.getItem('preferred-language');
    if (savedLanguage) {
        languageSelector.value = savedLanguage;
    }
    
    // Handle language changes
    languageSelector.addEventListener('change', (e) => {
        const selectedLanguage = e.target.value;
        localStorage.setItem('preferred-language', selectedLanguage);
        toast.show(`Language changed to: ${selectedLanguage}`);
    });
});

// Add intersection observer for animations
const observeElements = () => {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));
};

observeElements();