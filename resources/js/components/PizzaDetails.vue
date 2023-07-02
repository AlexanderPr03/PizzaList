<template>
    <div>
        <h1>{{ pizza.name }}</h1>
        <form @submit.prevent="submitForm">
            <div>
                <label for="name">Nume:</label>
                <input id="name" v-model="pizza.name" required>
            </div>
            <h2>Preț: {{ pizza.selling_price/100 }} euro</h2>
            <h2>Ingrediente:</h2>
            <ul class="ingredient-list">
                <li v-for="ingredient in pizzaIngredients" :key="ingredient.pivot.order">
                    {{ ingredient.pivot.order }}. {{ ingredient.name }} - {{ ingredient.cost_price/100 }} eur
                    <button @click="removeIngredient(ingredient.pivot.order-1)">Scoate</button>
                    <button @click="moveIngredientUp(ingredient.pivot.order-1)" v-if="ingredient.pivot.order > 0">Sus</button>
                    <button @click="moveIngredientDown(ingredient.pivot.order-1)" v-if="ingredient.pivot.order < allIngredients.length">Jos</button>
                </li>
            </ul>
            <div>
            <div>
                <label for="image">Imagine:</label>
                <input type="file" id="image" @change="onFileChange" accept="image/*">
            </div>
            <label for="newIngredient">Adaugă ingredient</label>
            <select id="newIngredient" v-model="newIngredient">
                <option v-for="ingredient in allIngredients" :key="ingredient.id" :value="ingredient.id">
                {{ ingredient.name }} - {{ ingredient.cost_price }} eur
                </option>
            </select>
            <button @click="addIngredient">Adaugă</button>
            </div>
            <button @click="deletePizza">Șterge pizza</button>
            <button type="submit">Actualizeaza Pizza</button>

        </form>
    </div>
</template>

<script>
import api from '../api'

export default {
data() {
    return {
        pizza: null,
        allIngredients: [],
        pizzaIngredients: [],
        newIngredient: null,
    }
},
async created() {
    try {
        const [pizzaResponse, pizzaIngredientsResponse, ingredientsResponse] = await Promise.all([
            api.get(`/pizzas/${this.$route.params.id}`),
            api.get(`/pizzas/${this.$route.params.id}/ingredients`),
            api.get('/ingredients'),
        ])
            this.pizza = pizzaResponse.data
            this.pizzaIngredients=pizzaIngredientsResponse.data
            this.allIngredients = ingredientsResponse.data

        } catch (error) {
         console.error(error)
        }
    },
    methods: {
        onFileChange(e) {
            this.pizza.image = e.target.files[0]
        },
        async removeIngredient(index) {
        try {
            const ingredient = this.pizzaIngredients[index];
            console.log(ingredient.id)
            await api.put(`/pizzas/${this.pizza.id}/removeIngredient`, {ingredient_id:ingredient.id})
            this.pizzaIngredients.splice(index, 1)
        } catch (error) {
            console.error(error)
        }
        },
        async moveIngredientUp(index) {
            try {
                const ingredient = this.pizzaIngredients[index]
                const newOrder = index 
                await api.put(`/pizzas/${this.pizza.id}/reorderIngredient`, { ingredient_id: ingredient.id, new_order: newOrder })
                await api.get(`/pizzas/${this.$route.params.id}/ingredients`).then(response=> (this.pizzaIngredients=response.data))
                // this.pizzaIngredients.splice(index, 1)
                // this.pizzaIngredients.splice(index - 1, 0, ingredient)
            } catch (error) {
                console.error(error)
            }
        },
        async moveIngredientDown(index) {
            try {
                console.log(this.pizza);
                const ingredient = this.pizzaIngredients[index]
                const newOrder = index + 2
                await api.put(`/pizzas/${this.pizza.id}/reorderIngredient`, { ingredient_id: ingredient.id, new_order: newOrder })
                await api.get(`/pizzas/${this.$route.params.id}/ingredients`).then(response=> (this.pizzaIngredients=response.data))

                // this.pizzaIngredients.splice(index, 1)
                // this.pizzaIngredients.splice(index + 1, 0, ingredient)
            } catch (error) {
                console.error(error)
            }
        },
        async addIngredient() {
            try {
                const ingredient = this.allIngredients.find(i => i.id === this.newIngredient)
                const order = this.pizzaIngredients.length+1;
                await api.post(`/pizzas/${this.pizza.id}/ingredients`, { ingredient_id: ingredient.id, order:order }).then(response=>console.log(response));
                location.reload();
                
            } catch (error) {
                console.error(error)
            }
        },
        async deletePizza() {
            try {
                await api.delete(`/pizzas/${this.pizza.id}`);
                alert('Pizza ștearsă cu succes!');
                this.$router.push('/pizzas');
            } catch (error) {
                console.error(error)
            }
        },
        async submitForm() {
            let data = {
                name: this.pizza.name,
                selling_price: this.pizza.selling_price*100,
                image: this.pizza.image,
                ingredients: this.pizzaIngredients.map((ingredient) => ingredient.id),
            };
            // const formData = new FormData()
            // formData.set('name', this.pizza.name)
            // formData.append('image', this.pizza.image)
            // formData.set('selling_price', this.pizza.selling_price*100)
            // this.pizzaIngredients.forEach((ingredient, index) => {
            //     formData.set(`ingredients[${index}]`, ingredient.id)
            // })

            try {
                
                await api.put(`/pizzas/${this.pizza.id}`, data )
                alert('Pizza a fost modificata cu succes!')
                this.$router.push('/pizzas')
            } catch (error) {
                console.error(error)
            }
        },
    },
}
</script>