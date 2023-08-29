<script lang="ts">
export default {
    name: 'Default',
    data: () => ({
        theme: 'light-theme',
    }),
    computed: {
        showFastActions (): boolean {
            return ['/sales/new', '/products/new'].indexOf(this.$route.path) === -1;
        },
    },
    async mounted () {
        this.theme = localStorage.getItem('user-theme') || 'light-theme';
        localStorage.setItem('user-theme', this.theme);
    },
};
</script>

<template>
    <div id="app-wrapper">
        <app-header
            :theme="theme"
            @theme-change="(t: string) => theme = t"
        />

        <main>
            <slot/>
        </main>

        <aside id="fast-actions" v-if="showFastActions">
            <button class="btn btn-warning new-product-button" @click="() => navigateTo('/products/new')">
                + New Product
            </button>

            <button class="btn btn-theme-primary new-sale-button" @click="() => navigateTo('/sales/new')">
                + New Sale
            </button>
        </aside>
    </div>
</template>

<style scoped>
#fast-actions {
    position: absolute;
    right: 1rem;
    bottom: 1.25rem;
    display: flex;
    flex-direction: column;
}

#fast-actions .btn {
    border-radius: 1.5rem;
    height: 48px;
    margin-top: .5rem;
    padding-left: 1.5rem;
    padding-right: 1.5rem;
    font-size: 1.05rem;
}
</style>