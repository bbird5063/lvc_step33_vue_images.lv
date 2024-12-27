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

		<input @click.prevent="store" type="submit" value="add" class="btn btn-primary">
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

		this.getPost();
	},

	methods: {
		store() {
			const data = new FormData();
			const files = this.dropzone.getAcceptedFiles();
			files.forEach(file => {
				data.append('images[]', file);
				this.dropzone.removeFile(file); // удаляем файлы из dropzone
			})
			data.append('title', this.title);
			data.append('content', this.content);
			this.title = ''; // удаляем title
			this.content = ''; // удаляем content
			axios.post('/api/posts', data)
				.then(res => {
					this.getPost(); // чтобы лента обновилась
				})
		},

		getPost() {
			axios.get('/api/posts')
				.then(res => {
					this.post = res.data.data;
					console.log(this.post);
				});
		},

		handleImageAdded(file, Editor, cursorLocation, resetUploader) {
			// Пример использования FormData
			// ПРИМЕЧАНИЕ: Ваш ключ может быть другим, например:
			// formData.append('file', file)
			var formData = new FormData();
			formData.append("image", file);

			axios.post('/api/posts/images', formData) // изменили по нашему
				.then(result => {
					const url = result.data.url; // Get url from response
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
