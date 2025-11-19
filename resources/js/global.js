import { Notyf } from "notyf";
import axios from "axios";

// Initialize
const notyfOptions = {
    dismissible: true,
    position: { x: "center", y: "bottom" },
    duration: 3000,
    types: [
        { type: "success", background: "#10b981", icon: false },
        { type: "error", background: "#ef4444", icon: false },
        { type: "warning", background: "#f59e0b", icon: false },
        { type: "info", background: "#3b82f6", icon: false },
    ],
};

const notyf = new Notyf(notyfOptions);
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


// Export as default utilities
export { notyf, axios };

// Also make globally available
Object.assign(globalThis, { notyf, axios });
