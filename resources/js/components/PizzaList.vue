<template>
    <div>
        <h1>Pizza</h1>
        <ul>
            <li v-for="pizza in pizzas" :key="pizza.id">{{ pizza.name }}</li>
        </ul>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
            pizzas: [],
            }
    },
    async created() {
        try {
            const response = await api.get('/pizzas')
            this.pizzas = response.data
        } catch (error) {
            console.error(error)
        }
    },
}
</script>