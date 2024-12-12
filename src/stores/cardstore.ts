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
      try {
        const response = await axios.get(
          'http://localhost:8080/backend/api/endpoint.php?path=inventory',
        )
        if (response.data && Array.isArray(response.data)) {
          this.inventory = response.data
        } else {
          console.error('Invalid response from API:', response.data)
          return [] // Return empty array if response is invalid
        }
      } catch (error) {
        console.error('Error fetching inventory:', error)
        return [] // Return empty array in case of an error
      }
    },
    async fetchWishlist() {
      try {
        const response = await axios.get(
          'http://localhost:8080/backend/api/endpoint.php?path=wishlist',
        )
        if (response.data && Array.isArray(response.data)) {
          this.wishlist = response.data
        } else {
          console.error('Invalid response from API:', response.data)
          return [] // Return empty array if response is invalid
        }
      } catch (error) {
        console.error('Error fetching inventory:', error)
        return [] // Return empty array in case of an error
      }
    },

    async deleteCard(id: number, table: string) {
      switch (table) {
        case 'inventory':
          this.deleteInventory(id)
          break
        case 'wishlist':
          this.deleteWishlist(id)
          break
        default:
          console.error('Unknown table:', table)
      }
    },

    async deleteInventory(id: number) {
      try {
        await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=inventory`, {
          data: { id },
        })
        this.inventory = this.inventory.filter((card) => card.id !== id) // Update the state
      } catch (error) {
        console.error('Error deleting the card:', error)
      }
    },
    async deleteWishlist(id: number) {
      try {
        await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=wishlist`, {
          data: { id },
        })
        this.wishlist = this.wishlist.filter((card) => card.id !== id) // Update the state
      } catch (error) {
        console.error('Error deleting the card:', error)
      }
    },
  },
})
