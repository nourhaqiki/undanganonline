import { initButterflySystem } from './butterfly-system';

const showToast = () => {
    const toast = document.querySelector('[data-copy-toast]');
    if (!toast) return;
    toast.classList.add('is-visible');
    window.setTimeout(() => toast.classList.remove('is-visible'), 2500);
};

const copyValue = (button) => {
    const target = button.dataset.copyTarget || '';
    const element = target.startsWith('#') ? document.querySelector(target) : null;
    const value = element ? element.textContent.trim() : target;
    if (!value) return;
    navigator.clipboard?.writeText(value).catch(() => {});
    showToast();
};

export function initUiInteractions() {
    document.querySelectorAll('[data-nav-target]').forEach((button) => {
        button.addEventListener('click', () => document.getElementById(button.dataset.navTarget)?.scrollIntoView({ behavior: 'smooth' }));
    });

    const navObserver = new IntersectionObserver((entries) => {
        entries.filter((entry) => entry.isIntersecting).forEach((entry) => {
            document.querySelectorAll('[data-nav-target]').forEach((button) => {
                button.classList.toggle('is-active', button.dataset.navTarget === entry.target.id);
            });
        });
    }, { rootMargin: '-40% 0px -40% 0px' });
    document.querySelectorAll('[data-nav-target]').forEach((button) => {
        const section = document.getElementById(button.dataset.navTarget);
        if (section) navObserver.observe(section);
    });

    document.querySelectorAll('.btn-copy').forEach((button) => button.addEventListener('click', () => copyValue(button)));
    window.addEventListener('invitation:copy', showToast);
    window.addEventListener('invitation:opened', () => {
        document.body.classList.add('invitation-open');
        initButterflySystem();
    }, { once: true });
    window.addEventListener('invitation:music-state', (event) => document.querySelector('[data-music-disc]')?.classList.toggle('is-playing', event.detail.playing));
}
