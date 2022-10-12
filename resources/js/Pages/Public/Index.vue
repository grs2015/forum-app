<script setup lang="ts">

import { Head } from '@inertiajs/inertia-vue3'
import { Paginated } from '@/Interfaces/PaginatedData'
import { computed } from 'vue'
import { Inertia } from '@inertiajs/inertia';
import PublishedTasks from '@/Shared/Lists/PublishedTasks.vue'

interface Props {
    model: {
        tasks: Paginated,
    }
}
const props = defineProps<Props>()

const data = computed(() => props.model.tasks)
const currentPage = computed(() => props.model.tasks.current_page)
// const min = computed(() => props.model.posts.from)
const max = computed(() => props.model.tasks.last_page)
const paginate = (value) => { Inertia.get('/', { page: value }) }

const addTask = () => {
    Inertia.get('/tasks/create')
}

</script>

<template>
    <Head title="Task list" />

    <div class="column q-ma-xl justify-center items-center">
        <q-card flat >
            <q-card-actions>

                <q-btn color="secondary" @click="addTask()">ADD TASK</q-btn>
            </q-card-actions>

        </q-card>
        <PublishedTasks :paginatedData="data" />
        <div class="q-pa-lg flex flex-center">
                <q-pagination
                    v-model="currentPage"
                    :max=max
                    @update:model-value="paginate"
                    unelevated
                    color="primary" />
        </div>
    </div>
</template>
