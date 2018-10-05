<template>
	<div class="">
    <skeleton v-if="loading">
      <div class="card card-body my-2">
        <div class="post">
          <div class="avatar"></div>
          <div class="line"></div>
          <div class="line"></div>
        </div>
      </div>
    </skeleton>
		<div class="row" v-else>
			<div class="col-md-12">
				<div class="card card-body">
					<h3 class="m-0">
						{{ lastName }}, {{ firstName }}
					</h3>
					<span>{{ affiliation }}</span> <br>
					<span class="text-muted">Scopus ID: {{ authorId }}</span>
				</div>
			</div>
			<div class="col-md-4 mt-2">
				<div class="card card-body text-center">
					<h5>Total documents</h5>
					<i class="far fa-file fa-2x text-primary"></i> 
					<h3 class="text-primary m-0"><b>{{ totalDocuments }}</b></h3> 
				</div>
			</div>
			<div class="col-md-4 mt-2">
				<div class="card card-body text-center">
					<h5>H Index</h5>
					<i class="fa fa-chart-line fa-2x text-primary"></i> 
					<h3 class="text-primary m-0"><b>{{ hIndex }}</b></h3> 
				</div>
			</div>
			<div class="col-md-4 mt-2">
				<div class="card card-body text-center">
					<h5>Cited by</h5>
					<i class="fa fa-quote-left fa-2x text-primary"></i> 
					<h3 class="text-primary m-0"><b>{{ citedBy }}</b></h3> 
				</div>
			</div>
		</div>
	</div>
</template>

<script>
import Skeleton from '../Skeleton';

export default {

  name: 'AuthorDetailsProfile',
  props: {
  	authorId: String,
  },
  data () {
    return {
    	author: {},
    	loading: true
    }
  },
  methods: {
  	async fetchAuthor() {
  		let response = await axios.get(this.url(`/api/authors/${this.authorId}`))
  		this.author = response.data.data[0]
  	},
  },
  computed: {
  	lastName: function () {
  		return this.author['preferred-name']['surname']
  	},
  	firstName: function () {
  		return this.author['preferred-name']['given-name']
  	},
  	affiliation: function () {
  		return this.author['affiliation-current']['affiliation-name']
  	},
  	totalDocuments: function () {
  		return this.author['coredata']['document-count']
  	},
  	citedBy: function () {
  		return this.author['coredata']['cited-by-count']
  	},
  	hIndex: function () {
  		return this.author['h-index']
  	}
  },
  async mounted() {
  	await this.fetchAuthor()
  	this.loading = false
  },
  components: {
    Skeleton,
  }
}
</script>

<style lang="css" scoped>
	.fa, .far {
		margin-bottom: 0.5rem;
	}
</style>