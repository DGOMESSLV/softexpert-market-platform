<script lang="ts">
export default {
    name: 'AppHeader',
    props: {
        theme: {
            type: String,
            required: true,
        },
    },
    methods: {
        toggleTheme (): void {
            const newTheme = this.theme === 'light-theme' ? 'dark-theme' : 'light-theme';
            
            document.documentElement.classList.add(newTheme);
            document.documentElement.classList.remove(this.theme);

            localStorage.setItem('user-theme', newTheme);

            this.$emit('theme-change', newTheme);
        },
    },
};
</script>

<template>
    <div>
        <header class="navbar navbar-expand-lg shadow-sm">
            <div class="container-fluid">
                <nuxt-link class="navbar-brand" to="/">
                    <img src="/img/brand.png" height="42" alt="An image containing a bright pink square with rounded edges and 'Your Logo' written on the front."/>
                </nuxt-link>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <nuxt-link  active-class="nuxt-link-active" class="nav-link" to="/">Home</nuxt-link>
                        </li>

                        <li class="nav-item">
                            <nuxt-link active-class="nuxt-link-active"  class="nav-link" to="/products">Products</nuxt-link>
                        </li>

                        <li class="nav-item">
                            <nuxt-link active-class="nuxt-link-active"  class="nav-link" to="/sales">Sales</nuxt-link>
                        </li>
                    </ul>

                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item ps-2">
                            <button class="theme-toggler" @click="toggleTheme" aria-label="Toggle theme">
                                <i :class="['fa-moon', (theme === 'dark-theme' ? 'fa-solid' : 'fa-regular')]"></i>
                            </button>
                        </li>
                    </ul>
                </div>

            </div>
        </header>
    </div>
</template>

<style scoped>
header {
    background: var(--navbar-bg);
}

.navbar-toggler {
    border: 0;
    font-size: 1.5rem;
    color: var(--navbar-toggler-fg);
}

.theme-toggler {
    height: 38px;
    width: 38px;
    border: 0;
    border-radius: 100%;
    background: transparent;
    font-size: 1.5rem;
    color: var(--navbar-toggler-fg);
}

.theme-toggler:hover,
.theme-toggler:focus {
    background: var(--navbar-toggler-active-bg);
}

.nav-item {
    max-width: 50%
}

.nav-link {
    margin: 0 .5rem;
    padding: .35rem .75rem !important;
    border-radius: .75rem;
    background: transparent;
    font-size: .925rem;
    color: var(--navbar-item-fg);
}

.nav-link:not(.nuxt-link-active):hover,
.nav-link:not(.nuxt-link-active):focus {
    background: var(--navbar-item-active-bg);
    color: var(--navbar-item-active-fg);
}

.nav-link.nuxt-link-active {
    background: var(--navbar-item-current-bg) !important;
    color: var(--navbar-item-current-fg) !important;
}
</style>