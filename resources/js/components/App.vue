<template>
  <nav class="navbar">
    <router-link class="nav-link" to="/pizzas">Vezi pizze</router-link>
    <router-link class="nav-link" to="/ingredients">Vezi ingrediente</router-link>
    <router-link class="nav-link" to="/add-pizza">Adauga Pizza</router-link>
    <router-link class="nav-link" to="/add-ingredient">Adauga Ingredient</router-link>
  </nav>
  <div class="container">
    <router-view></router-view>
  </div>

</template>

<script>
export default {
    name:'App'
}
</script>

<style>
@import '../../css/app.css'
</style>