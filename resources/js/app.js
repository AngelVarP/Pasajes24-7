// resources/js/app.js

// Importa bootstrap.js que configura Axios globalmente
// This assumes bootstrap.js is correctly setting up window.axios
import './bootstrap';
// Importa los estilos CSS (Tailwind)
import '../css/app.css';

console.log("Pasajes24/7 Frontend Ready! 👍");

// --- DOM Element Selections ---
const searchForm = document.getElementById('search-form');
const origenInput = document.getElementById('origen');
const destinoInput = document.getElementById('destino');
const fechaInput = document.getElementById('fecha');
const searchError = document.getElementById('search-error');
const origenSuggestions = document.getElementById('origen-suggestions');
const destinoSuggestions = document.getElementById('destino-suggestions');

// --- Simple Frontend Form Validation ---
if (searchForm && origenInput && destinoInput && fechaInput && searchError) {
    searchForm.addEventListener('submit', function(event) {
        // Hide previous errors
        searchError.classList.add('hidden');
        searchError.textContent = '';
        let isValid = true;

        // Simple validation: origin and destination cannot be the same (case-insensitive)
        const origenValue = origenInput.value.trim().toLowerCase();
        const destinoValue = destinoInput.value.trim().toLowerCase();

        if (origenValue === destinoValue && origenValue !== '') {
            searchError.textContent = 'El origen y el destino no pueden ser iguales.';
            searchError.classList.remove('hidden');
            origenInput.classList.add('border-red-500'); // Add error styling
            destinoInput.classList.add('border-red-500');
            isValid = false;
        } else {
             origenInput.classList.remove('border-red-500'); // Remove error styling
             destinoInput.classList.remove('border-red-500');
        }

        // Simple validation: date should not be empty (though 'required' helps)
        if (!fechaInput.value) {
            // Could add a specific message if needed
            fechaInput.classList.add('border-red-500');
            isValid = false;
        } else {
            fechaInput.classList.remove('border-red-500');
        }

        // Prevent form submission if invalid
        if (!isValid) {
            event.preventDefault();
            console.error("Search form validation errors.");
        } else {
            // Optional: Show a loading indicator
            console.log("Form is valid, submitting...");
            // You might want to disable the submit button here to prevent double submission
            // searchForm.querySelector('button[type="submit"]').disabled = true;
            // searchForm.querySelector('button[type="submit"]').textContent = 'Buscando...';
        }
    });
} else {
    console.warn("Could not find all required elements for search form validation.");
}

// --- Autocomplete Logic ---

/**
 * Debounce function to limit API calls while typing.
 * @param {Function} func - The function to debounce.
 * @param {number} delay - The debounce delay in milliseconds.
 * @returns {Function} - The debounced function.
 */
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
}

/**
 * Fetches city suggestions from the API.
 * @param {string} query - The search term.
 * @param {HTMLElement} suggestionBox - The DOM element to display suggestions in.
 */
async function fetchSuggestions(query, suggestionBox) {
    const targetInput = suggestionBox.id.includes('origen') ? origenInput : destinoInput;

    if (query.length < 2) { // Don't search for less than 2 characters
        suggestionBox.innerHTML = '';
        suggestionBox.classList.add('hidden');
        return;
    }

    try {
        // !!! Ensure this URL matches your API endpoint !!!
        const response = await axios.get(`/api/ciudades/buscar?q=${encodeURIComponent(query)}`);
        const ciudades = response.data; // Expects an array of strings

        suggestionBox.innerHTML = ''; // Clear previous suggestions

        if (ciudades && Array.isArray(ciudades) && ciudades.length > 0) {
            ciudades.forEach(ciudad => {
                const div = document.createElement('div');
                div.textContent = ciudad;
                // Tailwind classes for styling suggestions
                div.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer', 'text-sm', 'text-gray-700', 'text-left');
                div.addEventListener('click', () => {
                    targetInput.value = ciudad; // Fill input
                    suggestionBox.innerHTML = ''; // Clear suggestions
                    suggestionBox.classList.add('hidden'); // Hide box
                });
                suggestionBox.appendChild(div);
            });
            suggestionBox.classList.remove('hidden'); // Show suggestions box
        } else {
            // Optional: Show "No results found" message
            /*
            const noResultsDiv = document.createElement('div');
            noResultsDiv.textContent = 'No se encontraron ciudades.';
            noResultsDiv.classList.add('p-2', 'text-sm', 'text-gray-500', 'text-left');
            suggestionBox.appendChild(noResultsDiv);
            suggestionBox.classList.remove('hidden');
            */
            suggestionBox.classList.add('hidden'); // Or just hide it
        }
    } catch (error) {
        console.error('Error fetching suggestions:', error);
        suggestionBox.innerHTML = '';
        suggestionBox.classList.add('hidden');
    }
}

// Debounced version of fetchSuggestions to limit API calls
const debouncedFetchSuggestions = debounce(fetchSuggestions, 300); // 300ms delay

// Add input listeners for origin and destination
if (origenInput && origenSuggestions) {
    origenInput.addEventListener('input', () => debouncedFetchSuggestions(origenInput.value, origenSuggestions));

    // Hide suggestions when clicking outside
    document.addEventListener('click', (e) => {
        if (!origenInput.contains(e.target) && !origenSuggestions.contains(e.target)) {
            origenSuggestions.classList.add('hidden');
        }
    });
     // Hide suggestions on focus out unless clicking on a suggestion item
    origenInput.addEventListener('blur', (e) => {
        // Delay hiding slightly to allow click event on suggestion to register
        setTimeout(() => {
            if (!origenSuggestions.contains(document.activeElement)) {
                 origenSuggestions.classList.add('hidden');
            }
        }, 150);
    });
} else {
     console.warn("Origin input or suggestion box not found for autocomplete.");
}

if (destinoInput && destinoSuggestions) {
    destinoInput.addEventListener('input', () => debouncedFetchSuggestions(destinoInput.value, destinoSuggestions));

    // Hide suggestions when clicking outside
     document.addEventListener('click', (e) => {
        if (!destinoInput.contains(e.target) && !destinoSuggestions.contains(e.target)) {
            destinoSuggestions.classList.add('hidden');
        }
    });
    // Hide suggestions on focus out unless clicking on a suggestion item
    destinoInput.addEventListener('blur', (e) => {
       // Delay hiding slightly
        setTimeout(() => {
            if (!destinoSuggestions.contains(document.activeElement)) {
                 destinoSuggestions.classList.add('hidden');
            }
        }, 150);
    });
} else {
     console.warn("Destination input or suggestion box not found for autocomplete.");
}


// --- Optional: Datepicker Integration (Example using Flatpickr) ---
// If you want to use this:
// 1. Install flatpickr: npm install flatpickr
// 2. Uncomment the following lines
// 3. Optionally change the date input type in welcome.blade.php to "text"

/*
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
// Optional: Import Spanish locale for flatpickr
// import { Spanish } from "flatpickr/dist/l10n/es.js";

const fechaInputForPicker = document.getElementById('fecha');
if (fechaInputForPicker) {
    flatpickr(fechaInputForPicker, {
        // locale: Spanish, // Use Spanish locale
        altInput: true, // Shows a human-readable date, sends standard format
        altFormat: "j F, Y", // Format shown to the user (e.g., 27 Octubre, 2025)
        dateFormat: "Y-m-d", // Format sent to the server
        minDate: "today", // Prevent selecting past dates
        disableMobile: "true" // Use flatpickr UI on mobile instead of native
        // More options available: https://flatpickr.js.org/options/
    });
}
*/
