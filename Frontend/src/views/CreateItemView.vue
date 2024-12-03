<script setup>
import AuthNavBar from '@/components/AuthNavBar.vue';
import Footer from '@/components/Footer.vue'
import ItemCreateForm from '@/components/ItemCreateForm.vue';
import Wrapper from '@/components/Wrapper.vue';
</script>

<template>
  <Wrapper>
    <AuthNavBar />
    <ItemCreateForm />
  </Wrapper>
  <Footer />
</template>