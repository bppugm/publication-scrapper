<template>
	<div class="row">
		<div class="col-md-7">
			<span class="text-primary">{{ subType }}</span>
			<h6 class="document-font" v-html="title">
			</h6>
		</div>
		<div class="col-md-3">
			<document-list-item-authors
			:authors="authors"
			></document-list-item-authors>
		</div>
		<div class="col-md-2">
			<span class="badge badge-success">{{ hIndex }}</span>
		</div>
		<div class="col-md-12">
			<span class="text-muted">Source: {{ publicationName }}</span>
		</div>
	</div>
</template>

<script>
import DocumentListItemAuthors from './DocumentListItemAuthors';

export default {

  name: 'DocumentListItem',
  props: {
  	title: String,
  	authors: Array,
  	citedByCount: String,
  	publicationName: String,
  	hIndex: String,
  	coverDate: String,
  	subType: String,
  },
  data () {
    return {

    }
  },
  components: {
  	DocumentListItemAuthors,
  }
}
</script>

<style lang="css" scoped>
</style>