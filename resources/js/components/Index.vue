<template>
	<div class="w-25">
		<input v-model="title" name="title" type="text" class="mb-3 form-contol" placeholder="title">
		<!--class="btn - чтобы палец появился и +d-block а то размеры будут как у кнопки -->
		<div ref="dropzone" class="mb-3 btn d-block p-5 bg-dark text-center text-light">
			Upload
		</div>
		<input @click.prevent="store" type="submit" value="add" class="btn btn-primary">

		<div v-if="post" class="mt-5">
			<div>
				<h4>{{ post.title }}</h4>
				<div v-for="image in post.images" class="mb-2">
					<img :src="image.url">
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Dropzone from 'dropzone';
export default {
	name: 'Index',

	data() {
		return {
			dropzone: null,
			title: null,
			post: null,
		}
	},

	mounted() {
		this.dropzone = new Dropzone(this.$refs.dropzone, {
			/** Опции из https://docs.dropzone.dev/configuration/basics/configuration-options */
			url: '/api/posts', // обязательное свойство, указываем что угодно(пока это не имеет значения)
			clickable: true, // true - по умолчанию
			autoProcessQueue: false, // Отключаем автоматическую отправку изображения 
			addRemoveLinks: true, // добавляем ссылку на удаление 'Remove file' (внизу под картинкой)
		});

		this.getPosts();
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
			this.title = ''; // удаляем title
			axios.post('/api/posts', data);
		},

		getPosts() {
			axios.get('/api/posts')
				.then(res => {
					this.post = res.data.data;
					console.log(this.post);
				});
		}
	},
}
</script>

<style scoped></style>
