<template>
    <div>
        <h1>{{ ingredient.name}}</h1>
        {{ ingredient.id }}
        <p>Cost Price: {{ ingredient.cost_price }}</p>
        <img :src="'/images/'+ingredient.image" alt="" width="100px" height="100px">

        <h2>Schimba ingredientul:</h2>
        <form @submit.prevent="submitForm">
            <div>
                <label for="name">Name:</label>
                <input id="name" v-model="ingredient.name" required>
            </div>

            <div>
                <label for="cost_price">Cost Price:</label>
                <input id="cost_price" type="number" step="0.01" v-model="ingredient.cost_price" required>
            </div>

            <div>
                <label for="image">Image:</label>
                <input type="file" id="image" @change="onFileChange" accept="image/*">
            </div>

            <button type="submit">Update Ingredient</button>
        </form>
    </div>
</template>

<script>
import api from '../api'

export default {
    data() {
        return {
            ingredient: null,
            image: null
        }
    },
    async created() {
        try {
            const response = await api.get(`/ingredients/${this.$route.params.id}`)
            this.ingredient = response.data
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
                await api.put(`/ingredients/${this.ingredient.id}/change`, {name:this.ingredient.name, cost_price:this.ingredient.cost_price, image:this.ingredient.image})
                this.$router.push('/ingredients')
            } catch (error) {
                console.error(error)
            }
        },
    },
}
</script>