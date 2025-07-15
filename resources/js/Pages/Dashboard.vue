<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

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

const quote = ref('');
const author = ref('');
const showForm = ref(false);

const editingTask = ref(null);

const form = useForm({
    title: '',
    description: '',
    status: 'Pending',
    due_date: '',
});

const fetchQuote = async () => {
    try {
        const response = await axios.get('https://api.quotable.io/random');
        quote.value = response.data.content;
        author.value = response.data.author;
    } catch (error) {
        quote.value = 'Stay positive. Work hard. Make it happen.';
        author.value = 'Unknown';
    }
};

onMounted(() => {
    fetchQuote();
});

const editTask = async (taskId) => {
    try {
        const response = await axios.get(`/tasks/${taskId}`);
        const task = response.data;
        console.log(task);

        editingTask.value = task.id;
        showForm.value = true;

        form.title = task.title || '';
        form.description = task.description || '';
        form.status = task.status || 'Pending';
        form.due_date = task.due_date ? task.due_date.substring(0, 10) : '';
    } catch (error) {
        alert('Failed to fetch task data.');
    }
};

const deleteTask = async (taskId) => {
    if (!confirm('Are you sure you want to delete this task?')) return;

    try {
        await axios.delete(`/tasks/${taskId}`);
        window.location.reload();
    } catch (error) {
        alert('Failed to delete task.');
    }
};

const updateTask = () => {
    form.put(`/tasks/${editingTask.value}`, {
        preserveScroll: true,
        onSuccess: () => {
            showForm.value = false;
            form.reset();
            editingTask.value = null;
            successMessage.value = '✅ Task updated successfully!';

            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        },
        onError: () => {
            alert('Failed to update task');
        }
    });
};

const cancelEdit = () => {
    showForm.value = false;
    editingTask.value = null;
    form.reset();
};

const saveTask = () => {
  if (!form.title.trim()) {
    alert('Task Title is required!');
    return; // Stop saving if title is empty
  }

  if (editingTask.value) {
    // Update existing task
    form.put(`/tasks/${editingTask.value}`, {
      preserveScroll: true,
      onSuccess: () => {
        showForm.value = false;
        form.reset();
        editingTask.value = null;
      },
    });
  } else {
    // Create new task
    form.post('/tasks', {
      preserveScroll: true,
      onSuccess: () => {
        showForm.value = false;
        form.reset();
      },
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
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded shadow">
                    <p class="text-lg font-semibold text-gray-800">“{{ quote }}”</p>
                    <p class="text-sm text-right italic text-gray-600">- {{ author }}</p>
                </div>

                <div v-if="successMessage"
                    class="p-3 rounded bg-green-100 text-green-800 border border-green-300 shadow">
                    {{ successMessage }}
                </div>

                <!-- Create Task Button and Filter Dropdown -->
                <div class="flex items-center space-x-2 mb-4">
                    <button @click="openCreateForm"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                        + Create New Task
                    </button>
                    <select v-model="filterStatus" class="border rounded px-2 py-1 text-sm">
                        <option value="All">All</option>
                        <option value="Pending">Pending</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>

                <!-- Create/Edit Task Form -->
                <div v-if="showForm" class="bg-white p-4 rounded shadow space-y-4">
                    <div>
                        <label>Task Title:</label>
                        <input v-model="form.title" type="text" class="border rounded w-full px-2 py-1"  required />
                    </div>
                    <div>
                        <label>Description:</label>
                        <textarea v-model="form.description" class="border rounded w-full px-2 py-1"></textarea>
                    </div>
                    <div>
                        <label>Status:</label>
                        <select v-model="form.status" class="border rounded w-full px-2 py-1">
                            <option>Pending</option>
                            <option>In Progress</option>
                            <option>Completed</option>
                        </select>
                    </div>
                    <div>
                        <label>Due Date:</label>
                        <input v-model="form.due_date" type="date" class="border rounded w-full px-2 py-1" />
                    </div>
                    <div>
                        <button v-if="!editingTask" @click="saveTask"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                            Save Task
                        </button>

                        <button v-else @click="updateTask"
                            class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                            Update Task
                        </button>

                        <button @click="cancelEdit"
                            class="ml-2 px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500">
                            Cancel
                        </button>
                    </div>
                </div>

                <!-- Task List -->
                <div v-if="filteredTasks.length" class="space-y-4">
                    <div v-for="task in filteredTasks" :key="task.id"
                        class="bg-white p-4 rounded shadow flex flex-col space-y-1">
                        <div class="flex items-center justify-between">
                            <p class="font-semibold">📝 {{ task.title }}</p>
                            <div class="space-x-2">
                                <button @click="editTask(task.id)" class="text-blue-600 hover:underline">Edit</button>

                                <button @click="deleteTask(task.id)"
                                    class="text-red-600 hover:underline">Delete</button>
                            </div>
                        </div>
                        <p>Due: {{ task.due_date ?? 'N/A' }}</p>
                        <p>Status: {{ task.status }}</p>
                    </div>
                </div>

                <div v-else class="text-gray-500">
                    No tasks available.
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
