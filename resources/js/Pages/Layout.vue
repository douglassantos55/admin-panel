<script setup>
import { watch } from 'vue'
import Toastify from 'toastify-js'
import "toastify-js/src/toastify.css"
import { Link, usePage } from '@inertiajs/inertia-vue3'

watch(() => usePage().props.value.flash, message => {
    if (message) {
        Toastify({ text: message }).showToast()
    }
})
</script>

<template>
    <header class="navbar navbar-dark bg-dark px-3 sticky-top">
        <Link href="/" class="navbar-brand">Reconcip</Link>

        <div class="navbar-nav">
            <div class="nav-item">
                <Link :href="route('logout')" class="nav-link">Sair</Link>
            </div>
        </div>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <Link href="/" class="nav-link">Dashboard</Link>
                        </li>

                        <li class="nav-item">
                            <Link :href="route('customers.index')" class="nav-link">Clientes</Link>
                        </li>

                        <li class="nav-item">
                            <Link :href="route('suppliers.index')" class="nav-link">Fornecedores</Link>
                        </li>

                        <li class="nav-item">
                            <Link :href="route('periods.index')" class="nav-link">Períodos</Link>
                        </li>

                        <li class="nav-item">
                            <Link :href="route('equipments.index')" class="nav-link">Equipamentos</Link>
                        </li>

                        <li class="nav-item">
                            <Link :href="route('payment_types.index')" class="nav-link">Tipos pagamento</Link>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-3">
                <slot />
            </main>
        </div>
    </div>
</template>

<style>
.sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 100;
    padding: 48px 0 0;
    box-shadow: inset -1px 0 0 rgb(0 0 0 / 10%);
}
</style>
