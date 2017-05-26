<template>
    <div class="content console">
        <div class="container-fluid">
            <div id="console-wrapper">
                <p class="console">
                    <span class="termPrompt" v-text="terminalPrompt"></span><span v-html="greeting"></span>
                    </br v-if="newLineTime">
                    <span v-if="newLineTime" class="termPrompt" v-text="terminalPrompt"></span><span v-if="newLineTime"><blink> |</blink></span>
                </p>
            </div>
        </div>
    </div>
</template>
<style>

</style>
<script>

export default{
        components: {

        },
        props: {
            user: {
                type: Object,
                required: true
            },
        },
        data(){
            return{
                captionLength: 0,
                caption: ' Welcome to the Twilio Auto-Dialer',
                greeting: '',
                newLineTime: false
            }
        },
        mounted() {
            this.type();
        },
        components:{

        },
        methods: {
            type: function () {
                this.captionLength++;
                this.greeting = this.caption.substr(0, this.captionLength);
                if(this.captionLength < this.greeting.length + 1) {
                    setTimeout(this.type, 50);
                } else {
                    this.newLineTime = true;
                }
            },
        },
        computed: {
            terminalPrompt: function() {
                return this.user.name + ':~$'
            }
        },
}
</script>
