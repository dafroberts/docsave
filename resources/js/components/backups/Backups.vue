<template>
    <section class="backups">
        <div class="d-flex justify-content-between title">
            <h1>Backups</h1>
            <div>
                <button class="btn btn-primary" @click="createBackup" :disabled="loading.generatingBackup">
                    <template v-if="loading.generatingBackup">
                        Generating...
                    </template>
                    <template v-else>
                        Create backup
                    </template>
                </button>
            </div>
        </div>

        <section class="previous-backups">
            <div v-if="loading.existingBackups" class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Fetching existing backups...</span>
                </div>
            </div>
            <template v-else-if="backups.length">
                <table class="table table-light table-striped shadow-sm table-hover">
                    <thead>
                        <tr>
                            <th>Filename</th>
                            <th>Date</th>
                            <th class="text-center">File(s)</th>
                            <th class="text-center">File size</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(backup, i) in backups" :key="i" @click="downloadBackup(backup)" class="cursor-pointer">
                            <td>{{ backup.filename }}</td>
                            <td>{{ format(new Date(backup.created_at), 'dd-LL-y - HH:mm') }}</td>
                            <td class="text-center">{{ backup.file_count }}</td>
                            <td class="text-center">{{ backup.filesize_mb }}</td>
                        </tr>
                    </tbody>
                </table>
            </template>
            <span v-else class="text-muted">You haven't created any backups yet. <strong><a :href="route('docs.unsorted')">Create one now?</a></strong></span>

            <!-- Shown when a newly created backup has been generated -->
            <b-modal id="new-backup" title="Your backup is ready!" centered>
                <strong>{{ lastBackup.file_count }} files</strong> have been successfully archived and saved. Download them below or return to this page later.

                <template v-slot:modal-footer="{ ok, cancel }">
                    <b-button variant="secondary" @click="cancel()">
                        Dismiss
                    </b-button>
                    <b-button variant="primary" @click="ok()" :href="route('backups.download', {backup: lastBackup})">
                        Download ({{ lastBackup.filesize_mb }}MB)
                    </b-button>
                </template>
            </b-modal>
        </section>
    </section>
</template>
<script>
export default {
    data() {
        return {
            loading: {
                existingBackups: false,
                generatingBackup: false,
            },
            backups: [],
            lastBackup: {
                file_count: 0,
                filename: null,
                filesize_mb: 0,
            },
        }
    },

    mounted() {
        this.getBackups();
    },

    methods: {
        getBackups() {
            this.loading.existingBackups = true;

            axios.get(route('api.backups.index')).then(r => {
                this.backups = r.data;
            }).catch(e => this.niceError(e)).finally(() => {
                this.loading.existingBackups = false;
            });
        },

        createBackup() {
            this.loading.generatingBackup = true;

            axios.get(route('api.backups.create')).then(r => {
                console.log(r.data);
                this.lastBackup = r.data.backup;
                this.$bvModal.show('new-backup');
                this.getBackups();
            }).catch(e => this.niceError(e)).finally(() => {
                this.loading.generatingBackup = false;
            });
        },

        downloadBackup(backup) {
            return window.location = route('backups.download', {backup: backup});
        }
    }
}
</script>