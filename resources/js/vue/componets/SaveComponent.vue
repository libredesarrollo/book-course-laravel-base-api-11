<template>

    <h1 v-if="post">Update Post <span class="font-bold">{{ post.title }}</span></h1>
    <h1 v-else>Create Post</h1>

    <div class="grid grid-cols-2 gap-3">
        <div class="col-span-2">
            <o-field label="Title" :variant="errors.title ? 'danger' : 'primary'" :message="errors.title">
                <o-input v-model="form.title"></o-input>
            </o-field>
        </div>
        <o-field label="Content" :variant="errors.content ? 'danger' : 'primary'" :message="errors.content">
            <o-input v-model="form.content" type="textarea"></o-input>
        </o-field>
        <o-field label="Description" :variant="errors.description ? 'danger' : 'primary'" :message="errors.description">
            <o-input v-model="form.description" type="textarea"></o-input>
        </o-field>
        <o-field label="Slug" :variant="errors.slug ? 'danger' : 'primary'" :message="errors.slug">
            <o-input v-model="form.slug"></o-input>
        </o-field>
        <o-field label="Posted" :variant="errors.posted ? 'danger' : 'primary'" :message="errors.posted">
            <o-select v-model="form.posted" placeholder="Selected a option">
                <option value="yes">Yes</option>
                <option value="Not">No</option>
            </o-select>
        </o-field>
        <o-field label="Category" :variant="errors.category_id ? 'danger' : 'primary'" :message="errors.category_id">
            <o-select v-model="form.category_id" placeholder="Selected a option">
                <option value=""></option>
                <option v-for="c in categories" v-bind:key="c.id" :value="c.id">{{ c.title }}</option>
            </o-select>
        </o-field>


    </div>
    <div class="mt-3"></div>
    <o-button variant="primary" @click="send">Send</o-button>

    <div class="flex gap-2 mt-5" v-if="post">
        <o-field :message="fileError" :variant="fileError ? 'danger' : 'primary'">
            <o-upload v-model="file">
                <o-button tag="upload-tag" variant="primary">
                    <o-icon icon="upload"></o-icon>
                    <span>Click to upload</span>
                </o-button>
            </o-upload>
        </o-field>

        <o-button icon-left="upload" @click="upload">
            Upload
        </o-button>
        
    </div>

    <h3>Drag and Drop</h3>

    <o-field :message="fileError" :variant="fileError ? 'danger' : 'primary'">
        <o-upload v-model="filesDaD" multiple drag-drop>
            
                <section>
                    <o-icon icon="upload"></o-icon>
                    <span>Drag and Drop area</span>
                </section>
            
        </o-upload>
    </o-field>

    <span v-for="(file, index) in filesDaD" :key="index" >
        {{ file.name }}
    </span>

</template>
<script>
export default {
    async mounted() {

        if (this.$route.params.slug) {
            this.post = await this.$axios.get(this.$root.urls.getPostBySlug + this.$route.params.slug)
            this.post = this.post.data
            this.initPost()
        }

        this.getCategory()
    },
    data() {
        return {
            post: '',
            form: {
                title: '',
                slug: '',
                description: '',
                content: '',
                category_id: '',
                posted: '',

            },
            errors: {
                title: '',
                slug: '',
                description: '',
                content: '',
                category_id: '',
                posted: '',
            },
            fileError:'',
            file: null,
            filesDaD:[],
            categories: []
        }
    },
    methods: {
        
        initPost() {
            this.form.title = this.post.title
            this.form.content = this.post.content
            this.form.posted = this.post.posted
            this.form.description = this.post.description
            this.form.slug = this.post.slug
            this.form.category_id = this.post.category_id
        },

        upload(){
            this.fileError=''
            const formData = new FormData()

            formData.append('image', this.file)
            this.$axios.post(this.$root.urls.postUpload+this.post.id, formData, {
                headers: {
                    'Content-Type' : 'multipart/form-data'
                }
            }).then((res)=> {
                console.log(res);
            }).catch((error)=> {
                this.fileError=error.response.data.message

            })
        },

        cleanErrorsForm() {
            this.errors.title = ''
            this.errors.content = ''
            this.errors.posted = ''
            this.errors.description = ''
            this.errors.slug = ''
            this.errors.category_id = ''
        },
        getCategory() {
            this.$axios.get(this.$root.urls.getCategoriesAll).then((res) => {
                this.categories = res.data
            })
        },
        send() {
            this.cleanErrorsForm()

            if (this.post == '') {
                // create
                this.$axios.post(this.$root.urls.postPost, this.form).then(res => {
                    console.log(res)

                    this.$oruga.notification.open({
                        message: 'Record created success',
                        position:'bottom-right',
                        duration: 4000,
                        closable:true
                    })

                }).catch(error => {
                    if (error.response.data.errors.title) {
                        this.errors.title = error.response.data.errors.title[0]
                    }
                    if (error.response.data.errors.slug) {
                        this.errors.slug = error.response.data.errors.slug[0]
                    }
                    if (error.response.data.errors.description) {
                        this.errors.description = error.response.data.errors.description[0]
                    }
                    if (error.response.data.errors.posted) {
                        this.errors.posted = error.response.data.errors.posted[0]
                    }
                    if (error.response.data.errors.content) {
                        this.errors.content = error.response.data.errors.content[0]
                    }
                    if (error.response.data.errors.category_id) {
                        this.errors.category_id = error.response.data.errors.category_id[0]
                    }
                })
            } else {
                // update
                this.$axios.patch(this.$root.urls.postPatch + this.post.id, this.form).then(res => {

                    this.$oruga.notification.open({
                        message: 'Record updated success',
                        position:'bottom-right',
                        duration: 4000,
                        closable:true
                    })   

                    console.log(res)
                }).catch(error => {
                    if (error.response.data.errors.title) {
                        this.errors.title = error.response.data.errors.title[0]
                    }
                    if (error.response.data.errors.slug) {
                        this.errors.slug = error.response.data.errors.slug[0]
                    }
                    if (error.response.data.errors.description) {
                        this.errors.description = error.response.data.errors.description[0]
                    }
                    if (error.response.data.errors.posted) {
                        this.errors.posted = error.response.data.errors.posted[0]
                    }
                    if (error.response.data.errors.content) {
                        this.errors.content = error.response.data.errors.content[0]
                    }
                    if (error.response.data.errors.category_id) {
                        this.errors.category_id = error.response.data.errors.category_id[0]
                    }
                })
            }


        }
    },
    watch:{
        filesDaD: {
            handler(val) {
                // return console.log(val[val.length-1])

                this.fileError=''
                const formData = new FormData()

                formData.append('image', val[val.length-1])
                this.$axios.post(this.$root.urls.getCategoriesAll+this.post.id, formData, {
                    headers: {
                        'Content-Type' : 'multipart/form-data'
                    }
                }).then((res)=> {
                    console.log(res);
                }).catch((error)=> {
                    this.fileError=error.response.data.message

                })  
            },
            deep: true
        }
    }
}
</script>