<template>
	<form method="GET" ref="form">
		<div>
			<b>Quartile</b>
			<div class="my-2">
				<div v-for="(value, index) in hIndexes" :key="index">
					<div class="form-check" v-if="value != 0">
						<input class="form-check-input" type="radio" name="h_index" :id="'exampleRadios'+index" :value="index" v-model="request.h_index" @change="handleChanges()">
						<label class="form-check-label" :for="'exampleRadios'+index">
						    {{ index }} ({{ value }})
						</label>
					</div>
				</div>
			</div>

			<b>Year</b>
			<div class="mt-2">
				<div v-for="(value, index) in metric.year" :key="index">
					<div class="form-check" v-if="value != 0">
						<input class="form-check-input" type="radio" name="year" :id="'yearRadios'+index" :value="index" v-model="request.year" @change="handleChanges()">
						<label class="form-check-label" :for="'yearRadios'+index">
						    {{ index }} ({{ value }})
						</label>
					</div>
				</div>
			</div>
		</div>
	</form>
</template>

<script>
export default {

  name: 'AuthorFilterBase',
  props: {
  	request: Object,
  	metric: Object,
  },
  data () {
    return {

    }
  },
  methods: {
  	handleChanges() {
  		this.$refs.form.submit()
  	}
  },
  computed: {
  	hIndexes: function () {
  		return this.metric.h_index
  	}
  }
}
</script>

<style lang="css" scoped>
</style>