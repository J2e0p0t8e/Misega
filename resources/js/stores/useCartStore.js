import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: JSON.parse(localStorage.getItem('misega_cart') || '[]'),
    }),
    getters: {
        totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
        totalPrice: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0),
    },
    actions: {
        saveCart() {
            localStorage.setItem('misega_cart', JSON.stringify(this.items));
        },
        addItem(manga, volume, quantity = 1) {
            const existingIndex = this.items.findIndex(item => item.volume_id === volume.id);
            if (existingIndex > -1) {
                const newQty = this.items[existingIndex].quantity + quantity;
                if (newQty <= volume.stock) {
                    this.items[existingIndex].quantity = newQty;
                } else {
                    this.items[existingIndex].quantity = volume.stock;
                }
            } else {
                this.items.push({
                    volume_id: volume.id,
                    manga_id: manga.id,
                    manga_title: manga.title,
                    author: manga.author,
                    volume_number: volume.volume_number,
                    price: parseFloat(volume.price),
                    stock: volume.stock,
                    cover_image: manga.cover_url || manga.cover_image || volume.cover_image_url,
                    quantity: Math.min(quantity, volume.stock),
                });
            }
            this.saveCart();
        },
        removeItem(volumeId) {
            this.items = this.items.filter(item => item.volume_id !== volumeId);
            this.saveCart();
        },
        updateQuantity(volumeId, quantity) {
            const item = this.items.find(i => i.volume_id === volumeId);
            if (item) {
                if (quantity <= 0) {
                    this.removeItem(volumeId);
                } else if (quantity <= item.stock) {
                    item.quantity = quantity;
                    this.saveCart();
                }
            }
        },
        clearCart() {
            this.items = [];
            this.saveCart();
        }
    }
});
