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

const NAV_ALIASES = {
    hero: ['hero', 'cover'],
    profil: ['profil', 'couple', 'mempelai'],
    acara: ['acara', 'event'],
    galeri: ['galeri', 'gallery'],
    ucapan: ['ucapan', 'wishes'],
};

const resolveNavSection = (key) => {
    for (const id of (NAV_ALIASES[key] || [key])) {
        const el = document.getElementById(id);
        if (el) return el;
    }
    return null;
};

export function initUiInteractions() {
    const navButtons = [...document.querySelectorAll('[data-nav-target]')];
    navButtons.forEach((button) => {
        button.addEventListener('click', () => resolveNavSection(button.dataset.navTarget)?.scrollIntoView({ behavior: 'smooth', block: 'start' }));
    });

    if (navButtons.length) {
        const idToKey = new Map();
        navButtons.forEach((button) => {
            const key = button.dataset.navTarget;
            const sec = resolveNavSection(key);
            if (sec) idToKey.set(sec, key);
        });

        const navObserver = new IntersectionObserver((entries) => {
            entries.filter((entry) => entry.isIntersecting).forEach((entry) => {
                const activeKey = idToKey.get(entry.target);
                navButtons.forEach((button) => button.classList.toggle('is-active', button.dataset.navTarget === activeKey));
            });
        }, { rootMargin: '-40% 0px -40% 0px' });
        idToKey.forEach((_, sec) => navObserver.observe(sec));
    }

    document.querySelectorAll('.btn-copy').forEach((button) => button.addEventListener('click', () => copyValue(button)));
    window.addEventListener('invitation:copy', showToast);
    window.addEventListener('invitation:opened', () => document.body.classList.add('invitation-open'), { once: true });
    window.addEventListener('invitation:music-state', (event) => document.querySelector('[data-music-disc]')?.classList.toggle('is-playing', event.detail.playing));
}
