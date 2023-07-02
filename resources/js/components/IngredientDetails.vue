<template>
    <div>
        <h1>{{ ingredient.name}}</h1>
        <h2>Preț: {{ ingredient_price }}</h2>
        <img :src="'/images/'+ingredient.image" alt="" width="100px" height="100px">

        <h2>Schimba ingredientul:</h2>
        <form @submit.prevent="submitForm">
            <div>
                <label for="name">Nume:</label>
                <input id="name" v-model="ingredient.name" required>
            </div>

            <div>
                <label for="cost_price">Preț:</label>
                <input id="cost_price" type="number" step="0.01" v-model="ingredient_price" required>
            </div>

            <div>
                <label for="image">Imagine:</label>
                <input type="file" id="image" @change="onFileChange" accept="image/*">
            </div>

            <button type="submit">Actualizeaza Ingredient</button>
        </form>
        <button @click="deleteIngredient">Șterge Ingredient</button>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
            ingredient: null,
            image: null,
            ingredient_price:null
        }
    },
    async created() {
        try {
            const response = await api.get(`/ingredients/${this.$route.params.id}`)
            this.ingredient = response.data
            this.ingredient_price = this.ingredient.cost_price/100;
        } catch (error) {
            console.error(error)
        }
    },
    methods: {
        onFileChange(e) {
            this.image = e.target.files[0]
        },
        async submitForm() {
            console.log(this.ingredient);
            // let formData = new FormData()
            // formData.append('name', this.ingredient.name)
            // formData.append('cost_price', this.ingredient.cost_price)
            // if (this.image) {
            //     formData.append('image', this.image)
            // }
            // console.log(formData)
            try {
                await api.put(`/ingredients/${this.ingredient.id}/change`, {name:this.ingredient.name, cost_price:this.ingredient_price * 100, image:this.ingredient.image})
                this.$router.push('/ingredients')
            } catch (error) {
                console.error(error)
            }
        },
        async deleteIngredient() {
            try {
                await api.delete(`/ingredients/${this.ingredient.id}`);
                alert('Ingredient șters cu succes!');
                this.$router.push('/ingredients');
            } catch (error) {
                console.error(error)
            }
        },
    },
}
</script>