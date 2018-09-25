<template>
	<a class="d-flex justify-content-between" :href="'/author/'+authId">
		<h5 class="m-0">{{ name }}</h5>
		<span class="text-muted">{{ authId }}</span>
	</a>
</template>

<script>
export default {

  name: 'AuthorListItem',
  props: {
  	name: String,
  	affiliations: Array,
  	authId: String,
  },
  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>