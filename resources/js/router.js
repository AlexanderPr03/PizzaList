import { createRouter, createWebHistory } from 'vue-router'
import PizzaList from './components/PizzaList.vue'
import PizzaDetails from './components/PizzaDetails.vue'
import IngredientList from './components/IngredientList.vue'
import IngredientDetails from './components/IngredientDetails.vue'
import AddPizzaForm from './components/AddPizzaForm.vue'
import AddIngredientForm from './components/AddIngredientForm.vue'

const routes = [
    { 
        path: '/', 
        redirect: '/pizzas'
    },
    { 
        path: '/pizzas', 
        component: PizzaList 
    },
    { 
        path: '/pizzas/:id', 
        component: PizzaDetails 
    },
    { 
        path: '/ingredients', 
        component: IngredientList 
    },
    { 
        path: '/ingredients/:id', 
        component: IngredientDetails 
    },
    { 
        path: '/add-pizza', 
        component: AddPizzaForm 
    },
    { 
        path: '/add-ingredient', 
        component: AddIngredientForm 
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes,
})

export default router
