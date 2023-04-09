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

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow-lg bg-white mt-4 dialog rounded-md" id="dialog" ref="dialog">
                <div v-for="item in messages" class="flex justify-between border-2 border-transparent border-b-slate-200 p-2 last_none">
                    <div>
                        <a :href="'users/' + item.user_id">{{ item.name }}</a>
                        <p>{{ item.body }}</p>
                    </div>
                    <p class="text-sm">{{ item.time }}</p>
                </div>
            </div>

            <form @submit.prevent="store" class="flex justify-between gap-5 items-center">
                <input type="text" v-model="body"
                       class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
                <button
                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Send
                </button>
            </form>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "Index",

    props: ['messages'],

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
                    const dialog = this.$refs.dialog;
                    dialog.scrollTop = dialog.scrollHeight;
                })
            } else {
                window.alert('Введите сообщение')
            }
        }
    },

    mounted() {
        const dialog = this.$refs.dialog;
        dialog.scrollTop = dialog.scrollHeight;

        window.Echo.channel('store_message')
            .listen('.store_message', response => {
                this.messages.push(response.message);

                const dialog = this.$refs.dialog;
                dialog.scrollTop = dialog.scrollHeight;
            })
    },
}
</script>

<style scoped>
.dialog {
    padding: 30px;
    overflow-y: scroll;
    height: 72vh;
    margin-bottom: 30px;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.dialog::-webkit-scrollbar {
    display: none;
}

.last_none:last-child {
    border: none !important;
}
</style>
