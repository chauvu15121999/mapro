<template>
    <div class="container-fluid p-0">
        <navHeader
            :user="user"
        ></navHeader>
        <router-view
            :user="user"
        ></router-view>
    </div>
</template>

<style>
</style>
<script>
    import navHeader from './nav'
    export default{
        name: 'App',
        props: ['user','teamtype'],
        components: {
            navHeader,
        },
    }
</script>