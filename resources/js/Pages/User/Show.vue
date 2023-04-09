<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
</script>

<template>
    <Head title="Messages"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Messages</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
<!--            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mt-8 mb-8" role="alert" v-if="likes">-->
<!--                <p class="font-bold">Вам поставили лайк</p>-->
<!--                <p>{{ likes }}</p>-->
<!--            </div>-->

            <div class="shadow-lg bg-white mt-4 dialog rounded-md p-3">
                <div class="text-2xl">{{ user.name }}</div>
                <div class="text-lg">{{ user.email }}</div>

                <div class="text-lg mt-3">Лайков: {{ likesCountData }}</div>

                <div class="flex gap-5">
                    <button @click="sendLike" v-if="isLike" disabled
                            class="inline-flex items-center mt-8 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Лайкнуто
                    </button>

                    <button @click="sendLike" v-else
                            class="inline-flex items-center mt-8 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Лайкнуть
                    </button>

                    <a href="#"
                            class="inline-flex items-center mt-8 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Написать сообщение
                    </a>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "Show",

    props: [
        'user',
        'likesCount'
    ],

    data() {
        return {
            likes: '',
            likesCountData: this.likesCount,
            isLike: false
        }
    },

    methods: {
        sendLike() {
            axios.post(`/users/${this.user.id}`, {
                from_id: this.$page.props.auth.user.id
            })
                .then((response) => {
                    this.likesCountData = response.data.like_count
                    this.isLike = true
                })
        }
    },

    mounted() {
        axios.get(`/users-like/${this.user.id}`)
            .then(response => {
                if (response.data.myCount === 1) {
                    this.isLike = true
                }
            })

        window.Echo.channel('send_like_' + this.$page.props.auth.user.id)
            .listen('.send_like', response => {
                if (response.like_str === "null") {
                    this.isLike = true
                }
            })
    },
}
</script>
