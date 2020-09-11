<template>
    <section class="all-documents">
        <h1 class="title">All documents</h1>
        <div class="row">
            <div class="col">
                <div v-if="loading" class="text-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>
                </div>
                <div v-else>
                    <table v-if="documents.length" class="table table-light table-striped shadow-sm">
                        <thead>
                            <th>Name</th>
                            <th>Date</th>
                            <th></th>
                        </thead>
                        <tbody>
                            <tr v-for="(document, i) in documents" :key="i">
                                <th class="align-middle"><a :href="document.document_url" target="_blank">{{ document.name }}</a></th>
                                <td class="align-middle">{{ document.created_at }}</td>
                                <td>
                                    <a :href="route('docs.download', { document: document.id })" title="Download"><i class="icon-btn ri-download-line bg-primary" title="Download"></i></a>
                                    <a :href="document.document_url" target="_blank" title="View"><i class="icon-btn ri-eye-line bg-info"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <span v-else class="text-muted">You don't have any tracked documents yet. <strong><a :href="route('docs.unsorted')">Get started</a></strong></span>
                </div>
            </div>
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
        this.getAllDocuments();
    },

    methods: {
        getAllDocuments() {
            this.loading = true;

            axios.get(route('api.documents.index')).then(response => {
                this.documents = response.data.documents;
                console.log(this.documents);
            }).catch(e => this.niceError(e)).finally(() => {
                this.loading = false;
            });
        }
    }
}
</script>