import './bootstrap'; // Asegúrate de que esto esté al principio
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Configurar el token CSRF para las peticiones AJAX
const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token;
}


// --- Lógica para el Autocompletado y Validación de Ciudades ---

// Declaración de funciones
const setupAutocomplete = (inputId, suggestionsId) => {
    const input = document.getElementById(inputId);
    const suggestionsContainer = document.getElementById(suggestionsId);
    let timeout = null;

    if (!input || !suggestionsContainer) {
        console.warn(`Elementos para autocompletado no encontrados: inputId=${inputId}, suggestionsId=${suggestionsId}`);
        return;
    }

    input.addEventListener('input', function() {
        clearTimeout(timeout);

        const query = input.value.trim();

        if (query.length < 2) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.add('hidden');
            return;
        }

        timeout = setTimeout(async () => {
            try {
                console.log('Fetching cities for query:', query);
                const baseUrl = window.location.origin; // Obtener la URL base dinámica
                const response = await fetch(`${baseUrl}/api/ciudades/buscar?q=${encodeURIComponent(query)}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                const ciudades = await response.json();
                console.log('Response:', ciudades);
                console.log('Cities received:', ciudades);
                displaySuggestions(ciudades, input, suggestionsContainer);
            } catch (error) {
                console.error('Error fetching cities:', error);
                suggestionsContainer.innerHTML = `<div class="p-2 text-red-500">Error al cargar ciudades.</div>`;
                suggestionsContainer.classList.remove('hidden');
            }
        }, 300);
    });

    suggestionsContainer.addEventListener('click', function(event) {
        if (event.target.tagName === 'DIV' && event.target.classList.contains('suggestion-item')) {
            input.value = event.target.textContent;
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.add('hidden');
            hideSearchError();
        }
    });

    document.addEventListener('click', function(event) {
        if (event.target !== input && !suggestionsContainer.contains(event.target)) {
            suggestionsContainer.innerHTML = '';
            suggestionsContainer.classList.add('hidden');
        }
    });

    input.addEventListener('blur', function() {
        setTimeout(() => {
            if (!suggestionsContainer.contains(document.activeElement)) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
            }
        }, 100);
    });
};

const displaySuggestions = (ciudades, inputElement, containerElement) => {
    containerElement.innerHTML = '';
    
    if (ciudades.length > 0) {
        ciudades.forEach(ciudad => {
            const div = document.createElement('div');
            div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100', 'suggestion-item');
            div.textContent = ciudad;
            containerElement.appendChild(div);
        });
        containerElement.classList.remove('hidden');
    } else {
        containerElement.classList.add('hidden');
    }
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing autocomplete...');
    
    const searchForm = document.getElementById('search-form');
    const origenInput = document.getElementById('origen');
    const destinoInput = document.getElementById('destino');
    const searchErrorDiv = document.getElementById('search-error');

    // Función para configurar el autocompletado en un campo específico
    const setupAutocomplete = (inputId, suggestionsId) => {
        const input = document.getElementById(inputId);
        const suggestionsContainer = document.getElementById(suggestionsId);
        let timeout = null; // Para retrasar las peticiones a la API

        if (!input || !suggestionsContainer) {
            // console.warn(`Elementos para autocompletado no encontrados: inputId=${inputId}, suggestionsId=${suggestionsId}`);
            return; // Salir si los elementos no existen
        }

        input.addEventListener('input', function() {
            clearTimeout(timeout); // Limpiar cualquier timeout anterior

            const query = input.value.trim();

            if (query.length < 2) { // Solo buscar si hay al menos 2 caracteres
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
                return;
            }

            // Retrasar la petición a la API para evitar spam y mejorar rendimiento
            timeout = setTimeout(async () => {
                try {
                    console.log('Fetching cities for query:', query);
                    const response = await fetch(`/api/ciudades/buscar?q=${encodeURIComponent(query)}`);
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    const ciudades = await response.json();
                    console.log('Cities received:', ciudades);
                    
                    displaySuggestions(ciudades, input, suggestionsContainer);

                } catch (error) {
                    console.error('Error fetching cities:', error);
                    suggestionsContainer.innerHTML = `<div class="p-2 text-red-500">Error al cargar ciudades.</div>`;
                    suggestionsContainer.classList.remove('hidden');
                }
            }, 300); // Esperar 300ms después de la última tecla

        });

        // Manejar clics en las sugerencias
        suggestionsContainer.addEventListener('click', function(event) {
            if (event.target.tagName === 'DIV' && event.target.classList.contains('suggestion-item')) {
                input.value = event.target.textContent;
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
            }
            // Después de seleccionar, limpiar posibles errores de validación anteriores
            hideSearchError();
        });

        // Ocultar sugerencias cuando se hace clic fuera del input/sugerencias
        document.addEventListener('click', function(event) {
            if (event.target !== input && !suggestionsContainer.contains(event.target)) {
                suggestionsContainer.innerHTML = '';
                suggestionsContainer.classList.add('hidden');
            }
        });

        // Ocultar sugerencias si el input pierde el foco y no se hizo clic en una sugerencia
        input.addEventListener('blur', function() {
             // Pequeño retraso para permitir que el evento click en la sugerencia se dispare
            setTimeout(() => {
                if (!suggestionsContainer.contains(document.activeElement)) { // Si el foco no está en el contenedor de sugerencias
                    suggestionsContainer.innerHTML = '';
                    suggestionsContainer.classList.add('hidden');
                }
            }, 100);
        });
    };

    // Función para mostrar las sugerencias en el contenedor
    const displaySuggestions = (ciudades, inputElement, containerElement) => {
        containerElement.innerHTML = ''; // Limpiar sugerencias anteriores

        if (ciudades.length > 0) {
            ciudades.forEach(ciudad => {
                const div = document.createElement('div');
                div.classList.add('p-2', 'cursor-pointer', 'hover:bg-gray-100', 'suggestion-item');
                div.textContent = ciudad;
                containerElement.appendChild(div);
            });
            containerElement.classList.remove('hidden'); // Mostrar el contenedor
        } else {
            containerElement.classList.add('hidden'); // Ocultar si no hay sugerencias
        }
    };

    // Función para mostrar el error de búsqueda
    const showSearchError = (message) => {
        if (searchErrorDiv) {
            searchErrorDiv.textContent = message;
            searchErrorDiv.classList.remove('hidden');
        }
    };

    // Función para ocultar el error de búsqueda
    const hideSearchError = () => {
        if (searchErrorDiv) {
            searchErrorDiv.textContent = '';
            searchErrorDiv.classList.add('hidden');
        }
    };


    // --- Validación del Formulario ---
    if (searchForm) {
        searchForm.addEventListener('submit', function(event) {
            // Ocultar cualquier error anterior
            hideSearchError();

            const origenValue = origenInput.value.trim();
            const destinoValue = destinoInput.value.trim();

            // Validar que Origen y Destino no sean iguales
            if (origenValue && destinoValue && origenValue === destinoValue) {
                event.preventDefault(); // Detener el envío del formulario
                showSearchError('El origen y el destino no pueden ser la misma ciudad.');
                return;
            }

            // Validar que la fecha no esté vacía (aunque el input type="date" ya lo hace)
            const fechaInput = document.getElementById('fecha');
            if (!fechaInput.value) {
                event.preventDefault();
                showSearchError('Por favor, selecciona una fecha de salida.');
                return;
            }

            // Aquí podrías añadir más validaciones si fuera necesario (ej. que las ciudades sean válidas)
        });

        // Limpiar el error cuando los campos de origen/destino cambian
        origenInput.addEventListener('input', hideSearchError);
        destinoInput.addEventListener('input', hideSearchError);
        document.getElementById('fecha').addEventListener('input', hideSearchError);
    }


    // --- Inicialización ---
    // Inicializar autocompletado para Origen y Destino
    setupAutocomplete('origen', 'origen-suggestions');
    setupAutocomplete('destino', 'destino-suggestions');

});