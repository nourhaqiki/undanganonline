const romanticEventTypes = new Set(['wedding', 'engagement']);

const butterflyMarkup = () => `
    <div class="butterfly-body-tilt">
        <svg class="butterfly-wings" viewBox="0 0 32 24" aria-hidden="true">
            <g class="wing-left"><path d="M15 12C10 2 2 3 3 10c1 6 7 7 12 4Z" fill="currentColor"/></g>
            <g class="wing-right"><path d="M17 12C22 2 30 3 29 10c-1 6-7 7-12 4Z" fill="currentColor"/></g>
            <path d="M15 8h2v10h-2z" fill="currentColor"/>
        </svg>
    </div>`;

export function initButterflySystem() {
    const eventType = document.body.dataset.eventType;
    const container = document.querySelector('[data-butterfly-container]');

    if (!romanticEventTypes.has(eventType) || !container || container.children.length) return;

    [
        ['one', '17s', '-3s', '.82', '24px', '.28s', '1.8s'],
        ['two', '19s', '-11s', '.76', '28px', '.37s', '2.1s'],
        ['three', '17s', '-7s', '.88', '22px', '.45s', '1.6s'],
    ].forEach(([path, duration, delay, opacity, size, flap, body]) => {
        const butterfly = document.createElement('div');
        butterfly.className = `butterfly-flight-path butterfly-flight-path--${path}`;
        butterfly.style.cssText = `--butterfly-opacity:${opacity};--butterfly-size:${size};--flap-duration:${flap};--body-duration:${body};animation-duration:${duration};animation-delay:${delay};color:#fbbf24;`;
        butterfly.innerHTML = butterflyMarkup();
        container.append(butterfly);
    });
}
