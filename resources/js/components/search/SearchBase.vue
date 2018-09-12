<template>
	<div class="container">
	    <div class="row">
	        <div class="col-md-8">
	            <h1>Document Search</h1>
	        </div>
	    </div>
	    <div class="row py-4">
	    	<div class="col-12">
	            <document-list></document-list>
	    	</div>
	    </div>
	</div>
</template>

<script>
import DocumentList from '../document/DocumentList';

export default {

  name: 'SearchBase',

  data () {
    return {

    }
  },
  components: {
  	DocumentList,
  }
}
</script>

<style lang="css" scoped>
</style>