// stores/cards.ts
import { defineStore } from 'pinia'
import axios from 'axios'

interface Cards {
  id: number
  card_id: number
  card_name: string
}

export const useCardStore = defineStore('cards', {
  state: () => ({
    inventory: [] as Cards[],
    wishlist: [] as Cards[],
  }),
  actions: {
    async fetchInventory() {
      const response = await axios.get(
        'http://localhost:8080/backend/api/endpoint.php?path=inventory',
      )
      this.inventory = response.data
      console.log(this.inventory)
    },
    async fetchWishlist() {
      const response = await axios.get(
        'http://localhost:8080/backend/api/endpoint.php?path=wishlist',
      )
      this.wishlist = response.data
    },

    async deleteCard(id: number, table: string) {
      switch (table) {
        case 'inventory':
          this.deleteHave(id)
          break
        case 'wishlist':
          this.deleteWant(id)
          break
        default:
          console.error('Unknown table:', table)
      }
    },

    async deleteHave(id: number) {
      try {
        await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=inventory`, {
          data: { id },
        })
        this.inventory = this.inventory.filter((card) => card.card_id !== id) // Update the state
      } catch (error) {
        console.error('Error deleting the card:', error)
      }
    },
    async deleteWant(id: number) {
      try {
        await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=wishlist`, {
          data: { id },
        })
        this.wishlist = this.wishlist.filter((card) => card.card_id !== id) // Update the state
      } catch (error) {
        console.error('Error deleting the card:', error)
      }
    },
  },
})
