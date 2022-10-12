<script setup lang="ts">

import { Paginated } from '@/Interfaces/PaginatedData';
import { Inertia } from '@inertiajs/inertia';
import { computed, ref } from 'vue'
import { useQuasar } from 'quasar'
import draggable from 'vuedraggable'

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
        <draggable
            tag="ul"
            :list="taskList"
            handle=".handle"
            item-key="id"
        >
            <template #item="{ element, index }">
                <li class="list-group-item q-mb-md">
                    <q-card>
                        <q-card-section>
                            <div class="row justify-start items-center">
                                <q-icon name="drag_handle" class="q-mr-sm handle" size="32px" color="orange"/>
                                <div class="text-h6 text-bold">{{ element.title }}</div>
                            </div>
                        </q-card-section>
                        <q-separator inset />
                        <q-card-section>
                            <div>{{ element.description }}</div>
                        </q-card-section>
                        <q-card-actions align="right">
                            <q-btn flat color="primary" @click="editTask(element.slug)">EDIT</q-btn>
                            <q-btn flat color="negative" @click="deleteTask(element.slug)">DELETE</q-btn>
                        </q-card-actions>
                    </q-card>
                    <!-- <i class="fa fa-align-justify handle"></i>

                    <span class="text">{{ element.title }} </span>

                    <input type="text" class="form-control" v-model="element.description" />

                    <i class="fa fa-times close" @click="removeAt(index)"></i> -->
                </li>
            </template>
      </draggable>
        <!-- <div v-for="(task, index) in taskList" :key="task.id" class="full-width">
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
        </div> -->
    </div>
</template>

<style scoped>
.handle {
    float: left;
    padding-top: 8px;
    padding-bottom: 8px;
}

.list-group-item {
    list-style-type: none;
}

</style>
