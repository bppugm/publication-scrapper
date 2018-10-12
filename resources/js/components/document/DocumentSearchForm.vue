<template>
	<div class="row">
		<div class="col-md-12">
			<div class="form-group">
				<label class="form-label">Title or Keywords</label>
				<input required type="text" name="keyword" class="form-control" v-model="keyword">
				<small class="form-text text-muted">Required</small>
			</div>
		</div>
		<div class="col-12 text-right">
			<a :href="url('/document/search')" v-if="emptyKeyword" class="btn btn-primary"><i class="fa fa-file-alt"></i> Show all documents</a>
			<button type="submit" v-else class="btn btn-success"><i class="fa fa-search fa-fw"></i>
				Search
			</button>
		</div>
	</div>
</template>

<script>
export default {

  name: 'DocumentSearchForm',

  data () {
    return {
    	keyword: '',
    }
  },
  computed: {
  	buttonText: function () {
  		if (this.keyword.length == 0) {
  			return "Show All Documents"
  		}

  		return "Search"
  	},
  	emptyKeyword: function () {
  		if (this.keyword.length == 0) {
  			return true
  		}

  		return false
  	}
  }
}
</script>

<style lang="css" scoped>
</style>