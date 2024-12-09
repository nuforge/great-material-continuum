import axios from 'axios'

const apiClient = axios.create({
  baseURL: 'http://localhost:8080/backend/api',
  headers: {
    'Content-Type': 'application/json',
  },
})

export default {
  getUsers() {
    return apiClient.get('/endpoint.php?path=users')
  },
}
