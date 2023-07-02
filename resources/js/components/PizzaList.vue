<template>
    <div>
        <h1>Pizza</h1>
        <ul>
            <li v-for="pizza in pizzas" :key="pizza.id">
                <img :src="'images/'+pizza.image">
                {{ pizza.name }} - <router-link :to="'/pizzas/'+ pizza.id">Vezi Pizza</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
                pizzas: [],
                path:'localhost'
            }
    },
    async created() {
        
        try {
            const response = await api.get('/pizzas')
            this.pizzas = response.data
            console.log(this.pizzas)
        } catch (error) {
            console.error(error)
        }
    },
}
</script>