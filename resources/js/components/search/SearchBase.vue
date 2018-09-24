<template>
	<div class="container-fluid">
	    <div class="row">
	        <div class="col-md-8">
	            <h1>Documents Search</h1>
	        </div>
	    </div>
	    <div class="row py-4">
	    	<div class="col-md-2">
	    		<search-filter></search-filter>
	    	</div>
	    	<div class="col-md-10">
	            <document-list></document-list>
	    	</div>
	    </div>
	</div>
</template>

<script>
import DocumentList from '../document/DocumentList';
import SearchFilter from './SearchFilter';

export default {

  name: 'SearchBase',

  data () {
    return {

    }
  },
  components: {
  	DocumentList,
  	SearchFilter,
  }
}
</script>

<style lang="css" scoped>
</style>