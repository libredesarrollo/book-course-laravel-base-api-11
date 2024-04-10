<template>

    <!-- <router-link :to="{ name:'save' }">Create</router-link> -->

    <o-modal v-model:active="confirmDeleteAction">
        <div class="p-4">
            <p>Are you sure you want to delete the selected record?</p>
        </div>

        <div class="flex flex-row-reverse gap-2 bg-gray-100 p-3">
            <o-button variant="danger" @click="deletePost">Delete</o-button>
            <o-button @click="confirmDeleteAction = false">Cancel</o-button>
        </div>

    </o-modal>

    <h1>Post List</h1>

    <o-button iconLeft="plus" @click="$router.push({ name: 'save' })">Create</o-button>

    <div class="mb-5"></div>

    <o-table :data="posts.data" :loading="isLoading">
        <o-table-column field="id" label="ID" v-slot="p">
            {{ p.row.id }}
        </o-table-column>
        <o-table-column field="title" label="Title" v-slot="p">
            {{ p.row.title }}
        </o-table-column>
        <o-table-column field="posted" label="Posted" v-slot="p">
            {{ p.row.posted }}
        </o-table-column>
        <o-table-column field="category_id" label="Category" v-slot="p">
            {{ p.row.category.title }}
        </o-table-column>
        <o-table-column field="category_id" label="Actions" v-slot="p">

            <router-link class="mr-3" :to="{ name: 'save', params: { 'slug': p.row.slug } }">Edit</router-link>
            <o-button iconLeft="delete" variant="danger" size="small" rounded @click="deletePostRow = p; confirmDeleteAction=true">Delete</o-button>
        </o-table-column>
    </o-table>

    <div class="mb-5"></div>

    <o-pagination v-if="posts.data && posts.data.length > 0" @change="updatePage" :total="posts.total"
        v-model:current="currentPage" :range-before="2" :range-after="2" size="small" :simple="false" :rounded="true"
        :per-page="posts.per_page">
    </o-pagination>
</template>
<script>
export default {

    data() {
        return {
            posts: [],
            isLoading: true,
            currentPage: 1,
            confirmDeleteAction: false,
            deletePostRow: ''
        }
    },
    mounted() {
        this.listPage()
    },
    methods: {
        updatePage() {
            setTimeout(() => {
                this.listPage()
            }, 100);
        },
        listPage() {
            this.isLoading = true
            this.$axios.get(this.$root.urls.postPaginate+'?page=' + this.currentPage).then((res) => {
                this.posts = res.data
                this.isLoading = false
            })
        },
        deletePost() {
            this.confirmDeleteAction = false

            this.$oruga.notification.open({
                message: 'Delete success',
                position:'bottom-right',
                variant: 'danger',
                duration: 4000,
                closable:true
            })

            this.$axios.delete(this.$root.urls.postDelete + this.deletePostRow.row.id)
            this.posts.data.splice(this.deletePostRow.index, 1)
        }
    }
}
</script>