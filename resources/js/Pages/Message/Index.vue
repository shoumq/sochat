<template>
    <div class="w-3/4 mx-auto">
        <div>
            Messages
        </div>

        <div class="flex justify-between gap-1 w-48">
            <input type="text" v-model="body"
                   class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1 block w-full">
            <button @click="store"
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                Send
            </button>
        </div>

        <div>
            <div v-for="item in messages">
                <p>{{ item.id }}</p>
                <p>{{ item.body }}</p>
                <p>{{ item.time }}</p>
            </div>
        </div>
    </div>
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

    created() {
        window.Echo.channel('store_message')
            .listen('.store_message', response => {
                console.log(response)
            })
    },

    methods: {
        store() {
            axios.post('/messages', {
                body: this.body
            }).then(response => {
                this.messages.unshift(response.data);
                this.body = '';
            })
        }
    }
}
</script>

<style scoped>

</style>
