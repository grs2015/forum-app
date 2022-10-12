<script setup lang="ts">

import { Paginated } from '@/Interfaces/PaginatedData';
import { Inertia } from '@inertiajs/inertia';
import { computed, ref } from 'vue'
import { useQuasar } from 'quasar'

interface Props {
    paginatedData: Paginated
}

const props = defineProps<Props>()
const loading = ref(false)
const $q = useQuasar()

const taskList = computed(() => props.paginatedData.data)

const deleteTask = (slug: string) => {
    loading.value = true
    Inertia.delete(`/tasks/${slug}`, {
        onSuccess: () => onDeleteSuccess(),
        onError: () => onDeleteFail()
    })
}

const editTask = (slug: string) => {
    Inertia.get(`/tasks/${slug}/edit`)
}

const onDeleteSuccess = () => {
    $q.notify({
        type: 'positive',
        message: "Task has been deleted successfully"
    })
}

const onDeleteFail = () => {
    $q.notify({
        type: 'negative',
        message: "Something went wrong with task deletion"
    })
}

</script>

<template>
    <div class="column q-col-gutter-y-md q-mx-xl" style="width: 640px">
        <div v-for="(task, index) in taskList" :key="task.id" class="full-width">
            <q-card>
                <q-card-section>
                    <div class="text-h6 text-bold">{{ task.title }}</div>
                </q-card-section>
                <q-separator inset />
                <q-card-section>
                    <div>{{ task.description }}</div>
                </q-card-section>
                <q-card-actions align="right">
                    <q-btn flat color="primary" @click="editTask(task.slug)">EDIT</q-btn>
                    <q-btn flat color="negative" @click="deleteTask(task.slug)">DELETE</q-btn>
                </q-card-actions>
            </q-card>
        </div>
    </div>
</template>
