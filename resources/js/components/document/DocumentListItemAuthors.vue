<template>
	<div>
		<span v-for="(author, index) in firstBatch">
			<a href="#">{{ author }}</a>
			<span v-if="index + 1 != firstBatch.length">, &nbsp;</span>
		</span>
		<span v-if="tooManyAuthors">
			(...)
			<a href="#">{{ lastAuthor }}</a>
		</span>
	</div>
</template>

<script>
export default {

  name: 'DocumentListItemAuthors',
  props: {
  	authors: Array,
  },
  data () {
    return {

    }
  },
  computed: {
  	authorNames: function () {
  		return this.authors.map(item => {
  			return item.authname
  		}); 	
  	},
  	uniqueNames: function () {
  		return [...new Set(this.authorNames)]	
  	},
  	firstBatch: function () {
  		let authors = [];
  		for (var i = 0; i <= this.iterationNumber; i++) {
  			authors[i] = this.uniqueNames[i]
  		}
  		return authors
  	},
  	iterationNumber: function () {
  		if (this.tooManyAuthors) {
  			return 1
  		} else {
  			return this.uniqueNames.length - 1
  		}
  	},
  	tooManyAuthors: function () {
  		if (this.uniqueNames.length > 3) {
  			return true
  		} else {
  			return false
  		}
  	},
  	lastAuthor: function () {
  		return this.uniqueNames[this.uniqueNames.length-1]
  	}
  }
}
</script>

<style lang="css" scoped>
</style>