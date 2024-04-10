<template lang="">
    <div>
        <router-view></router-view>
    </div>
</template>
<script>
export default {
    data() {
        return {
            urls: {
                postUpload:'/api/post/upload/',
                postPaginate:'/api/post/',
                postPatch:'/api/post/',
                postPost:'/api/post/',
                postDelete:'/api/post/',
                getPostBySlug:'/api/post/slug/',
                getCategoriesAll:'/api/category/all',
            }
        }
    },
}
</script>