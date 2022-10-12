<script setup>

</script>

<template>
    <q-layout view="hHh lpR fFf">

        <q-header elevated class="bg-primary text-white">
            <q-toolbar>
                <q-toolbar-title>
                    Zadanie rekrutacyjne
                </q-toolbar-title>
            </q-toolbar>
        </q-header>

        <q-page-container>
            <q-page padding>
                <slot></slot>
            </q-page>
        </q-page-container>

        <q-footer elevated class="bg-grey-8 text-white">
            <q-toolbar>
                <q-toolbar-title>

                </q-toolbar-title>
            </q-toolbar>
        </q-footer>

    </q-layout>
</template>
