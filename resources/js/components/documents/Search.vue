<template>
    <section class="document-search">
        <h1 class="title">Search</h1>

        <input type="text" v-model.trim="query" class="form-control form-control-lg large-search-input" placeholder="Type anything..." @keyup="search">

        <section v-if="query.trim()">
            <div v-if="loading" class="row">
                <div class="col-12 text-center mt-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
            </div>

            <div v-else class="row mt-5">
                <template v-if="results.length">
                    <div v-for="(document, i) in results" :key="i" class="col-12 col-md-3">
                        <div class="card h-100 shadow-sm">
                            <div class="pdf-preview fixed-height">
                                <pdf :src="'/storage/documents/sorted/'+document.location"></pdf>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ document.name }}</h5>

                                <div class="date mt-auto text-muted">
                                    <p class="card-text"><strong>{{ document.created_at }}</strong></p>
                                    <a :href="route('docs.download', { document: document.id })" title="Download"><i class="icon-btn ri-download-line bg-primary" title="Download"></i></a>
                                    <a :href="document.document_url" target="_blank" title="View"><i class="icon-btn ri-eye-line bg-info"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>

                <div v-else class="col-12 text-muted h3 text-center">
                    <p>Sorry, we came up empty ☹</p>
                </div>
            </div>
        </section>
    </section>
</template>

<script>
import pdf from 'vue-pdf'

export default {
    components: {
        pdf,
    },

    data() {
        return {
            query: "",
            timeout: null,
            loading: false,
            results: [],
        }
    },

    destroyed() {
        if(this.timeout) clearInterval(this.timeout);
    },

    methods: {
        search() {
            this.loading = true;
            if (this.timeout) clearTimeout(this.timeout);

            this.timeout = setTimeout(() => {
                this.loading = true;

                axios.post(route('api.documents.search'), { query: this.query }).then(response => {
                    console.log(response);
                    this.results = response.data.results;
                }).catch(e => this.niceError(e)).finally(() => {
                    this.loading = false;
                });
            }, 500);
        }
    }
}
</script>