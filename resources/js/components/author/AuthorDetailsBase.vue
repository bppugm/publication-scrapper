<template>
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-md-12">
	            <h1>Author Details #{{ author_id }}</h1>
	        </div>
	    </div>
	    <div class="row py-4 justify-content-center">
	    	<div class="col-md-12">

	    	</div>
	    </div>
	</div>
</template>

<script>
export default {

  name: 'AuthorDetailsBase',
  props: {
  	author_id: String,
  },
  data () {
    return {

    }
  }
}
</script>

<style lang="css" scoped>
</style>