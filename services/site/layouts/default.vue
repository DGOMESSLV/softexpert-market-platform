<script lang="ts">
import HttpClient from '~/helpers/http-client';

export default {
    name: 'Default',
    data: () => ({
        theme: 'light-theme',
    }),
    async mounted () {
        this.theme = localStorage.getItem('user-theme') || 'light-theme';
        localStorage.setItem('user-theme', this.theme);
    },
};
</script>

<template>
    <div id="app-wrapper">
        <main>
            <slot/>
        </main>
    </div>
</template>

<style scoped></style>