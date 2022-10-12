<script setup lang="ts">

import { Head } from '@inertiajs/inertia-vue3'
import { TaskData } from '@/Interfaces/PaginatedData'
import { useQuasar } from 'quasar'
import { ref } from 'vue'
import { useForm, Link } from '@inertiajs/inertia-vue3'

interface Props {
    model: {
        task?: TaskData | null
    }
}

const props = defineProps<Props>()
const $q = useQuasar()
const modeCreate = ref(true)
const taskTitleRef = ref(null)
const confirm = ref(false)

let form = useForm<TaskData>({
    id: null,
    title: '',
    description: '',
    slug: ''
})

if (props.model.task) {
    modeCreate.value = false

    form = useForm<TaskData>({
        id: props.model.task.id,
        title: props.model.task.title,
        description: props.model.task.description,
        slug: props.model.task.slug
    })
}

const actionTask = () => {

if (modeCreate.value) {
    form.post('/tasks', {
    onSuccess: () => {
            $q.notify({
            type: 'positive',
            message: 'The tag have been stored successfully'
            })
    },
    onError: () => {
            $q.notify({
            type: 'negative',
            message: 'There were errors while storing tag'
            })
    }
    })
    return
}

form.put(`/tasks/${form.slug}`, {
    onSuccess: () => {
            $q.notify({
                type: 'positive',
                message: 'The tag have been updated successfully'
            })
            // window.history.back()
    },
    onError: () => {
            $q.notify({
                type: 'negative',
                message: 'There were errors while updating'
            })
    },
})

const onUpdateSuccess = () => {
    $q.notify({
        type: 'positive',
        message: 'The tag have been updated successfully'
    })
}

const onUpdateFail = () => {
    $q.notify({
        type: 'negative',
        message: 'There were errors while updating'
    })
}

const resetForm = () => {
    form.reset()
    taskTitleRef.value.resetValidation()
}

}

</script>

<template>
    <Head title="Task Edit" />
    <div class="column q-ma-xl justify-center items-center">
        <div class="q-mt-md" style="width: 640px">
        <q-form @submit="actionTask">
            <q-card flat bordered>
                <q-card-section class="text-primary text-h6">
                    {{ modeCreate ? 'Create Task' : 'Edit Task' }}
                </q-card-section>
                <q-card-section>
                    <div class="column q-col-gutter-md">
                        <div class="row q-col-gutter-md">
                            <div class="col">
                                <q-card flat bordered>
                                    <q-card-section>
                                        <div class="col q-mb-md">
                                            <div class="form_header text-subtitle2 text-primary text-weight-regular">
                                                Task Title</div>
                                            <q-input v-model="form.title" dense
                                                :rules="[val => !!val || 'Field is required',
                                                val => val.length <= 30 || 'Please use maximum 30 characters']" ref="taskTitleRef"
                                                :error-message="form.errors.title"
                                                :error="form.errors.title && form.errors.title.length > 0">
                                                <template v-slot:prepend>
                                                    <q-icon name="edit" color="orange" />
                                                </template>
                                            </q-input>
                                        </div>
                                        <div class="col q-mb-md">
                                            <div class="form_header text-subtitle2 text-primary text-weight-regular">
                                                Task Description</div>
                                            <q-input v-model="form.description" dense
                                                :rules="[val => val.length <= 50 || 'Please use maximum 50 characters']">
                                                <template v-slot:prepend>
                                                    <q-icon name="edit" color="orange" />
                                                </template>
                                            </q-input>
                                        </div>
                                    </q-card-section>
                                </q-card>
                            </div>

                        </div>
                    </div>
                </q-card-section>
                <q-card-section>
                    <div class="row justify-between items-center">
                        <div>
                            <q-btn outline color="negative" @click="confirm = true" :disable="form.processing">
                            Reset</q-btn>
                        </div>
                        <div class="row q-col-gutter-md">
                            <div>
                                <Link as="div" href="/">
                                <q-btn outline color="secondary" :disable="form.processing">Cancel</q-btn>
                                </Link>
                            </div>
                            <div>
                                <q-btn type="submit" outline color="primary" :loading="form.processing">{{ modeCreate ?
                                'Create' : 'Update' }}
                                    <template v-slot:loading>
                                        <q-spinner-hourglass />
                                    </template>
                                </q-btn>
                            </div>
                        </div>
                    </div>
                </q-card-section>
            </q-card>
        </q-form>
    </div>
    <q-dialog v-model="confirm" persistent>
        <q-card>
            <q-card-section class="row items-center bg-red-1">
                <q-avatar icon="front_hand" color="negative" text-color="white" />
                <span class="q-ml-sm">You can lose all of your data put in the form</span>
            </q-card-section>

            <q-card-actions align="right">
                <q-btn flat label="Cancel" color="primary" v-close-popup />
                <q-btn flat label="Reset" color="negative" v-close-popup @click="resetForm"/>
            </q-card-actions>
        </q-card>
    </q-dialog>




    </div>
</template>
