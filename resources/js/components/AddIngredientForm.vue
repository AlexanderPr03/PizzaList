<template>
    <div>
        <h1>Adaugă un ingredient</h1>
        <form @submit.prevent="submitForm">
        <div>
            <label for="name">Nume:</label>
            <input id="name" v-model="ingredient.name" required>
        </div>

        <div>
            <label for="image">Imagine:</label>
            <input type="file" id="image" @change="onFileChange" accept="image/*">
        </div>

        <div>
            <label for="cost_price">Cost :</label>
            <input id="cost_price" type="number" step="0.01" v-model="ingredient.cost_price" required>
        </div>

        <button type="submit">Adaugă ingredient</button>
        </form>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
        ingredient: {
                name: '',
                cost_price: 0,
                image: null,
            },
        }
    },
    methods: {
        onFileChange(e) {
            const file = e.target.files[0]
            this.ingredient.image = file
        },
        async submitForm() {
            const formData = new FormData()
            formData.append('name', this.ingredient.name)

            let priceInDollars = this.ingredient.cost_price;
            let priceInCents = priceInDollars * 100;

            formData.append('cost_price', priceInCents)
            formData.append('image', this.ingredient.image)

            try {
                await api.post('/ingredients', formData)
                alert('ingredientul a fost adăugat')
                this.$router.push('/ingredients')
            } catch (error) {
                console.error('Fail', error)
            }
        },
    },
}
</script>