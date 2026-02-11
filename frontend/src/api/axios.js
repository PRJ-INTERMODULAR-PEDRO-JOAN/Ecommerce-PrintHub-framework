import axios from 'axios';

const api = axios.create({
    // CAMBIA ESTO si tu Laravel corre en otro puerto (ej: http://localhost:8000)
    baseURL: 'http://localhost', 
    withCredentials: true, // Crucial para que viajen las cookies/sesión
    headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
        'Content-Type': 'application/json'
    }
});

export default api;