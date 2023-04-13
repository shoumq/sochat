<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
</script>

<template>
    <Head :title="user.name"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Пользователь</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="shadow-lg bg-white mt-4 dialog rounded-md p-3">
                <div class="text-2xl">{{ user.name }}</div>
                <div class="text-lg">{{ user.email }}</div>

                <div class="text-lg mt-3">Лайков: {{ likesCountData }}</div>

                <div v-if="user.id !== $page.props.auth.user.id">
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

                        <button @click="addFriend" v-if="!isAdded"
                                class="inline-flex items-center mt-8 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Добавить в друзья
                        </button>

                        <button disabled v-else
                                class="inline-flex items-center mt-8 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Заявка отправленна
                        </button>
                    </div>
                </div>

                <div v-else>
                    <a :href="route('profile.edit')"
                       class="inline-flex items-center mt-8 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Редактировать профиль
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
            isLike: false,
            isAdded: false,
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
        },

        addFriend() {
            axios.post(`/friend/add/${this.$page.props.auth.user.id}/${this.user.id}`, {
                from_id: this.$page.props.auth.user.id,
                to_id: this.user.id
            })
                .then(() => {
                    this.isAdded = true
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

        axios.get(`/friend-info/${this.$page.props.auth.user.id}/${this.user.id}`)
            .then(response => {
                console.log(response)
                if (response.data.frCount === 1) {
                    this.isAdded = true
                }
            })

        window.Echo.channel('send_like_' + this.$page.props.auth.user.id)
            .listen('.send_like', response => {
                this.likesCountData = response.like_count

                if (response.like_str === "null") {
                    this.isLike = true
                }
            })

        window.Echo.channel('add_friend_' + this.$page.props.auth.user.id)
            .listen('.add_friend', response => {
                console.log(response)
            })
    },
}
</script>
