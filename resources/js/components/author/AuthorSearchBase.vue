<template>
	<div class="container-fluid">
	    <div class="row justify-content-center">
	        <div class="col-md-8">
	            <h1>Authors Search Results</h1>
	        </div>
	    </div>
	    <div class="row py-4 justify-content-center">
	    	<div class="col-md-8">
	    		<author-search
	    		:query="$props"></author-search>
	    	</div>
	    </div>
	</div>
</template>

<script>
import AuthorSearch from './AuthorSearch';

export default {

  name: 'AuthorBase',
  props: {
  	last_name: String,
  	first_name: String,
  },
  data () {
    return {

    }
  },
  components: {
  	AuthorSearch,
  }
}
</script>

<style lang="css" scoped>
</style>