import './bootstrap';
import { initMotionEngine } from './invitation/motion-engine';
import { initUiInteractions } from './invitation/ui-interactions';

document.addEventListener('DOMContentLoaded', () => {
    initMotionEngine();
    initUiInteractions();
});
