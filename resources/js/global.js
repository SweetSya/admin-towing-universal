import { Notyf } from "notyf";
import axios from "axios";

// Initialize
const notyfOptions = {
    dismissible: true,
    position: { x: "center", y: "top" },
    duration: 3000,
    types: [
        { type: 'success', background: '#10b981', icon: false },
        { type: 'error', background: '#ef4444', icon: false },
        { type: 'warning', background: '#f59e0b', icon: false },
        { type: 'info', background: '#3b82f6', icon: false }
    ]
};

const notyf = new Notyf(notyfOptions);

// Export as default utilities
export { notyf, axios };

// Also make globally available
Object.assign(globalThis, { notyf, axios });