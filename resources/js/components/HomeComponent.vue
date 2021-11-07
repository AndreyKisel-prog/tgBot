<template>
    <div>
        <notifications group="err"/>
        <div class="form-wrap container w-50">
            <div class="form-group">
                <h1>Enter web link</h1>
                <input
                    v-model="fullWebLink"
                    :class="{'form-control':true, 'is-invalid' : !isValidURL() && isFormSubmitted}">
                <div class="invalid-feedback">A valid web link required</div>
            </div>
            <div class="form-group">
                <a type="submit" href="#" v-on:click.stop.prevent="handleSubmit"
                   class="btn btn-lg btn-success">Submit</a>
            </div>
            <div v-if="shortWebLink" class="alert alert-success" role="alert">
                <h5>Thank you</h5>
                <p>Your short link is {{ shortWebLink }}</p>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: function () {
        return {
            fullWebLink: '',
            shortWebLink: '',
            isFormSubmitted: false,
            errors: ''
        }
    },
    methods: {
        handleSubmit() {
            this.isFormSubmitted = true;
            this.shortWebLink = '';
            this.errors = '';

            if (!this.isValidURL()) {
                return
            }

            this.sendWebLink()
        },
        isValidURL() {
            let url;
            return true
            try {
                url = new URL(this.fullWebLink);
            } catch (_) {
                return false;
            }

            return url.protocol === "http:" || url.protocol === "https:";
        },
        sendWebLink() {
            axios.post(`api/url`, {url: this.fullWebLink})
                .then((resp) => {
                    this.shortWebLink = resp.data;
                }).catch((error) => {
                this.errors = error.response.data.errors.url;
                this.notifyAboutErrors();
            })
        },
        notifyAboutErrors() {
            this.errors.forEach((e) => {
                this.$notify({
                    group: 'err',
                    title: 'Error message',
                    type: 'warn',
                    text: e
                });
            })
        }
    }
}
</script>
