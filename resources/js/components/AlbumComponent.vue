<template>
    <div>
        <form @submit.prevent="createAlbum" action="" enctype="multipart/form-data">
            <div class="form-group mb-2">
                <label>Name</label>
                <input type="text" name="name" v-model="name" class="form-control" maxlength="20">
            </div>
            <div class="form-group mb-2">
                <label>Description</label>
                <textarea name="description" v-model="description" class="form-control" cols="30" rows="10"></textarea>
            </div>
            <div class="form-group mb-2">
                <label>Category</label>
                <select class="form-control" name="category" v-model="category">
                    <option v-for="(category,index) in categories" :key="index" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>
            <div class="form-group mb-2">
                <label>Image</label>
                <input type="file" name="image" class="form-control" v-on:change="onImageChange">
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
</template>

<script>
import axios from 'axios'

    export default {
        data() {
            return {
                name: '',
                description: '',
                category: '',
                image: '',
                categories: [],
            }
        },
        created(){
            this.getCategories()
        },
        methods: {
            getCategories(){
                axios.get('/api/categories')
                    .then(response => {
                        this.categories = response.data
                    })
            },
            createAlbum(){
                const config = {
                    headers: { 'content-type': 'multipart/form-data' }
                }
                let formData = new FormData();
                formData.append('name', this.name);
                formData.append('description', this.description);
                formData.append('category_id', this.category);
                formData.append('image', this.image);
                axios.post('/albums/create', formData, config).then((response) => {
                    this.name='',
                    this.description='',
                    this.image='',
                    this.category=''
                }). catch((error) => {
                    console.log(error)
                });

            },
            onImageChange(e){
                this.image = e.target.files[0];

            }
        }

    }
</script>
