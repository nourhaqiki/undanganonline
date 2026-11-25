export function initMotionEngine() {
    document.querySelectorAll('.reveal-on-scroll:not([data-reveal])').forEach((element) => {
        element.dataset.reveal = 'fade-up';
    });
    const reveals = [...document.querySelectorAll('[data-reveal]')];
    if (!reveals.length) return;

    document.documentElement.classList.add('motion-ready');
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
        group.querySelectorAll('[data-reveal]').forEach((element, index) => {
            element.style.transitionDelay = `${index * 100}ms`;
        });
    });

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches || !('IntersectionObserver' in window)) {
        reveals.forEach((element) => element.dataset.revealed = 'true');
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.dataset.revealed = 'true';
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.1 });

    reveals.forEach((element) => observer.observe(element));
}
