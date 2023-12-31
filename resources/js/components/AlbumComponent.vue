<template>
    <div>
        <form @submit.prevent="createAlbum" v-if="!success" action="" enctype="multipart/form-data">
            <div class="form-group mb-2">
                <label>Name</label>
                <input type="text" name="name" v-model="name" class="form-control" maxlength="20">
                <span v-if="allErrors.name" :class="['text-danger']">
                    {{ allErrors.name[0] }}
                </span>
            </div>
            <div class="form-group mb-2">
                <label>Description</label>
                <textarea name="description" v-model="description" class="form-control" cols="30" rows="10"></textarea>
                <span v-if="allErrors.description" :class="['text-danger']">
                    {{ allErrors.description[0] }}
                </span>
            </div>
            <div class="form-group mb-2">
                <label>Category</label>
                <select class="form-control" name="category" v-model="category">
                    <option v-for="(category,index) in categories" :key="index" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
                <span v-if="allErrors.category" :class="['text-danger']">
                    {{ allErrors.category[0] }}
                </span>
            </div>
            <div class="form-group mb-2">
                <label>Image</label>
                <input type="file" name="image" class="form-control" v-on:change="onImageChange">
                <span v-if="allErrors.image" :class="['text-danger']">
                    {{ allErrors.image[0] }}
                </span>
            </div>
            <div class="form-group">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
        <div v-if="success">
            <a :href="'gallery/' + albumId">Y our album has been created. Click here to upload images</a>
        </div>

    </div>
</template>

<script>
import axios, { all } from 'axios'

    export default {
        data() {
            return {
                name: '',
                description: '',
                category: '',
                image: '',
                categories: [],
                albumId: '',
                success: false,
                allErrors: []
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
                axios.post('/albums/store', formData, config).then((response) => {
                    this.name='',
                    this.description='',
                    this.image='',
                    this.category=''
                    this.albumId = response.data.id
                    this.success = true
                    console.log(response)
                }). catch((error) => {
                    this.allErrors = error.response.data.errors
                });

            },
            onImageChange(e){
                this.image = e.target.files[0];

            }
        }

    }
</script>
