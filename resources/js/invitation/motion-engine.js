export function initMotionEngine() {
    document.querySelectorAll('.reveal-on-scroll:not([data-reveal])').forEach((element) => {
        element.dataset.reveal = 'fade-up';
    });
    const reveals = [...document.querySelectorAll('[data-reveal]')];
    if (!reveals.length) return;

    document.documentElement.classList.add('motion-ready');
    document.querySelectorAll('[data-reveal-group]').forEach((group) => {
        group.querySelectorAll('[data-reveal]').forEach((element, index) => {
            element.style.transitionDelay = `${index * 120}ms`;
        });
    });

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        reveals.forEach((element) => element.dataset.revealed = 'true');
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;
            entry.target.dataset.revealed = 'true';
            entry.target.addEventListener('transitionend', () => { entry.target.style.willChange = 'auto'; }, { once: true });
            observer.unobserve(entry.target);
        });
    }, { threshold: 0.12 });

    reveals.forEach((element) => {
        element.style.willChange = 'transform, opacity';
        observer.observe(element);
    });
}
