<template>
    <section class="unsorted-docs">
        <h1 class="title">Unsorted documents <span class="badge" :class="{ 'badge-primary': documents.not_tracked.length > 0, 'badge-secondary': documents.not_tracked.length == 0 }">{{ documents.not_tracked.length }}</span></h1>
        
        <div v-if="documents.not_tracked.length" class="alert alert-info">
            To add more, copy newly scanned documents to <strong>{{ unsortedPath }}</strong>
        </div>

        <div v-if="loading" class="col-12 text-center">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        
        <div v-else>
            <template v-if="documents.not_tracked.length">
                <div class="card-columns">
                    <div v-for="(document, i) in documents.not_tracked" :key="i" class="card" :class="{loading: document.isLoading}">
                        
                        <document-preview :document="document" type="unsorted"></document-preview>

                        <div class="card-body">
                            <div class="form-group">
                                <label :for="'doc-name-'+i">Document name</label>
                                <input v-model="document.name" type="text" class="form-control" :id="'doc-name-'+i">
                            </div>
                            <div class="form-group">
                                <label :for="'doc-tags-'+i">Tags</label>
                                <vue-tags-input 
                                    :tags="document.tags"
                                    :add-on-key="[',', 13]"
                                    :avoid-adding-duplicates="true"
                                    :add-on-blur="true"
                                    v-model="document.unsavedTags"
                                    @tags-changed="newTags => document.tags = newTags"
                                    :autocomplete-items="existingTags"
                                    :id="'doc-tags-'+i" />
                            </div>
                            <div class="form-group">
                                <label :for="'doc-date-'+i">Date</label>
                                <datepicker v-model="document.date"></datepicker>
                            </div>
                            <button class="btn btn-primary" @click="saveDocument(document, i)">Save</button>
                        </div>
                    </div>
                </div>
            </template>
            <div v-else class="card col-12 col-md-6 col-lg-4">
                <div class="card-body">
                    <h3 class="mb-2"><i class="ri-information-line"></i> Getting started</h3>
                    <p class="mb-4">You don't have any documents waiting to be tracked yet. Follow the instructions below to start organising your documents!</p>
                    <ol>
                        <li>Scan the documents you want to track as PDF files.</li>
                        <li>Copy the scanned documents to <strong>{{ unsortedPath }}</strong></li>
                        <li><a :href="route('docs.unsorted')">Refresh this page</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import pdf from 'vue-pdf'
import VueTagsInput from '@johmun/vue-tags-input';
import Datepicker from 'vuejs-datepicker';

export default {
    components: {
        pdf,
        VueTagsInput,
        Datepicker,
    },

    props: {
        unsortedPath: {
            type: String,
            required: true,
        }
    },

    data() {
        return {
            documents: {
                not_tracked: [],
                fullpath: null,
            },
            existingTags: [],
            loading: false,
        }
    },

    mounted() {
        this.getUnsortedDocuments();

        this.getTags();
    },

    methods: {
        getUnsortedDocuments() {
            this.loading = true;

            axios.get(route('api.documents.unsorted')).then(response => {
                let defaultDate = new Date();

                this.documents.not_tracked = response.data.unsorted.map(doc => {
                    return {
                        tags: [],
                        unsavedTags: '',
                        date: defaultDate,
                        ...doc,
                    }
                });
            }).catch(e => this.niceError(e)).finally(() => {
                this.loading = false;
            });
        },

        getTags() {
            axios.get(route('api.tags.index')).then(response => {
                this.existingTags = response.data.tags.map(tag => {
                    return {
                        id: tag.id,
                        text: tag.name
                    }
                });
            }).catch(e => this.niceError(e));
        },

        saveDocument(doc, i) {
            doc.isLoading = true;

            // Create the document entry
            axios.post(route('api.document.store'), doc).then(response => {
                if(response.data.success == true) {
                    // Show success message
                    this.$toast.success(doc.name+' has been indexed');

                    doc.unsavedTags = '';

                    // Get newly created tags (if any)
                    this.getTags();

                    // Remove this document from the pile
                    this.documents.not_tracked.splice(i, 1);
                }
            }).catch(e => this.niceError(e)).finally(() => {
                doc.isLoading = false;
            });
        }
    }
}
</script>