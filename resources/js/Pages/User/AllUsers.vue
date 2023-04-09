<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
</script>

<template>
    <Head title="Messages"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" @click="toBottom">Messages</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-4  gap-4">
            <a :href="'/users/' + item.id" v-for="item in users" class="shadow-lg bg-white mt-4 rounded-md p-2">
                <div class="text-2xl">{{ item.name }}</div>
                <div class="text-lg">{{ item.email }}</div>
            </a>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "AllUsers",

    props: ['users'],

    data() {
        return {
            body: ''
        }
    },

    methods: {
        store() {
            if (this.body) {
                axios.post('/messages', {
                    body: this.body
                }).then(() => {
                    this.body = '';
                })
            } else {
                window.alert('Введите сообщение')
            }
        },
    },

    mounted() {
        window.Echo.channel('store_message')
            .listen('.store_message', response => {
                const dialog = this.$refs.dialog;
                dialog.scrollTop = dialog.scrollHeight;
                this.messages.push(response.message);
            })
    },
}
</script>
