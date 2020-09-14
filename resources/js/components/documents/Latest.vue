<template>
    <section class="latest-documents">
        <h1 class="title">Latest documents</h1>
        <div class="row">
            <div v-if="loading" class="col-12 text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <template v-else>
                <template v-if="documents.length">
                    <div v-for="(document, i) in documents" :key="i" class="col-12 col-md-3">
                        <div class="card h-100 shadow-sm">

                            <document-preview :document="document" :fixed-height="true" type="sorted"></document-preview>

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
                <div v-else class="col-12">
                    <span class="text-muted">You don't have any tracked documents yet. <strong><a :href="route('docs.unsorted')">Get started</a></strong></span>
                </div>
            </template>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            documents: [],
            loading: false,
        }
    },

    mounted() {
        this.getLatestDocuments();
    },

    methods: {
        getLatestDocuments() {
            this.loading = true;
            axios.get(route('api.documents.latest')).then(response => {
                this.documents = response.data.documents;
                console.log(this.documents);
            }).catch(e => this.niceError(e)).finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>