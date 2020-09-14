<template>
    <div class="document-preview">
        <div v-if="document.document_file_class.extension == 'pdf'" class="pdf-preview" :class="{ 'fixed-height': fixedHeight }">
            <a :href="document.document_url" target="_blank">
                <pdf :src="'/storage/documents/'+type+'/'+document.location"></pdf>
            </a>
        </div>

        <!-- JPG -->
        <div v-else-if="document.document_file_class.extension == 'jpeg'" class="img-preview" :style="{ backgroundImage: 'url(\'/storage/documents/'+type+'/'+document.location+'\')' }"></div>

        <!-- Unknown -->
        <div v-else class="text-center my-5">
            Unable to display preview
        </div>
    </div>
</template>

<script>
import pdf from 'vue-pdf'
export default {
    components: {
           pdf,
    },
    
    props: {
        document: {
            type: Object,
            required: true,
        },
        type: {
            type: String,
            default: 'unsorted',
        },
        fixedHeight: {
            type: Boolean,
            default: false,
        }
    }
}
</script>