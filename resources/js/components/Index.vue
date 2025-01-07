<template>
	<div class="w-25">
		<input v-model="title" type="text" class="mb-3 form-contol" placeholder="title">
		<!--class="btn - чтобы палец появился и +d-block а то размеры будут как у кнопки -->
		<div ref="dropzone" class="mb-3 btn d-block p-5 bg-dark text-center text-light">
			Upload
		</div>

		<!--ВСТАВИЛ @image-added @change-->
		<div class="mb-3">
			<vue-editor useCustomImageHandler @image-added="handleImageAdded" v-model="content" />
		</div>

		<!--ИЗМЕНИЛ-->
		<input @click.prevent="update" type="submit" value="Update" class="btn btn-primary">
		<div v-if="post" class="mt-5">
			<div>
				<h4>{{ post.title }}</h4>
				<div v-for="image in post.images" class="mb-2">
					<img :src="image.preview_url" class="mb-3">
					<img :src="image.url">
				</div>

				<!--ДОБАВИЛИ, 
					для наследования класса от родителя
					(выравнивания и т.д.-class="ql-editor")-->
				<div class="ql-editor" v-html="post.content"></div>

			</div>
		</div>
	</div>
</template>

<script>
import Dropzone from 'dropzone';
//import Quill from 'quill';
import { VueEditor } from "vue3-editor";
export default {
	name: 'Index',

	data() {
		return {
			dropzone: null,
			title: null,
			post: null,
			content: null,
			imagesIdsForDelete: [], // ДОБАВИЛИ
		}
	},

	components: {
		VueEditor
	},

	mounted() {
		this.dropzone = new Dropzone(this.$refs.dropzone, {
			/** Опции из https://docs.dropzone.dev/configuration/basics/configuration-options */
			url: '/api/posts', // обязательное свойство, указываем что угодно(пока это не имеет значения)
			clickable: true, // true - по умолчанию
			autoProcessQueue: false, // Отключаем автоматическую отправку изображения 
			addRemoveLinks: true, // добавляем ссылку на удаление 'Remove file' (внизу под картинкой)
		});

		this.dropzone.on('removedfile', (file) => { // ДОБАВИЛИ
			console.log(file); // при удалении файла, я увижу его в консоли
			this.imagesIdsForDelete.push(file.id);
		});

		this.getPost();
	},

	methods: {
		update() {
			const data = new FormData();
			const files = this.dropzone.getAcceptedFiles();
			files.forEach(file => {
				data.append('images[]', file);
				this.dropzone.removeFile(file); // удаляем файлы из dropzone
			})

			this.imagesIdsForDelete.forEach(idForDelete => { // ДОБАВИЛИ
				data.append('images_ids_for_delete[]', idForDelete);
			});

			data.append('title', this.title);
			data.append('content', this.content);
			data.append('_method', 'PATCH');
			this.title = '';
			this.content = '';
			axios.post(`/api/posts/${this.post.id}`, data) // ИЗМЕНИЛ, но patch(в роуте нам нужен patch) мы укажем в data.append('_method', 'PATCH')(выше), а здесь post
				.then(res => {
					this.getPost();
				})
		},

		getPost() {
			axios.get('/api/posts')
				.then(res => {
					this.post = res.data.data;

					this.title = this.post.title;
					this.content = this.post.content;


					this.post.images.forEach(image => {
						/**
						 * 1. Создаем файл (объект с атрибутами)
						 * 2. Обращаемся к нашему this.dropzone и отображаем его использую URL
						 */
						let file = { id: image.id, name: image.name, size: image.size }; // 1. ДОБАВИЛИ id
						this.dropzone.displayExistingFile(file, image.url);
					});
				});
		},

		handleImageAdded(file, Editor, cursorLocation, resetUploader) {
			var formData = new FormData();
			formData.append("image", file);

			axios.post('/api/posts/images', formData) // изменили по нашему
				.then(result => {
					const url = result.data.url;
					Editor.insertEmbed(cursorLocation, "image", url);
					resetUploader();
				})
				.catch(err => {
					console.log(err);
				});

		},
	},
}
</script>

<style>
.dz-success-mark,
.dz-error-mark {
	display: none;
}
</style>
