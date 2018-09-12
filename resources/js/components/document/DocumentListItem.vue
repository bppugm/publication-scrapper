<template>
	<div class="row">
		<div class="col-md-10">
			<h5 class="document-font">
				{{ title }}
			</h5>
		</div>
		<div class="col-md-2">
			<span class="badge badge-success">{{ HIndex }}</span>
		</div>
		<div class="col-md-12">
			<span class="text-muted">Published at: {{ coverDate }}</span>
		</div>
	</div>
</template>

<script>
export default {

  name: 'DocumentListItem',
  props: {
  	title: String,
  	authors: Array,
  	citedByCount: String,
  	PublicationName: String,
  	HIndex: String,
  	coverDate: String,
  },
  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>