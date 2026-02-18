import { defineStore } from 'pinia';
import api from '../api/axios';

export const useCartStore = defineStore('cart', {
  state: () => ({
    // Cargar del LocalStorage al iniciar, o array vacío
    cart: JSON.parse(localStorage.getItem('my_ecommerce_cart')) || [],
    order: null
  }),

  getters: {
    // Total en euros
    totalPrice: (state) => {
      return state.cart.reduce((total, item) => total + (item.price * item.quantity), 0);
    },
    // Cantidad total de items (para el icono del nav)
    itemCount: (state) => {
      return state.cart.reduce((total, item) => total + item.quantity, 0);
    }
  },

  actions: {
    addToCart(product) {
      const existingItem = this.cart.find(item => item.id === product.id);

      if (existingItem) {
        // Validar stock antes de sumar
        if (existingItem.quantity < product.stock) {
          existingItem.quantity++;
        } else {
          alert('¡No queda más stock de este producto!');
          return;
        }
      } else {
        // Añadir nuevo item
        this.cart.push({
          id: product.id,
          name: product.name,
          price: Number(product.price), // Asegurar que es número
          image: product.image,
          stock: product.stock,
          quantity: 1
        });
      }

      // Guardar cambios en LocalStorage
      this.saveToLocalStorage();
    },

    removeFromCart(productId) {
      this.cart = this.cart.filter(item => item.id !== productId);
      this.saveToLocalStorage();
    },

    clearCart() {
      this.cart = [];
      this.saveToLocalStorage();
    },

    saveToLocalStorage() {
      localStorage.setItem('my_ecommerce_cart', JSON.stringify(this.cart));
    },

    // Enviar el pedido al Backend (Laravel)
    async checkout(shippingData) {
      try {
        const payload = {
          shipping_address: shippingData,
          items: this.cart, // Enviamos el array de productos
          total: this.totalPrice
        };
        
        // NOTA: Debes crear esta ruta en tu api.php de Laravel
        const response = await api.post('/api/checkout', payload);
        
        this.order = response.data.order;
        this.clearCart(); // Vaciar carrito tras éxito
        return true;
      } catch (error) {
        console.error('Error procesando pedido', error);
        throw error;
      }
    }
  }
});