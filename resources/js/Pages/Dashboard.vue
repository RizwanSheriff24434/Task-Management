<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import { VBtn, VSelect, VTextField, VTextarea, VDialog, VCard, VCardTitle, VCardText, VCardActions, VAlert, VToolbar, VToolbarTitle, VRow, VCol, VSnackbar } from 'vuetify/components';

const filterStatus = ref('All');

const props = defineProps({
    tasks: Array,
});

const filteredTasks = computed(() => {
    if (filterStatus.value === 'All') {
        return props.tasks;
    }
    return props.tasks.filter(task => task.status === filterStatus.value);
});


const successMessage = ref('');
const snackbar = ref(false);
const snackbarMessage = ref('');
const snackbarColor = ref('success');

const quote = ref('');
const author = ref('');
const showForm = ref(false);
const dialog = ref(false);
const deleteDialog = ref(false);
const taskToDelete = ref(null);

const editingTask = ref(null);

const form = useForm({
    title: '',
    description: '',
    status: 'Pending',
    due_date: '',
});


const fetchQuote = async () => {
    try {
        const response = await fetch('/api/quote');
        const data = await response.json();
        console.log(data);

        quote.value = data[0].q;
        author.value = data[0].a;

    } catch (error) {
        console.error("Quote fetch failed:", error);
        quote.value = "Keep going. You're doing great! 💪";
        author.value = "Motivator Bot";
    }
};



onMounted(() => {
    fetchQuote();
});

const editTask = async (taskId) => {
    try {
        const response = await axios.get(`/tasks/${taskId}`);
        const task = response.data;
        editingTask.value = task.id;
        form.title = task.title || '';
        form.description = task.description || '';
        form.status = task.status || 'Pending';
        form.due_date = task.due_date ? task.due_date.substring(0, 10) : '';
        dialog.value = true;
    } catch (error) {
        snackbarMessage.value = 'Failed to fetch task data.';
        snackbarColor.value = 'error';
        snackbar.value = true;
    }
};

const askDeleteTask = (taskId) => {
    taskToDelete.value = taskId;
    deleteDialog.value = true;
};

const confirmDeleteTask = async () => {
    try {
        await axios.delete(`/tasks/${taskToDelete.value}`);
        deleteDialog.value = false;
        taskToDelete.value = null;
        snackbarMessage.value = 'Task deleted successfully!';
        snackbarColor.value = 'success';
        snackbar.value = true;
        window.location.reload();
    } catch (error) {
        snackbarMessage.value = 'Failed to delete task.';
        snackbarColor.value = 'error';
        snackbar.value = true;
    }
};

const cancelDelete = () => {
    deleteDialog.value = false;
    taskToDelete.value = null;
};

const updateTask = () => {
    form.put(`/tasks/${editingTask.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            dialog.value = false;
            form.reset();
            editingTask.value = null;
            snackbarMessage.value = '✅ Task updated successfully!';
            snackbarColor.value = 'success';
            snackbar.value = true;
        },
        onError: () => {
            snackbarMessage.value = 'Failed to update task';
            snackbarColor.value = 'error';
            snackbar.value = true;
        }
    });
};

const cancelEdit = () => {
    dialog.value = false;
    editingTask.value = null;
    form.reset();
};

const saveTask = () => {
    if (!form.title.trim()) {
        snackbarMessage.value = 'Task Title is required!';
        snackbarColor.value = 'error';
        snackbar.value = true;
        return; // Stop saving if title is empty
    }

    if (editingTask.value) {
        // Update existing task
        form.put(`/tasks/${editingTask.value}`, {
            preserveScroll: true,
            onSuccess: () => {
                dialog.value = false;
                form.reset();
                editingTask.value = null;
                snackbarMessage.value = '✅ Task updated successfully!';
                snackbarColor.value = 'success';
                snackbar.value = true;
            },
            onError: () => {
                snackbarMessage.value = 'Failed to update task';
                snackbarColor.value = 'error';
                snackbar.value = true;
            }
        });
    } else {
        // Create new task
        form.post('/tasks', {
            preserveScroll: true,
            onSuccess: () => {
                dialog.value = false;
                form.reset();
                snackbarMessage.value = '✅ Task created successfully!';
                snackbarColor.value = 'success';
                snackbar.value = true;
            },
            onError: () => {
                snackbarMessage.value = 'Failed to create task';
                snackbarColor.value = 'error';
                snackbar.value = true;
            }
        });
    }
};


const openCreateForm = () => {
    editingTask.value = null;
    form.reset({
        title: '',
        description: '',
        status: 'Pending',
        due_date: '',
    });
    showForm.value = true;
    dialog.value = true;
};

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-8">
            <div class="mx-auto max-w-6xl sm:px-6 lg:px-8 space-y-6">
                <!-- Motivational Quote Section -->
                <v-alert type="info" border="start" color="blue" elevation="2" class="mb-4">
                    <div>
                        <p class="text-lg font-semibold">"{{ quote }}"</p>
                        <p class="text-sm text-right mt-2">— {{ author }}</p>
                    </div>
                </v-alert>

                <!-- Create Task Button and Filter Dropdown -->
                <v-row align="center" class="mb-4">
                    <v-col cols="auto">
                        <v-btn color="success" @click="openCreateForm" prepend-icon="mdi-plus">
                            Create New Task
                        </v-btn>
                    </v-col>
                    <v-col cols="auto">
                        <v-select v-model="filterStatus" :items="['All', 'Pending', 'In Progress', 'Completed']"
                            label="Filter Status" dense outlined hide-details style="min-width: 150px;" />
                    </v-col>
                </v-row>

                <!-- Create/Edit Task Dialog -->
                <v-dialog v-model="dialog" max-width="500">
                    <v-card>
                        <v-card-title>{{ editingTask ? 'Edit Task' : 'Create New Task' }}</v-card-title>
                        <v-card-text>
                            <v-form>
                                <v-text-field v-model="form.title" label="Task Title" outlined dense required />
                                <v-textarea v-model="form.description" label="Description" outlined dense />
                                <v-select v-model="form.status" :items="['Pending', 'In Progress', 'Completed']"
                                    label="Status" outlined dense />
                                <v-text-field v-model="form.due_date" label="Due Date" type="date" outlined dense />
                            </v-form>
                        </v-card-text>
                        <v-card-actions>
                            <v-btn v-if="!editingTask" color="primary" variant="flat" class="mx-1" @click="saveTask">
                                Save Task
                            </v-btn>
                            <v-btn v-else color="warning" variant="flat" class="mx-1" @click="updateTask">
                                Update Task
                            </v-btn>
                            <v-btn color="grey" variant="flat" class="mx-1"
                                @click="() => { cancelEdit(); dialog = false; }">
                                Cancel
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!-- Delete Confirmation Dialog -->
                <v-dialog v-model="deleteDialog" max-width="400">
                    <v-card>
                        <v-card-title class="text-h6">Confirm Delete</v-card-title>
                        <v-card-text>Are you sure you want to delete this task?</v-card-text>
                        <v-card-actions>
                            <v-btn color="error" variant="flat" class="mx-1" @click="confirmDeleteTask">Delete</v-btn>
                            <v-btn color="grey" variant="flat" class="mx-1" @click="cancelDelete">Cancel</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!-- Task List -->
                <div v-if="filteredTasks.length" class="space-y-4">
                    <v-card v-for="task in filteredTasks" :key="task.id" class="mb-2" elevation="2">
                        <v-card-text>
                            <div class="d-flex justify-space-between align-center">
                                <span class="font-weight-bold">📝 {{ task.title }}</span>
                                <div class="flex gap-3">
                                    <v-btn color="primary" variant="flat" class="mx-1"
                                        @click="editTask(task.id)">Edit</v-btn>
                                    <v-btn color="error" variant="flat" class="mx-1"
                                        @click="askDeleteTask(task.id)">Delete</v-btn>
                                </div>
                            </div>
                            <div>Due: {{ task.due_date ?? 'N/A' }}</div>
                            <div>
                                <v-chip :color="task.status === 'Completed' ? 'success' :
                                    task.status === 'In Progress' ? 'warning' :
                                        task.status === 'Pending' ? 'grey' : 'default'" text-color="white" class="ma-1"
                                    label variant="flat">
                                    {{ task.status }}
                                </v-chip>
                            </div>
                        </v-card-text>
                    </v-card>
                </div>

                <v-alert v-else type="info" color="grey" elevation="1">
                    No tasks available.
                </v-alert>

                <!-- Snackbar for notifications -->
                <v-snackbar v-model="snackbar" :color="snackbarColor" timeout="3000" location="top middle">
                    {{ snackbarMessage }}
                </v-snackbar>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
