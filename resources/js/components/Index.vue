<template>
	<div class="w-25">
		<!--class="btn - чтобы палец появился и +d-block а то размеры будут как у кнопки-->
		<div ref="dropzone" class="btn d-block p-5 bg-dark text-center text-light">
			Upload
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
		}
	},

	mounted() {
		this.dropzone = new Dropzone(this.$refs.dropzone, {
			/** Опции из https://docs.dropzone.dev/configuration/basics/configuration-options */
			url: 'qwqwqw', // обязательное свойство, указываем что угодно(пока это не имеет значения)
			clickable: true, // true - по умолчанию
		});
	}
}
</script>

<style scoped></style>
