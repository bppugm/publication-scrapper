<template>
	<div class="row">
		<div class="col-md-7">
			<span class="text-primary">{{ subType }}</span>
			<h6 class="document-font" v-html="title">
			</h6>
		</div>
		<div class="col-md-3">
			<br>
			<document-list-item-authors
			:authors="authors"
			></document-list-item-authors>
		</div>
		<div class="col-md-2">
			<span v-if="hasQuartile" class="badge badge-success">{{ hIndex }}</span>
			<span v-else class="badge badge-secondary">Quartile N/A</span>
		</div>
		<div class="col-md-7 small-spacing">
			<small class="text-muted">Source: {{ publicationName }}</small>
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
  computed: {
  	hasQuartile: function () {
  		if (this.subType == 'Article') {
  			return true
  		} else {
  			return false
  		}
  	}
  },
  components: {
  	DocumentListItemAuthors,
  }
}
</script>

<style lang="css" scoped>
	.small-spacing {
		line-height: 1;
	}
</style>