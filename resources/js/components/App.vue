<template>
  <router-link to="/pizzas">Vezi pizze</router-link>
  <router-link to="/ingredients">Vezi ingrediente</router-link>
  <router-link to="/add-pizza">Adauga Pizza</router-link>
  <router-link to="/add-ingredient">Adauga Ingredient</router-link>
  <router-view></router-view>
</template>

<script>
export default {
    name:'App'
}
</script>

<style>

</style>