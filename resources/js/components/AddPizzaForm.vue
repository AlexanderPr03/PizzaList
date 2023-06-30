<template>
    <div>
        <h1>Adaugă o pizza!</h1>
        <form @submit.prevent="submitForm">
        <div>
            <label for="name">Nume:</label>
            <input id="name" v-model="pizza.name" required>
        </div>

        <div>
            <label for="image">Imagine:</label>
            <input type="file" id="image" @change="onFileChange" accept="image/*" required>
        </div>

        <div>
            <label for="ingredients">Ingrediente:</label>
            <select id="ingredients" v-model="newIngredient">
            <option v-for="ingredient in allIngredients" :key="ingredient.id" :value="ingredient.id">
                {{ ingredient.name }} - {{ ingredient.cost_price }} eur
            </option>
            </select>
            <button type="button" @click="addIngredient">Adaugă ingredient</button>
        </div>

        <ul>
            <li v-for="(ingredient, index) in pizza.ingredients" :key="ingredient.id">
            {{ ingredient.name }} - {{ ingredient.cost_price }} eur
            <button type="button" @click="removeIngredient(index)">Scoate</button>
            </li>
        </ul>

        <button type="submit">Adaugă pizza</button>
        </form>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
        pizza: {
            name: '',
            ingredients: [],
            image: null,
        },
        allIngredients: [],
        newIngredient: null,
        }
    },
    async created() {
        try {
            const response = await api.get('/ingredients')
            this.allIngredients = response.data
            } catch (error) {
            console.error(error)
        }
    },
    methods: {
        onFileChange(e) {
            const file = e.target.files[0]
            this.pizza.image = file
        },
        addIngredient() {
            const ingredient = this.allIngredients.find(i => i.id === this.newIngredient)
            this.pizza.ingredients.push(ingredient)
        },
        removeIngredient(index) {
            this.pizza.ingredients.splice(index, 1)
        },
        async submitForm() {
            const formData = new FormData()
            formData.append('name', this.pizza.name)
            formData.append('image', this.pizza.image)
            this.pizza.ingredients.forEach((ingredient, index) => {
                formData.append(`ingredients[${index}]`, ingredient.id)
            })

            try {
                await api.post('/pizzas', formData)
                alert('Pizza a fost adăugat cu succes!')
                this.$router.push('/pizzas')
            } catch (error) {
                console.error(error)
            }
        },
    },
}
</script>
