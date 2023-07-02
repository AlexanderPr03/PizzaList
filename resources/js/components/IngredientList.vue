<template>
    <div>
        <h1>Ingrediente</h1>
        <ul>
        <li v-for="ingredient in ingredients" :key="ingredient.id">
            <img :src="'images/'+ingredient.image">
            {{ ingredient.name }} - {{ ingredient.cost_price/100 }} eur - <router-link :to="'/ingredients/'+ ingredient.id">Schimba ingredient</router-link>
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