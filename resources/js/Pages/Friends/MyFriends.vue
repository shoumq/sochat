<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head} from '@inertiajs/vue3';
</script>

<template>
    <Head title="Друзья"/>

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Пользователь</h2>
        </template>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-4 gap-4">
            <a :href="'/users/' + item.friend_name[0].id" v-for="item in users" class="shadow-lg bg-white mt-4 rounded-md p-2">
                <!--                    <div class="text-2xl">{{ checkName(item.from_id, item.to_id) }}</div>-->
                <div class="text-2xl">{{ item.friend_name[0].name }}</div>
                <div class="text-xl">{{ item.friend_name[0].email }}</div>
            </a>
        </div>
    </AuthenticatedLayout>
</template>

<script>
export default {
    name: "Friends",

    props: [
        'users',
    ],

    methods: {
        checkName(from, to) {
            if (from !== this.$page.props.auth.user.id) {
                return to
            } else {
                return from
            }
        }
    }
}
</script>
