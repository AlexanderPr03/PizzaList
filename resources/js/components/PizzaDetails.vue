<template>
    <div>
        <h1>{{ pizza.name }}</h1>
        <p>Preț {{ pizza.selling_price }}</p>
        <h2>Ingrediente:</h2>
        <ul>
        <li v-for="(ingredient, index) in pizza.ingredients" :key="ingredient.id">
            {{ index + 1 }}. {{ ingredient.name }} - {{ ingredient.cost_price }} eur
            <button @click="removeIngredient(index)">Scoate</button>
            <button @click="moveIngredientUp(index)" v-if="index > 0">Sus</button>
            <button @click="moveIngredientDown(index)" v-if="index < pizza.ingredients.length - 1">Jos</button>
        </li>
        </ul>
        <div>
        <label for="newIngredient">Adaugă ingredient</label>
        <select id="newIngredient" v-model="newIngredient">
            <option v-for="ingredient in allIngredients" :key="ingredient.id" :value="ingredient.id">
            {{ ingredient.name }} - {{ ingredient.cost_price }} eur
            </option>
        </select>
        <button @click="addIngredient">Adaugă</button>
        </div>
    </div>
</template>

<script>
import api from '../api'

export default {
data() {
    return {
    pizza: null,
    allIngredients: [],
    newIngredient: null,
    }
},
async created() {
    try {
        const [pizzaResponse, ingredientsResponse] = await Promise.all([
            api.get(`/pizzas/${this.$route.params.id}`),
            api.get('/ingredients'),
        ])
        this.pizza = pizzaResponse.data
        this.allIngredients = ingredientsResponse.data
        } catch (error) {
        console.error(error)
        }
    },
    methods: {
        async removeIngredient(index) {
        try {
            const ingredient = this.pizza.ingredients[index]
            await api.delete(`/pizzas/${this.pizza.id}/ingredients/${ingredient.id}`)
            this.pizza.ingredients.splice(index, 1)
        } catch (error) {
            console.error(error)
        }
        },
        moveIngredientUp(index) {
            const ingredient = this.pizza.ingredients[index]
            this.pizza.ingredients.splice(index, 1)
            this.pizza.ingredients.splice(index - 1, 0, ingredient)
        },
        moveIngredientDown(index) {
            const ingredient = this.pizza.ingredients[index]
            this.pizza.ingredients.splice(index, 1)
            this.pizza.ingredients.splice(index + 1, 0, ingredient)
        },
        async addIngredient() {
            try {
                const ingredient = this.allIngredients.find(i => i.id === this.newIngredient)
                await api.post(`/pizzas/${this.pizza.id}/ingredients`, { ingredient_id: ingredient.id })
                this.pizza.ingredients.push(ingredient)
            } catch (error) {
                console.error(error)
            }
        },
    },
}
</script>