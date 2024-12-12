// stores/cards.ts
import { defineStore } from 'pinia'
import axios from 'axios'

export const useCardStore = defineStore('cards', {
  state: () => ({
    haves: [] as { id: number; card_name: string }[],
    wants: [] as { id: number; card_name: string }[],
  }),
  actions: {
    async fetchHaves() {
      const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=haves')
      this.haves = response.data
    },
    async fetchWants() {
      const response = await axios.get('http://localhost:8080/backend/api/endpoint.php?path=wants')
      this.wants = response.data
    },

    async deleteCard(id: number, table: string) {
      switch (table) {
        case 'haves':
          this.deleteHave(id)
          break
        case 'wants':
          this.deleteWant(id)
          break
        default:
          console.error('Unknown table:', table)
      }
    },

    async deleteHave(id: number) {
      try {
        await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=haves`, {
          data: { id },
        })
        this.haves = this.haves.filter((card) => card.id !== id) // Update the state
      } catch (error) {
        console.error('Error deleting the card:', error)
      }
    },
    async deleteWant(id: number) {
      try {
        await axios.delete(`http://localhost:8080/backend/api/endpoint.php?path=wants`, {
          data: { id },
        })
        this.wants = this.wants.filter((card) => card.id !== id) // Update the state
      } catch (error) {
        console.error('Error deleting the card:', error)
      }
    },
  },
})
