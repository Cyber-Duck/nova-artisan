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
            <h2>{{ details.name }}</h2>
            <p>{{ details.description }}</p>
            <div v-if="Object.keys(details.arguments).length">
                <h3>Arguments</h3>
                <div v-for="(item, index) in details.arguments">
                    <label for="item.name">{{ item.name }}</label>
                    <input type="text" :id="item.name" :name="item.name" required v-if="item.required">
                    <input type="text" :id="item.name" :name="item.name" v-else>
                </div>
            </div>
            <div v-if="Object.keys(details.options).length">
                <h3>Options</h3>
                <div v-for="(item, index) in details.options">
                    <label for="item.name">{{ item.name }}</label>
                    <input type="checkbox" :id="item.name" :name="item.name" required v-if="item.boolean">
                    <input type="text" :id="item.name" :name="item.name" required v-else-if="item.required">
                    <input type="text" :id="item.name" :name="item.name" v-else>
                </div>
            </div>
            <div>
                <button @click="run">Run</button>
                <button @click="cancel">Cancel</button>
            </div>

            <pre id="output" class="w-full h-full p-2 text-white"><code class="language-bash" ref="outputCode" v-text="output"></code></pre>
        </div>
    </div>
</template>

<style src="prismjs/themes/prism.css" />

<script>
import CodeMirror from 'codemirror'
import 'codemirror/mode/markdown/markdown'
import 'codemirror/mode/javascript/javascript'
import 'codemirror/mode/php/php'
import 'codemirror/mode/ruby/ruby'
import 'codemirror/mode/shell/shell'
import 'codemirror/mode/sass/sass'
import 'codemirror/mode/yaml/yaml'
import 'codemirror/mode/yaml-frontmatter/yaml-frontmatter'
import 'codemirror/mode/nginx/nginx'
import 'codemirror/mode/xml/xml'
import 'codemirror/mode/vue/vue'
import 'codemirror/mode/dockerfile/dockerfile'
import 'codemirror/keymap/vim'

import Prism from 'prismjs/components/prism-core'
import 'prismjs/components/prism-bash'

export default {
    data () {
        return {
            commands: {},
            details: false,
            commmand: false,
            codemirror: null,
            output: null,
        }
    },

    mounted() {
        this.list()
        const config = {
            tabSize: 4,
            indentWithTabs: true,
            lineWrapping: true,
            lineNumbers: true,
            mode: 'text/x-php',
            theme: 'dracula',
            extraKeys: {
                'Cmd-Enter': (cm) => {
                    this.sendCode()
                },
                'Ctrl-Enter': (cm) => {
                    this.sendCode()
                },
            }
        };
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
                    that.details = response.data
                    that.command = command
                })
                .catch(function (error) {
                    console.log(error)
                    that.details = false
                    that.command = null
                })
                .then(function () {
                    // always executed
                })
        },
        run () {
            Nova.request().post('/nova-vendor/nova-artisan/commands/'+this.command+'/run', {
            }).then(({data}) => {
                this.output = data;
                localStorage.setItem('tinker-tool-output', data);
                this.$nextTick(() => this.applyOutputStyles());
            });
        },
        cancel () {
            this.details = false
            this.command = null
        },
        applyOutputStyles() {
            Prism.highlightElement(this.$refs.outputCode)
        },
    },
}
</script>

<style>
    td,th {padding: 5px 10px; text-align: left;}
    thead tr, tbody tr:even {background: white;}
    /* Scoped Styles */
</style>
