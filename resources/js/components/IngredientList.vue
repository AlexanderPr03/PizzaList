<template>
    <div>
        <h1>Ingrediente</h1>
        <ul>
        <li v-for="ingredient in ingredients" :key="ingredient.id">
            {{ ingredient.name }} - {{ ingredient.cost_price }} eur
        </li>
        </ul>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
            ingredients: [],
        }
    },
    async created() {
        try {
            const response = await api.get('/ingredients')
            this.ingredients = response.data
        } catch (error) {
            console.error(error)
        }
    },
}
</script>