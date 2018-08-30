<template>
    <div>
        <heading class="mb-6">Task list</heading>
        <div v-if="!details">
            <table class="table-auto">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Description</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in commands">
                        <td>{{ index }}</td>
                        <td>{{ item }}</td>
                        <td><a @click="view(index)">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div v-if="details">
        </div>
    </div>
</template>

<script>
export default {
    data () {
        return {
            commands: {},
            details: false
        }
    },
    methods: {
        list () {
            let that = this;
            Nova.request().get('/nova-vendor/nova-artisan/commands')
                .then(function (response) {
                    console.log(that.commands)
                    that.commands = response.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
                .then(function () {
                    // always executed
                })
        },
        view (command) {
            let that = this;
            Nova.request().get('/nova-vendor/nova-artisan/commands/'+command)
                .then(function (response) {
                    console.log(response.data)
                    that.details = true;
                })
                .catch(function (error) {
                    console.log(error);
                    that.details = false;
                })
                .then(function () {
                    // always executed
                })
            //
        }
    },
    mounted() {
        this.list()
    }
}
</script>

<style>
    td,th {padding: 5px 10px; text-align: left;}
    thead tr, tbody tr:even {background: white;}
    /* Scoped Styles */
</style>
