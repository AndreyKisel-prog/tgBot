<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
                <div class="card border-0 shadow rounded-3 my-5">
                    <div class="card-body p-4 p-sm-5">
                        <h5 class="card-title text-center mb-5 fw-light fs-5">Enter web link</h5>
                        <form>
                            <div class="form-floating mb-3">
                                <input v-model="fullWebLink" class="form-control" id="floatingInput">
                            </div>
                            <div class="form-floating mb-3">
                                <input v-model="fullWebLink" class="form-control" id="floatingInput">
                            </div>
                            <div>
                                <button @click="handleSubmit" class="btn btn-primary text-uppercase fw-bold w-100">
                                    Send
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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
            error: ''
        }
    },
    methods: {
        handleSubmit(e) {
            e.preventDefault();
            if (!this.isValidURL()) {
                alert('url is incorrect')
                return
            }

            this.sendWebLink()
        },
        async sendWebLink() {
            let response = axios.post(`/url`, { url: this.fullWebLink })
        },
        isValidURL() {
            let url;

            try {
                url = new URL(this.fullWebLink);
            } catch (_) {
                return false;
            }

            return url.protocol === "http:" || url.protocol === "https:";
        }
    }
}
</script>
