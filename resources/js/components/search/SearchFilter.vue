<template>
	<section class="sticky-top">
		<b>Filter Documents</b>
		<div class="card mt-3">
			<div class="card-body">
				<b>Quartile</b>
				<div class="mt-2">
					<div class="form-check" v-for="(item, index) in hIndexes" :key="index">
					  <input class="form-check-input" type="radio" name="h_index" :id="'exampleRadios'+index" :value="item" v-model="queryQuartile">
					  <label class="form-check-label" :for="'exampleRadios'+index">
					    {{ item }}
              <span v-if="item == ''">All</span>
					  </label>
					</div>
				</div>
			</div>
		</div>
	</section>
</template>

<script>
export default {

  name: 'SearchFilter',

  data () {
    return {

    }
  },
  computed: {
  	query: function () {
      return this.$store.getters['query/getQuery']
  	},
  	queryQuartile: {
  		get: function () {
  			return this.query.h_index
  		},
  		set: function (value) {
  			this.$store.commit('query/UPDATE_QUERY_FILTER', {
          type: 'h_index',
          value: value,
        })
  		}
  	},
  	filter: function () {
  		return this.$store.getters['query/getFilters']
  	},
  	hIndexes: function () {
  		return this.filter.h_index
  	}
  }
}
</script>

<style lang="css" scoped>
</style>