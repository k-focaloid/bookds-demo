<template>
  <div class="wrapper">
    <div class="main-content">
      <div>
        <fade-transition :duration="200" origin="center top" mode="out-in">
          <router-view></router-view>
        </fade-transition>
      </div>
    </div>
  </div>
</template>
<script>
  import { FadeTransition } from 'vue2-transitions';
  export default {
    name: 'books-layout',
    components: {
      FadeTransition
    },
  };
</script>
<style lang="scss">
</style>
